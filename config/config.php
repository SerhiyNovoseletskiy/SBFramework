<?php
use app\cache\FileCache\Cache;
use app\sitebuilder\Database;
use app\sitebuilder\Request;

return [
    'siteName' => 'SB Framework',
    'language' => 'uk',
    'layoutPath' => __DIR__ . '/../layouts/',
    'viewsPath' => __DIR__ . '/../views/',
    'translatePath' => __DIR__ . '/../translates/',
    'layout' => 'index',
    'route' => require_once(__DIR__ . '/route.php'),

    'assets' => [
        'js' => [
            '/assets/jquery/jquery.min.js',
            '/assets/bootstrap/js/bootstrap.min.js',
            '/assets/syntaxhighlighter/scripts/shCore.js',
            '/assets/syntaxhighlighter/scripts/shBrushPhp.js',
            '/assets/syntaxhighlighter/scripts/shBrushXml.js'
        ],
        'css' => [
            '/assets/bootstrap/css/bootstrap.min.css',
            '/assets/syntaxhighlighter/styles/shCoreDefault.css'
        ]
    ],
    'components' => [
        [
            'alias' => 'request',
            'class' => Request::class
        ],
        [
            'alias' => 'db',
            'class' => Database::class,
            'options' => [
                'host' => 'localhost',
                'user' => 'root',
                'password' => '',
                'database' => 'sitebuilder'
            ]
        ], [
            'alias' => 'cache',
            'class' => Cache::class,
            'options' => [
                'cachepath' => __DIR__ . '/../cache/'
            ]
        ]
    ]
];