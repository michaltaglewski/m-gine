<?php

namespace mgine\helpers;

use ReflectionClass;

class InflectorHelper
{
    public static function camelCase($str, array $noStrip = [])
    {
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);

        $str = trim($str);

        $str = ucwords($str);
        $str = str_replace(" ", "", $str);
        $str = lcfirst($str);

        return $str;
    }

    public static function getClassBaseName(object $object) :string
    {
        return (new \ReflectionClass($object))->getShortName();
    }
}