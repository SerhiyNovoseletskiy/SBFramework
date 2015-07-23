<?php

namespace app\sitebuilder;


class Container
{
    public static $container = [];

    public static function add($key, $value)
    {
        self::$container[$key] = $value;
    }

    public static function get($key)
    {
        if (isset(self::$container[$key]))
            return self::$container[$key];

        return null;
    }

}