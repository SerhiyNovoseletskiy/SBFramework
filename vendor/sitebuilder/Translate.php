<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 23.07.15
 * Time: 1:00
 */

namespace app\sitebuilder;


class Translate
{
    private static $translates = [];

    static function translate($category, $value)
    {
        if (!array_key_exists($category, self::$translates)) {
            if (file_exists(Container::get('translatePath') . '/' . $category . '/' . SiteBuilder::$app->language . '.php')) {
                self::$translates[$category] = require_once(Container::get('translatePath') . '/' . $category . '/' . SiteBuilder::$app->language . '.php');

                if (array_key_exists($value, self::$translates[$category]))
                    return self::$translates[$category][$value];
            }
        }

        return $value;
    }
}