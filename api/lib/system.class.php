<?php

class system
{
    public static function getPageUri()
    {
        return strtolower(trim(substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME']))), ' /'));
    }

    public static function reslash($s)
    {
        return preg_replace('#[\\\/]+#ims', DIRECTORY_SEPARATOR, $s);
    }

    public static function camelize($string)
    {
      return lcfirst(join('', array_map('ucfirst', preg_split('#(\/|_)#', $string))));
    }

    public static function sendJsonHeaders()
    {
        header("Access-Control-Allow-Origin: *");
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');
    }
}