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
    __debug('mb_substr', 'WARNING: You should install PHP mbstring');
    function mb_substr($string, $start, $length = null, $encoding = null) {
        return substr($string, $start, $length);
    }
}

if (!function_exists('mb_strlen')) {
    __debug('mb_strlen', 'WARNING: You should install PHP mbstring');
    function mb_strlen($string, $encoding = null) {
        return strlen($string);
    }
}

if (!function_exists('mb_strtolower')) {
    __debug('mb_strtolower', 'WARNING: You should install PHP mbstring');
    function mb_strtolower($string, $encoding = null) {
        return strtolower($string);
    }
}
