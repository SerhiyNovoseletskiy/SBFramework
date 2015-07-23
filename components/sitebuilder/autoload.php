<?php
$map = require_once __DIR__ . '/../../config/autoload.php';
$params = require(__DIR__ . '/../../config/params.php');

function __autoload($class_name)
{
    global $params;

    $arr = [];
    $class_name = str_replace('\\', '/', $class_name);

    /*
     * Якщо наш клас в папці components
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
        require_once $params['componentsPath'] . substr($file, 0, strlen($file) - 1) . '.php';
    } else {

    }
}