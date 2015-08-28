<?php
use app\sitebuilder\Render;

return
    [
        '' => [
            'callback' => function() {
                return new Render('site/index');       
            }
        ]
    ];