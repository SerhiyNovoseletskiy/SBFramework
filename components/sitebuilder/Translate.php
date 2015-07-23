<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 23.07.15
 * Time: 1:00
 */

namespace app\sitebuilder;


class Translate implements Component{
    public $translatePath;

    function init() {

    }

    function translate($category, $value) {
        if (file_exists($this->translatePath . '/'. $category . '/' . Application::$app->language. '.php')) {
            $translate = require_once($this->translatePath . '/'. $category . '/' . Application::$app->language. '.php');

            if (array_key_exists($value, $translate))
                return $translate[$value];
        }

        return $value;
    }
}