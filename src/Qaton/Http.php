<?php

namespace VirX\Qaton;

class Http
{
    public function getPost()
    {
        return filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    }

    public function getQuery()
    {
        return filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    }

    public static function get(String $key)
    {
        return Http::_input_filter($key, $_GET);
    }

    public static function post(String $key)
    {
        return Http::_input_filter($key, $_POST);
    }

    public static function server(String $key)
    {
        return $_SERVER[$key];
    }

    public static function _input_filter(String $key, Array &$inputArray)
    {
        if ($key)
        {
            if (isset($inputArray[$key]))
            {
                return filter_var($inputArray[$key], FILTER_SANITIZE_STRING);
            }
        }

        return null;
    }
}