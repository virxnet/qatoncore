<?php

if (!function_exists('__start_debug')) {
    function __start_debug(int $level = 2)
    {
        if (
            isset($_ENV['QATON_CONFIG']['APP_DEBUG'])
            && $_ENV['QATON_CONFIG']['APP_DEBUG'] === true
        ) {
            @session_start();

            if (isset($_SESSION['__debug_' . $level])) {
                unset($_SESSION['__debug_' . $level]);
            }
            $_SESSION['__debug_' . $level] = array();
        }
    }
}

if (!function_exists('__clear_debug')) {
    function __clear_debug(int $level)
    {
        if (
            isset($_ENV['QATON_CONFIG']['APP_DEBUG'])
            && $_ENV['QATON_CONFIG']['APP_DEBUG'] === true
        ) {
            @session_start();
            unset($_SESSION['__debug_' . $level]);
        }
    }
}

if (!function_exists('__debug')) {
    function __debug($data, $label = '', $level = 2)
    {
        if (
            isset($_ENV['QATON_CONFIG']['APP_DEBUG'])
            && $_ENV['QATON_CONFIG']['APP_DEBUG'] === true
        ) {
            @session_start();

            if (!isset($_SESSION['__debug_' . $level])) {
                __start_debug($level);
            }

            $_SESSION['__debug_' . $level][] = array(
                'label' => $label,
                'data' => $data
            );

            return true;
        }
    }
}

if (!function_exists('__print_debug')) {
    function __print_debug($level = 2)
    {
        if (
            isset($_ENV['QATON_CONFIG']['APP_DEBUG'])
            && $_ENV['QATON_CONFIG']['APP_DEBUG'] === true
        ) {
            if (function_exists('getallheaders')) {
                $headers = getallheaders();
                @session_start();

                if (isset($_SESSION['__debug_' . $level]) && !empty($_SESSION['__debug_' . $level])) {
                    if (isset($headers['X-Qaton-Debug']) && $headers['X-Qaton-Debug'] == 'false') {
                        // do nothing
                    } elseif (
                        (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json') ||
                        (isset($headers['Accept']) && $headers['Accept'] == 'application/json')
                    ) {
                        //header('Content-type: application/json');
                        echo json_encode($_SESSION['__debug_' . $level], JSON_BIGINT_AS_STRING);
                    } elseif (
                        (isset($headers['Content-Type']) && $headers['Content-Type'] == 'text/plain') ||
                        (isset($headers['Accept']) && $headers['Accept'] == 'text/plain')
                    ) {
                        echo json_encode($_SESSION['__debug_' . $level], JSON_BIGINT_AS_STRING | JSON_PRETTY_PRINT);
                    } else {
                        echo "\n\n<!-- DEBUG -->\n\n<script type='text/javascript'>\n";
                        //echo "function qaton_debug() {";
                        foreach ($_SESSION['__debug_' . $level] as $debug) {
                            if (isset($debug['label'])) {
                                echo 'console.info("QATON DEBUG :: ' . $debug['label'] . ' :: ");' . "\n";
                            }
                            if (isset($debug['data'])) {
                                if (!$out_data = json_encode($debug, JSON_OBJECT_AS_ARRAY)) {
                                    $out_data = json_encode(var_export($debug, true), JSON_PRETTY_PRINT);
                                }
                                echo 'console.log(JSON.parse(atob("' . base64_encode($out_data) . '")));' . "\n";
                            }
                        }
                        //echo "}";
                        echo "</script>\n";
                    }
                    unset($_SESSION['__debug_' . $level]);
                }
            }
        }
    }
}

if (!function_exists('_vd')) {
    function _vd($data, $label = false)
    {
        echo '<pre>';
        if ($label) {
            echo "<h5>VAR_DUMP {$label}:</h5>";
        }
        var_dump($data);
        echo '</pre>';
    }
}

if (!function_exists('_vdc')) {
    function _vdc($data, $label = false)
    {
        if (!$out_data = json_encode($data, JSON_OBJECT_AS_ARRAY)) {
            $out_data = json_encode(@var_export($data, true), JSON_PRETTY_PRINT);
        }
        echo "\n\n<!-- QATON DEBUG -->\n<script>\n";
        if ($label) {
            echo 'console.log("QATON DEBUG :: VAR_DUMP_CONSOLE :: ' . $label . "\");\n";
        } else {
            echo 'console.log("QATON DEBUG :: VAR_DUMP_CONSOLE ");' . "\n";
        }
        echo 'console.log(JSON.parse(atob("' . base64_encode($out_data) . '")));' . "\n";
        echo "</script>\n";
    }
}
