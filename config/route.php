<?php
use app\sitebuilder\Application;

return
    [
        '^/$' => [
            'callback' => function () {
                $city = new \app\models\City();

                $city->name = 'Київ';

                if ($city->is_valid()) {
                    $city->save();
                }

                var_dump($city->errors);
            }
        ], '^/docs/(?P<view>\w+)' => [
            'callback' => function($view) {
                return new \app\sitebuilder\LayoutRender('docs/' . \app\sitebuilder\Application::$app->language . '/' . $view);
            }
        ]
    ];