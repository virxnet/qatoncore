<?php

namespace VirX\Qaton;

use Exception;
use VirX\Qaton\View;

use function PHPSTORM_META\elementType;

/* Temporary notes about Controller routing
    - automated routing is built-in to the Controller class
    - all 404s will maintain the complete user request array
    - a 404 will be issued if a Controller is not found
    - a 404 will be issued if a Contoller is missing required methods
    - the user request array is a single dimension array and will have Int indexes 
    - the user request is parsed from left to right (top to bottom) incrementing keys
    - if a Controller is found on the first run, it will be set
    - if a Controller is not found, will check if the request is a dir
    - if it's a dir, will look for the default Controller (index)
    - if the index exists, the it will be set as the active controller 
    - if an index does not exist, then two things are possible
        - if user request had more items, will check the next item
        - if the user request does not have anymore items a 404 is issued
    - When Controller files are found (index or not), the remaning user request is passed as the active request to the Controller
    - The first item in active request after a Controller is called will be the Method name
    - All remaning items on the active request after the Method name will be method params
    - The Controller class will traverse through the user request looking for Controller files, dirs and indexes. If none are found 404 is issued
    - The 404 itself is a Controller 

    - Controller filenames need to be lowercase 
    - Controller class names need to be Ucfirst
    - Controllers need to be namespaced from the base dir. 
      So if base is /var/www/app/cont/ and controller is /var/www/app/cont/a/b/c.ph
      then namespace will be \a\b;
    - 
*/
class Controller 
{
    
    const PHP_EXT = '.php';
    
    public $SYSTEM;
    public $request;
    public $base_dir;
    public $app_root_dir;
    public $views_dir;
    public $view;
    public $active_controller;
    public $active_controller_namespace = array();
    public $active_controller_class;
    public $active_params;
    public $default_controller;
    public $default_method;
    public $fallback_controller;
    public $last_default_controller = array();
    public $controllers_namespace;


    public function __construct(System $system)
    {
        $this->SYSTEM = &$system;
        $this->_autoload_register();
    }

    public function _initHttpController()
    {
        $this->request = &$this->SYSTEM->USER_HTTP_REQUEST;
        $this->base_dir = &$this->SYSTEM->CONTOLLERS_PATH;
        $this->app_root_dir = &$this->SYSTEM->BASE_PATH;
        $this->views_dir = &$this->SYSTEM->HTTP_CONFIG['APP_VIEWS_PATH'];
        $this->default_controller = &$this->SYSTEM->HTTP_CONFIG['APP_DEFAULT_CONTROLLER'];
        $this->default_method = &$this->SYSTEM->HTTP_CONFIG['APP_DEFAULT_METHOD'];
        $this->fallback_controller = &$this->SYSTEM->HTTP_CONFIG['APP_FALLBACK_CONTROLLER'];
        $this->controllers_namespace = &$this->SYSTEM->CONTROLLERS_NAMESPACE;
        $this->active_request = $this->request;
        $this->active_controller = $this->_setActiveController();
        $this->active_class = $this->_setActiveControllerClass();
        $this->active_method = $this->_setActiveMethod();
        $this->view = new View($this);
    }
    
    public function _setActiveMethod()
    {
        if (is_array($this->active_request))
        {
            $this->active_method = array_shift($this->active_request);
        }
        else
        {
            $this->active_method = false;
        }

        $this->active_params = $this->active_request;

        return $this->active_method;
    }

    public function _instantiateController()
    {
        if ($this->active_method)
        {

            ob_start();
            $obj = new $this->active_controller_class($this);
            $method = $this->active_method;

            if (method_exists($obj, $method))
            {
                
                $reflection = new \ReflectionMethod($this->active_controller_class, $method);

                if ($this->SYSTEM->HTTP_CONFIG['APP_DEBUG'] === true)
                {
                    __debug(json_decode(json_encode($reflection->getParameters(), JSON_OBJECT_AS_ARRAY)), 'ACTIVE_METHOD_PARAMETERS');
                    __debug(json_decode(json_encode($reflection->getNumberOfRequiredParameters(), JSON_OBJECT_AS_ARRAY)), 'ACTIVE_METHOD_PARAMETER_COUNT');
                }
                
                if ($reflection->getNumberOfRequiredParameters() == count($this->active_params))
                {
                    if (empty($this->active_params))
                    {
                        return $obj->$method();
                    }
                    else
                    {
                        call_user_func_array(array($obj, $method), array_values($this->active_params));
                    }
                }
                else
                {
                    ob_clean();
                    $this->_activateHttpFallbackController();
                }
                
            }
            else
            {
                ob_clean();
                $this->_activateHttpFallbackController();
            }
            
        }

        return new $this->active_controller_class($this);
        
    }

    public function _instantiateActiveController()
    {

        $this->_setActiveControllerClass();
        try 
        {
            if (class_exists($this->active_controller_class, true))
            {
                //return new $this->active_controller_class($this); // TODO: find a secure way to pass parameters into method or call methods in class if params don't exist
                return $this->_instantiateController();
            }
            throw new \Exception('Error Instantiating Active Controller Class : ' . $this->active_controller_class);
        }
        catch (Exception $e)
        {
            echo "<pre>";
            debug_print_backtrace();
            trigger_error($e->getMessage(), E_USER_WARNING);
            $this->_activateHttpFallbackController();
            try 
            {
                if (class_exists($this->active_controller_class, true))
                {
                    //return new $this->active_controller_class($this);
                    return $this->_instantiateController();
                }
                throw new \Exception('Error Instantiating Fallback Controller Class : ' . $this->active_controller_class);
            }
            catch (Exception $e)
            {
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit('404');
            }
        }
    }

