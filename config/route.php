<?php

return
    [
        '^/$' => [
            'callback' => function() {
                $cache = \app\sitebuilder\SiteBuilder::$app->cache;

                $cats = $cache->get('cats');

                if (!$cats) {
                    $cats = \models\Category::getAll();
                    $cache->set('cats', $cats, 10);
                    return 'Insert into cache';
                } else {
                    $cache->remove('cats');
                }
            }
        ]
    ];