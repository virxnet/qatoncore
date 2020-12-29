<?php

namespace VirX\Qaton;

class Error
{
    public function __construct(
        string $message = null,
        array $data = null,
        int $code = null,
        int $line = null,
        string $method = null,
        string $class = null,
        string $file = null
    ) {
        @set_exception_handler(array($this, 'exceptionHandler'));

        switch ($code) {
            case 1000:
                $message = "Application Configuration Error ({$message})";
                break;
            case 1001:
                $message = "Invalid Path {$message} ";
                break;
            case 1002:
                $message = "Vital Resource Not Found ({$message}) ";
                break;
            case 1003:
                $message = "FileDatabase Fatal Error ({$message}) ";
                break;
            default:
                $message = "Error ({$message}) ";
        }

        if (is_array($data)) {
            $message .= " \n " . var_export($data, true) . " \n ";
        }

        // TODO: Clean this up later and make it nice

        $parts = '';
        if (!is_null($line)) {
            $parts .= "-LINE::[{$line}]\n";
        }
        if (!is_null($method)) {
            $parts .= "-METHOD::[{$method}]\n";
        }
        if (!is_null($class)) {
            $parts .= "-CLASS::[{$class}]\n";
        }
        if (!is_null($file)) {
            $parts .= "-FILE::[{$file}]\n";
        }
        $message .= "\nERROR CODE::[{$code}]:\n{$parts}\n";

        if (isset($_ENV['QATON_CONFIG']['APP_OUTPUT_MODE'])) {
            switch ($_ENV['QATON_CONFIG']['APP_OUTPUT_MODE']) {
                case 'cli':
                case 'text':
                    throw new \Exception($message);
                    break;
                case 'html':
                    $message = str_replace("\n", '<br>', "<div style='border: 2px solid red; padding: 10px;'><h1 style='color: red'>ERROR</h1><pre>{$message}</pre></div>");
                    throw new \Exception($message);
                    break;
                case 'json':
                    throw new \Exception(json_encode(['error' => $message]));
                    break;
                default:
                    throw new \Exception($message);
            }
        } else {
            throw new \Exception($message);
        }
    }

    public function exceptionHandler($exception)
    {
        print " \n\n" . $exception->getMessage() . "\n\n ";
    }
}
