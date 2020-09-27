<?php

if (!function_exists('__start_debug'))
{
    function __start_debug()
    {
        @session_start();

        $_SESSION['__debug'] = array();

    }
}

if (!function_exists('__debug'))
{
    function __debug($data, $label='')
    {
        @session_start();
        
        if (!isset($_SESSION['__debug']))
        {
            __start_debug();
        }

        $_SESSION['__debug'][] = array(
            'label' => $label,
            'data' => $data
        );

        //var_dump($_SESSION['__debug']);

        return true;
    }
}

if (!function_exists('__print_debug'))
{
    function __print_debug()
    {
        $headers = getallheaders();

        if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json')
        {
            return false;
        }
        
        @session_start();
        
        if (isset($_SESSION['__debug']) && !empty($_SESSION['__debug']))
        {
            echo "\n\n<!-- DEBUG -->\n<script>\n";
            foreach ($_SESSION['__debug'] as $debug)
            {
                if (isset($debug['label']))
                {
                    echo 'console.log("DEBUG :: '.$debug['label'].' :: ");'."\n";
                }
                if (isset($debug['data']))
                {
                    echo 'console.log(JSON.parse(atob("'. base64_encode(json_encode($debug, JSON_OBJECT_AS_ARRAY)) .'")));'."\n";
                }
            }
            echo "</script>\n";
        }
    }
}