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

if (!function_exists('getUserAgent')) {
    function getUserAgent() 
    {
        if ($agent = @get_browser(null, true)) 
        {
            return $agent;
        } elseif ($_SERVER['HTTP_USER_AGENT']) {
            $agent_parts = explode(' ', $_SERVER['HTTP_USER_AGENT']);
            return [
                'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
                'parent' => end($agent_parts)
            ];
        } else {
            return false;
        }
    }
}

if (!function_exists('getIP')) {
    function getIP() 
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // from proxy?
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        return $ip_address;
    }
}