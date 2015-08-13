<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 22.07.15
 * Time: 15:30
 */

namespace app\sitebuilder;


class RequestContainer
{
    private $container = [];

    function __set($name, $array)
    {
        foreach ($array as $key => $value) {
            $this->container[$key] = $value;
        }
    }

    function __get($name)
    {
        if (array_key_exists($name, $this->container))
            return $this->container[$name];

        return null;
    }
}