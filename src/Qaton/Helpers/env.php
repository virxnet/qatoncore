<?php

if (!function_exists('_env')) {
    function _env($key)
    {
        return $_ENV[$key];
    }
}

if (!function_exists('_config')) {
    function _config($key)
    {
        return $_ENV['QATON_CONFIG'][$key];
    }
}
