<?php

namespace VirX\Qaton;

use VirX\Qaton\HttpHeaders;

class Response
{
    public $router;
    public $outputMode;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function send(string $outputMode = null)
    {
        $this->outputMode = $outputMode;
        $this->setOutputHeader();
        call_user_func_array(
            array(
                $this->router->controller['instance'], $this->router->method
            ),
            array_values(
                $this->router->params
            )
        );
    }

    public function setOutputHeader()
    {
        if ($this->router->method_is_fallback === true || $this->router->controller_is_fallback === true) {
            HttpHeaders::setByCode(404);
        } else {
            HttpHeaders::setByCode(200);
        }

        header('X-Powered-By: VirX Qaton by Antony Shan Peiris');
        switch ($this->outputMode) {
            case 'json':
                header('Content-Type: application/json');
                break;

            case 'text':
                header('Content-Type: text/plain');
                break;

            default:
                header('Content-Type: text/html');
        }
    }
}
