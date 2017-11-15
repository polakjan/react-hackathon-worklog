<?php

class request
{
    public static function get($key, $default = null)
    {
        if(array_key_exists($key, $_REQUEST))
        {
            return $_REQUEST[$key];
        }
        return $default;
    }
}