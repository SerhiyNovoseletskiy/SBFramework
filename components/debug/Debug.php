<?php

namespace app\debug;


use app\sitebuilder\Render;

class Debug
{
    private $timeBegin;

    function __construct()
    {
        $this->timeBegin = microtime();
    }

    function __destruct()
    {
        echo new Render('debug', [
            'timeBegin' => $this->timeBegin,
            'timeEnd' => microtime(),
        ]);
    }
}