<?php

namespace VirX\Qaton;

class System 
{
    const REQUIRED_HTTP_CONFIG_ITEMS = array(
        'APP_PATH',
        'APP_PATH_RELATIVE_TO_CALLER',
        'APP_CONTROLLERS_PATH',
        'APP_DEFAULT_CONTROLLER',
        'APP_DEFAULT_METHOD',
        'APP_FALLBACK_CONTROLLER',
        'APP_VIEWS_PATH',
        'APP_URL_SUB_DIR'
    );
    
    //
    public $APP_NAME;
    public $BASE_PATH;
    public $CALLER_PATH;
    public $USER_HTTP_REQUEST;
    public $HTTP_CONFIG;
    public $CONTOLLERS_PATH;
    public $CONTROLLERS_NAMESPACE;

    //
    public $Controller;

    public function __construct(String $caller_path)
    {
        $this->CALLER_PATH = $caller_path;
    }

    public function getHttpResource(Array $config)
    {
        $this->HTTP_CONFIG = $config;
        $this->_checkConfigRequirements();
        $this->_initEnvironment();
        $this->_setUserHttpRequest();
        $this->Controller = new Controller($this);
        $this->Controller->_initHttpController();
        $this->Controller->_instantiateActiveController();
        
    }

    private function _checkConfigRequirements()
    {
        foreach (self::REQUIRED_HTTP_CONFIG_ITEMS as $config_key)
        {
            if (!isset($this->HTTP_CONFIG[$config_key]))
            {
                throw new \Exception("Config Setting {$config_key} Undefined");
            }
        }
    }

    private function _initEnvironment()
    {

        if ($this->HTTP_CONFIG['APP_PATH_RELATIVE_TO_CALLER'] == 'true')
        {
            if (!$this->HTTP_CONFIG['APP_PATH'] = realpath($this->CALLER_PATH . '/' . $this->HTTP_CONFIG['APP_PATH'].DIRECTORY_SEPARATOR))
            {
                throw new \Exception('APP_PATH Invalid: ');
            }
        }
        elseif (!realpath($this->HTTP_CONFIG['APP_PATH']))
        {
            throw new \Exception('APP_PATH Invalid');
        }
        
        $app_pathinfo = pathinfo($this->HTTP_CONFIG['APP_PATH']);
        $this->APP_NAME = $app_pathinfo['basename'];
        $this->BASE_PATH = $app_pathinfo['dirname'];

        if (!$this->CONTOLLERS_PATH = realpath($this->HTTP_CONFIG['APP_PATH'].DIRECTORY_SEPARATOR.$this->HTTP_CONFIG['APP_CONTROLLERS_PATH'].DIRECTORY_SEPARATOR))
        {
            throw new \Exception('APP_CONTROLLERS_PATH Invalid');
        }

        $this->CONTROLLERS_NAMESPACE = $this->APP_NAME . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $this->HTTP_CONFIG['APP_CONTROLLERS_PATH']);

    }

    private function _setUserHttpRequest()
    {
        if (mb_substr(Http::server('REQUEST_URI'), 0, mb_strlen($this->HTTP_CONFIG['APP_URL_SUB_DIR'])) === $this->HTTP_CONFIG['APP_URL_SUB_DIR'])
        {
            $request_url = '/'.mb_substr(Http::server('REQUEST_URI'), mb_strlen($this->HTTP_CONFIG['APP_URL_SUB_DIR']));
        }
        else
        {
            $request_url = Http::server('REQUEST_URI');
        }
        
        $this->USER_HTTP_REQUEST = explode('/', explode('?', $request_url, 2)[0]);

        foreach ($this->USER_HTTP_REQUEST as $i => $req)
        {
            if ($req == '' || is_null($req))
            {
                unset($this->USER_HTTP_REQUEST[$i]);
            }
        }
        
        $this->USER_HTTP_REQUEST = array_values($this->USER_HTTP_REQUEST); 
        if (isset($this->USER_HTTP_REQUEST[0]))
        {
            if ($this->USER_HTTP_REQUEST[0] == basename(__FILE__))
            {
                unset($this->USER_HTTP_REQUEST[0]);
            }
        }
    }
}