    public function _setActiveControllerClass()
    {
        $this->active_controller_class = $this->controllers_namespace.$this->_getActiveClassNamespace().$this->_getActiveClassProperName();
        return $this->active_controller_class;
    }

    public function _overrideActiveConteoller(String $controller)
    {
        $this->active_controller_namespace = array();
        $this->active_controller = $controller;
        $this->active_class = 'Class';
    }

    public function _setActiveController(String $path_trail='', Int $key=0)
    {        
        
        $dir = $this->base_dir.DIRECTORY_SEPARATOR.$path_trail;

        if (empty($this->active_request))
        {
            if ($file = $this->_setDefaultController($this->active_request, $dir))
            {
                return $file;
            }
            else
            {
                return $this->_activateHttpFallbackController();
            }
        }
        else
        {
            $file = $dir.$this->active_request[$key].self::PHP_EXT;

            if ($file = realpath($file))
            {
                unset($this->active_request[$key]);
                return $file;
            }
            elseif ($dir = realpath($dir))
            {
                $file = $this->_setDefaultController($this->active_request, $dir);
                $this->active_controller_namespace[] = $this->active_request[$key];
            }

            $path_trail .= $this->active_request[$key].DIRECTORY_SEPARATOR;

            if (!empty($this->active_request))
            {   
                unset($this->active_request[$key]);
                return $this->_setActiveController($path_trail, $key+1);
            }
            else
            {
                // Default
                $this->active_request = $this->last_default_controller['request'];
                if (!empty($this->last_default_controller))
                {
                    $this->active_controller = $this->last_default_controller['controller'];
                }
                // 404
                else
                {
                    return $this->_activateHttpFallbackController();
                }
                return $this->active_controller;
            }
        }

    }

    public function _setDefaultController(Array $request, String $dir)
    {
        $file = $dir.DIRECTORY_SEPARATOR.$this->default_controller.self::PHP_EXT;

        if ($file = realpath($file))
        {
            $this->last_default_controller['controller'] = $file;
            $this->last_default_controller['request'] = $request;
            return $file;
        }
        else
        {
            return false;
        }
    }

    public function _activateHttpFallbackController()
    {
        $controller = $this->base_dir.DIRECTORY_SEPARATOR.mb_strtolower($this->fallback_controller).self::PHP_EXT;
        $this->_overrideActiveConteoller($controller);
        if (realpath($controller))
        {
            $this->active_controller = $controller;
            $this->active_request = $this->request;
            HttpHeaders::setByCode(404);
            $this->_setActiveControllerClass();
            return $this->active_controller;
        }
        else
        {
            throw new \Exception('HTTP Fallback Controller Missing: ' . $controller);
        }
    }

    public function _getActiveClassNamespace()
    {
        $namespace = implode('\\', $this->active_controller_namespace);
        if (!empty($this->active_controller_namespace))
        {
            $namespace .= '\\';
        }
        return $namespace;
    }

    public function _getClassNamespacePath(String $class)
    {
        $class = explode('\\', $class);
        $class_name = end($class);
        $class[key($class)] = mb_strtolower($class[key($class)]);
        $class = implode('\\', $class);
        return DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $class);
    }

    public function _getActiveClassProperName()
    {
        return ucfirst(pathinfo($this->active_controller)['filename']);
    }

    public function _autoload_register()
    {
        spl_autoload_register(function ($class) {
            $controller = $this->app_root_dir.$this->_getClassNamespacePath($class).self::PHP_EXT;
            if (realpath($controller)) 
            {
                require_once($controller);
                return true;
            }
            throw new \Exception('Autoloading Controller Class Failed : ' . $class . ' from file ' . $controller);
        });
    }

    public function __destruct()
    {
        if ($this->SYSTEM->HTTP_CONFIG['APP_DEBUG'] === true)
        {
            __debug($this->SYSTEM->HTTP_CONFIG, 'CONFIG');
            __debug($this->active_controller, 'ACTIVE_CONTROLLER');
            __debug($this->active_controller_namespace, 'ACTIVE_CONTROLLER_NAMESPACE');
            __debug($this->active_request, 'ACTIVE_REQUEST');
            __debug($this->last_default_controller, 'LAST_DEFAULT_CONTROLLER');
            __debug($this->active_class, 'ACTIVE_CLASS');
            __debug($this->active_method, 'ACTIVE_METHOD');
            __debug($this->active_params, 'ACTIVE_PARAMS');
            __debug($this->default_controller, 'DEFAULT_CONTROLLER');
            __debug($this->default_method, 'DEFAULT_METHOD');
            __debug($this->fallback_controller, 'FALLBACK_CONTROLLER');
            __debug($this->controllers_namespace, 'CONTROLLERS_NAMESPACE');
            __debug($this->base_dir, 'BASE_DIR');
            __debug($this->app_root_dir, 'APP_ROOT_DIR');
            __debug($this->views_dir, 'VIEWS_DIR');
            __print_debug();
        }
    }
    
}
