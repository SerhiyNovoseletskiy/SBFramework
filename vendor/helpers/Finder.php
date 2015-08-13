<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 30.07.15
 * Time: 23:14
 */

namespace app\helpers;


class Finder {
    public static function getPathFromClass($class_name) {

        $map = require __DIR__ . '/../../config/autoload.php';
        $params = require (__DIR__ . '/../../config/params.php');

        $arr = [];
        $class_name = str_replace('\\', '/', $class_name);

        /*
         * Якщо наш клас в папці vendor
         */
        if (preg_match_all('#app/(.*)/(.*)#is', $class_name, $arr, PREG_SET_ORDER)) {
            $file = '';
            $arr = $arr[0];
            $i = 0;

            foreach ($arr as $path) {
                if ($i > 0)
                    $file .= $path . '/';

                $i++;
            }
            return $params['componentsPath'] . substr($file, 0, strlen($file) - 1) . '.php';
        } else {


            $class = explode('/', $class_name)[0];


            if (array_key_exists($class, $map)) {
                $class_name = str_replace($class, $map[$class], $class_name);
                $class_name = $class_name . '.php';

                return $class_name;
            }
        }
    }
}