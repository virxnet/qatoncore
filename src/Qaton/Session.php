<?php

namespace VirX\Qaton;

final class Session
{
    public static function set($key, $value)
    {
        @session_start();
        $_SESSION[$key] = $value;
        return true;
    }

    public static function get($key)
    {
        @session_start();
        return @$_SESSION[$key];
    }

    public static function unset($key)
    {
        @session_start();
        unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        @session_destroy();
    }
}
