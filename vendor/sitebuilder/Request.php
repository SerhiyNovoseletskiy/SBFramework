<?php

namespace app\sitebuilder;


class Request implements Component
{
    public $method;
    public $host;
    public $post;
    public $get;

    public function init()
    {
        $this->post = new RequestContainer();
        $this->get = new RequestContainer();

        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->host = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

        $this->post->container = $_POST;
        $this->get->container = $_GET;

        $request_uri = explode('?', $_SERVER['REQUEST_URI']);
        $_SERVER['REQUEST_URI'] = array_shift($request_uri);

    }
}