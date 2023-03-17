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

if (!function_exists('compress_page_html')) {
    function compress_page_html($buffer)
    {
        /*
        // TODO: improve and optimize this, using basic tidy as temp solution 
        // TODO: cache support
        if (class_exists('tidy')) {
            // Specify configuration
            $config = array(
                //'clean'          => true,
                'indent-with-tabs' => true,
                'tab-size' => 4,
                'hide-comments'  => true,
                'indent'         => true,
                //'output-html'   => true,
                'wrap'           => 200,
                'output-xhtml'   => false,
                //'show-body-only' => true
            );
            $tidy = new tidy();
            $tidy->parseString($buffer, $config, 'utf8');
            $tidy->cleanRepair();
            return $tidy;
        }
        */
        //return $buffer;

        //$buffer = preg_replace('/<!--(.|\s)*?-->/', '', $buffer);
        //$buffer = preg_replace('/\s+/', " ", $buffer);
        //return str_replace(array("\n", "\t", "\r"), '', $buffer);

        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            //'/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/', // Remove HTML comments,
            //'/\s\s+/' // replace multiple spaces with single 
        );
    
        $replace = array(
            '>',
            '<',
            //'\\1',
            '',
            //' '
        );
    
        return preg_replace($search, $replace, $buffer);
    }
}
