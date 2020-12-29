<?php

namespace VirX\Qaton;

use VirX\Qaton\Error;

class HttpHeaders
{
    public static function redirect($url)
    {
        self::setByCode(302, $url);
    }

    public static function set(string $header, bool $replace = true, int $code = null)
    {
        if (is_null($code)) {
            header($header, $replace);
        } else {
            header($header, $replace, $code);
        }
        throw new Error("Header Redirection Failed", null, 0, __LINE__, __METHOD__, __CLASS__, __FILE__);
    }

    public static function setByCode(int $code, string $value = null)
    {
        switch ($code) {
            // Bad Request
            case 400:
                header("HTTP/1.0 400 Bad Request");
                break;

            // Bad Request
            case 401:
                header("HTTP/1.0 401 Unauthorized");
                break;

            // Forbidden
            case 401:
                header("HTTP/1.0 403 Forbidden");
                break;

            // Not Found
            case 404:
                header("HTTP/1.0 404 Not Found");
                break;

            // 301 Moved Permanently
            case 301:
                header("Location: {$value}", true, 301);
                break;

            // 302 Found
            case 302:
                header("Location: {$value}", true, 302);
                break;

            // 303 See Other
            case 303:
                header("Location: {$value}", true, 303);
                break;

            // 307 Temporary Redirect
            case 307:
                header("Location: {$value}", true, 307);
                break;

            // 308 TPermanent Redirect
            case 308:
                header("Location: {$value}", true, 308);
                break;

            // Internal Server Error
            case 500:
                header("HTTP/1.0 500 Internal Server Error");
                break;

            // Service Unavailable
            case 503:
                header("HTTP/1.0 503 Service Unavailable");
                break;

            // Gateway Timeout
            case 504:
                header("HTTP/1.0 504 Service Unavailable");
                break;

            case 200:
            default:
                header("HTTP/1.0 200 OK");
        }

        // Failsafe
        switch ($code) {
            case 301:
            case 302:
            case 303:
            case 307:
            case 308:
                throw new Error("Header Redirection Failed", null, 0, __LINE__, __METHOD__, __CLASS__, __FILE__);
        }
    }
}
