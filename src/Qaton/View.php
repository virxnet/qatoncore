<?php

namespace VirX\Qaton;

use Exception;

class View
{
    public $controller;
    public $base_url;
    
    public function __construct(Controller &$controller)
    {
        $this->controller = &$controller;
        $this->_setBaseUrl();
    }
    
    public function render($view_file, $data=false, $return=false)
    {
        
        // TODO: Majob fix: This is ugly, these paths need to be fixed from the source 
        $view_file = $this->controller->SYSTEM->BASE_PATH.DIRECTORY_SEPARATOR.$this->controller->SYSTEM->APP_NAME.DIRECTORY_SEPARATOR.$this->controller->views_dir.$view_file.$this->controller::PHP_EXT;

        if ($this->controller->SYSTEM->HTTP_CONFIG['APP_DEBUG'] === true)
        {
            __debug($data, "VIEW {$view_file} DATA");
        }

        if ($file = realpath($view_file))
        {
           if (is_array($data))
           {
                extract($data);
           }
           
            if ($return === true)
           {
                ob_start();
                include($view_file);
                return ob_get_clean();
           }
           else
           {
                include($view_file);
           }
            
        }
        else
        {
            throw new \Exception('View Not Found : ' . $view_file);
        }
    }

    private function _setBaseUrl()
    {
        if (isset($_SERVER['HTTP_HOST']))
        {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $this->base_url = $protocol.$_SERVER['HTTP_HOST'].$this->controller->SYSTEM->HTTP_CONFIG['APP_URL_SUB_DIR'];
        }

        return null;
    }

    public function __destruct()
    {
        if ($this->controller->SYSTEM->HTTP_CONFIG['APP_DEBUG'] === true)
        {
            __debug($this->base_url, 'BASE_URL');
        }
    }

}