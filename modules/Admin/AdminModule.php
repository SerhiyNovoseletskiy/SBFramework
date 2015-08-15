<?php


namespace modules\Admin;


use app\sitebuilder\Module;

class AdminModule extends Module
{
    function route()
    {
        return [
            '^categories$' => [
                'controller' => 'modules\Admin\controllers\CategoryController'
            ],

            '^category/(?P<id>\d+)$' => [
                'GET' => [
                    'controller' => 'modules\Admin\controllers\CategoryController',
                    'action' => 'edit'
                ],
                'POST' => [
                    'controller' => 'modules\Admin\controllers\CategoryController',
                    'action' => 'update'
                ]
            ],

            '^category/(?P<action>\w+)$' => [
                'GET' => [
                    'controller' => 'modules\Admin\controllers\CategoryController',
                    'action' => 'add'
                ],
                'POST' => [
                    'controller' => 'modules\Admin\controllers\CategoryController',
                    'action' => 'create'
                ]
            ],

            '^category/(?P<id>\d+)/(?P<action>\w+)' => [
                'controller' => 'modules\Admin\controllers\CategoryController',
            ],

            '^login$' => [
                'controller' => 'modules\Admin\controllers\LoginController',
                'action' => 'login'
            ],

            '^sign_in$' => [
                'controller' => 'modules\Admin\controllers\LoginController',
                'action' => 'sign_in'
            ]
        ];
    }
}