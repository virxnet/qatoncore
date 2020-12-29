<?php

namespace VirX\Qaton;

use VirX\Qaton\Error;
use VirX\Qaton\Loader;

class Router
{

    public const PHP_EXT = '.php';

    public $request;
    public $config;
    public $route;
    public $requested_route;

    public $loader;
    public $namespace;
    public $controller;
    public $class;
    public $method;
    public $params = [];
    public $controller_is_default = false;
    public $controller_is_fallback = false;
    public $method_is_default = false;
    public $method_is_fallback = false;

    public function __construct(Request $request, array $config)
    {
        $this->request = $request;
        $this->config = $config;
        $this->initNamespace();
        $this->setRoute();
        $this->loader = new Loader();
        $this->setController();
        $this->setFallback();
    }

    public function setFallback()
    {
        if ($this->method_is_fallback === true) {
            $this->route = explode('/', $this->config['APP_FALLBACK_CONTROLLER']);
            $this->initNamespace();
            $this->method = $this->config['APP_DEFAULT_METHOD'];
            $this->setController();
        }
    }

    public function setController()
    {
        $file = $this->resolveActiveContolller();
        $instance = $this->loader->controller($this->class, $file, $this->namespace);
        $this->controller = [
            'instance' => $instance,
            'file' => $file,
            'class' => $this->class,
            'namespace' => $this->namespace
        ];
        $this->method = $this->resolveActiveMethod();
    }

    public function resolveActiveMethod()
    {
        if (!empty($this->route)) {
            $method = array_shift($this->route);
        } else {
            $this->method_is_default = true;
            $method = $this->config['APP_DEFAULT_METHOD'];
        }

        if (method_exists($this->controller['instance'], $method)) {
            $reflection = new \ReflectionMethod($this->namespace . $this->class, $method);
            if ($reflection->isPublic()) {
                $this->params = $this->route;
                $this->route = [];
                if ($reflection->getNumberOfRequiredParameters() == count($this->params)) {
                    return $method;
                }
            }
        }

        $this->method_is_fallback = true;
        return false;
    }

    public function resolveActiveContolller(string $controller_path_trail = '', int $route_key = 0)
    {
        $controller_dir = $this->config['APP_PATHS']['CONTROLLERS'] . DIRECTORY_SEPARATOR . $controller_path_trail;

        if (empty($this->route)) {
            return $this->getDefaultController($controller_dir);
        } else {
            $this->class = ucfirst($this->route[$route_key]);
            if ($controller_file = realpath($controller_dir . $this->class . self::PHP_EXT)) {
                unset($this->route[$route_key]);
                return $controller_file;
            } else {
                $controller_path_trail .= $this->route[$route_key] . DIRECTORY_SEPARATOR;
                $this->namespace .= $this->class . "\\";
                unset($this->route[$route_key]);
                return $this->resolveActiveContolller($controller_path_trail, $route_key + 1);
            }
        }
    }

    public function getDefaultController(string $controller_dir = null)
    {
        $this->class = ucfirst($this->config['APP_DEFAULT_CONTROLLER']);

        if ($default_controller = realpath($controller_dir . $this->class . self::PHP_EXT)) {
            $this->controller_is_default = true;
            return $default_controller;
        } else {
            return $this->getFallbackController();
        }
    }

    public function getFallbackController()
    {
        $this->controller_is_fallback = true;
        if (
            $fallback_controller_file = realpath(
                $this->config['APP_PATHS']['CONTROLLERS'] . $this->config['APP_FALLBACK_CONTROLLER'] . self::PHP_EXT
            )
        ) {
            $this->initNamespace();
            $fallback_pathinfo = pathinfo($this->config['APP_FALLBACK_CONTROLLER']);
            $this->namespace .= str_replace('/', "\\", $fallback_pathinfo['dirname']) . "\\";
            $this->class = ucfirst($fallback_pathinfo['basename']);
            return $fallback_controller_file;
        } elseif ($fallback_controller_file = realpath(__DIR__ . DIRECTORY_SEPARATOR . 'Errors' . DIRECTORY_SEPARATOR . 'Error404' . self::PHP_EXT)) {
            $this->namespace = "VirX\\Qaton\\Erorrs\\";
            $this->class = 'Error404';
            return $fallback_controller_file;
        } else {
            throw new Error(
                'File Not Found and No Fallback Handler',
                $this->route,
                1,
                __LINE__,
                __METHOD__,
                __CLASS__,
                __FILE__
            );
        }
    }

    public function setRoute()
    {
        $this->route = explode('/', explode('?', $this->request->path, 2)[0]);
        foreach ($this->route as $key => $part) {
            if ($part == '' || is_null($part)) {
                unset($this->route[$key]);
            }
        }
        $this->route = array_values($this->route);
        $this->requested_route = $this->route;
    }

    public function initNamespace()
    {
        $this->namespace = $this->config['BASE_NAMESPACE_CONTROLLERS'] . '\\';
    }
}
