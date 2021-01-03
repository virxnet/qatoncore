<?php

namespace VirX\Qaton;

class Loader
{
    public $controller_class;
    public $controller_file;
    public $controller_namespace;

    public function controller(string $class = null, string $file = null, string $namespace = null, bool $output = true)
    {
        $this->controller_namespace = $namespace;
        $this->controller_class = $this->controller_namespace . $class;
        $this->controller_file = $file;
        //$this->registerAutoload();
        if ($output === false) {
            ob_start();
        }
        $controller = new $this->controller_class();
        if ($output === false) {
            ob_clean();
        }
        return $controller;
    }

    /*
    private function registerAutoload()
    {
        spl_autoload_register(function ($class) {
            require_once($this->controller_file);
        });
    }
    */
}
