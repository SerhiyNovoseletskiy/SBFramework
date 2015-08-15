<?php

return
    [
        '^/(.*)' => [
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