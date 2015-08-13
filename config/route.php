<?php
use app\sitebuilder\Application;

return
    [
        '^/admin' => [
            'module' => modules\Admin\AdminModule::class,
            'middleware' => [
                app\sbuser\Module::class => [
                    'redirectTo' => '/admin/login',
                    'excluded' => [
                        '/admin/login',
                        '/admin/sign_in'
                    ]
                ]
            ]
        ]
    ];