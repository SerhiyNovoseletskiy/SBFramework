<?php
return [
    'siteName' => 'SB Framework',
    'language' => 'uk',
    'layoutPath' => __DIR__ . '/../layouts/',
    'viewsPath' => __DIR__ . '/../views/',
    'layout' => 'index',
    'route' => require_once(__DIR__ . '/route.php'),
    'errors' => [
        '404' => '404'
    ],
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
            '/assets/syntaxhighlighter/styles/shCoreFadeToGrey.css'
        ]
    ],
    'components' => [
        [
            'alias' => 'request',
            'class' => 'app\sitebuilder\Request'
        ],
        [
            'alias' => 'db',
            'class' => 'app\sitebuilder\db\mySqlDb',
            'options' => [
                'host' => 'localhost',
                'user' => 'root',
                'password' => '',
                'database' => 'yii'
            ]
        ], [
            'alias' => 't',
            'class' => 'app\sitebuilder\Translate',
            'options' => [
                'translatePath' => __DIR__ . '/../translates'
            ]
        ], [
            'alias' => 'cache',
            'class' => 'app\cache\CacheFile\Cache',
            'options' => [
                'cachepath' => __DIR__ . '/../cache/'
            ]
        ]
    ]
];