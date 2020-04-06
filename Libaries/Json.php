<?php
namespace Libaries;

class Json
{
    public static function encode($string)
    {
        header('Content-type: application/json');
        return json_encode($string);
    }
}