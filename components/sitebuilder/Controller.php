<?php

namespace app\sitebuilder;


abstract class Controller
{
    protected $layout = 'index';


    protected function render($view, $data = [])
    {
        return new LayoutRender($view, $data, $this->layout);
    }

    protected function ajaxRender($view, $data = []) {
        return new Render($view, $data);
    }

    protected function redirect($url) {
        header("Location: {$url}");
    }
}