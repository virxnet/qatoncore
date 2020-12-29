<?php

namespace VirX\Qaton;

class Http
{
    public static function getPost()
    {
        // TODO: implement/evaluate filtering
        return filter_input_array(INPUT_POST, FILTER_DEFAULT);
    }

    public static function getHeaders()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        } else {
            return false;
        }
    }

    public static function getRequest()
    {
        return $_REQUEST;
    }

    public static function getFiles()
    {
        return $_FILES;
    }

    public static function getQuery()
    {
        return filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    }

    public static function get(string $key)
    {
        return Http::inputFilter($key, $_GET);
    }

    public static function post(string $key)
    {
        return Http::inputFilter($key, $_POST);
    }

    public static function server(string $key)
    {
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }
    }

    public static function inputFilter(string $key, array &$inputArray)
    {
        if ($key) {
            if (isset($inputArray[$key])) {
                return $inputArray[$key];
                //return filter_var($inputArray[$key], FILTER_SANITIZE_STRING);
            }
        }

        return null;
    }

    public static function getMethod()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return $_SERVER['REQUEST_METHOD'];
        } else {
            return false;
        }
    }
}