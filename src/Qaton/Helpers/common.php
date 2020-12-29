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
