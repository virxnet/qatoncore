<?php

namespace VirX\Qaton;

use Exception;

class View
{
    public $controller;
    
    public function __construct(Controller &$controller)
    {
        $this->controller = &$controller;
    }
    
    public function render($view_file, $data=false, $return=false)
    {
        // TODO: Majob fix: This is ugly, these paths need to be fixed from the source 
        $view_file = $this->controller->SYSTEM->BASE_PATH.DIRECTORY_SEPARATOR.$this->controller->SYSTEM->APP_NAME.DIRECTORY_SEPARATOR.$this->controller->views_dir.$view_file.$this->controller::PHP_EXT;

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

}