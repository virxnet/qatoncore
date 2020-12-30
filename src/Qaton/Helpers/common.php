<?php

if (!function_exists('_getNamespaceByPath')) {
    function _getNamespaceByPath($path)
    {
        $path_arr = explode('/', pathinfo($path)['dirname']);
        if ($path_arr[0] !== '.') {
            $path_arr = array_map('ucfirst', $path_arr);
            return '\\' . implode('\\', $path_arr);
        } else {
            return null;
        }
    }
}

if (!function_exists('mb_substr')) {
    function mb_substr($string, $start, $length = null, $encoding = null) {
        return substr($string, $start, $length);
    }
}

if (!function_exists('mb_strlen')) {
    function mb_strlen($string, $encoding = null) {
        return strlen($string);
    }
}

if (!function_exists('mb_strtolower')) {
    function mb_strtolower($string, $encoding = null) {
        return strtolower($string);
    }
}
