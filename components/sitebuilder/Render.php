<?php
namespace app\sitebuilder;

use app\sitebuilder\Container;

class Render
{
    protected $content;
    protected $title;

    public function __construct($view, $data = [])
    {
        // Витягуємо вмістиме масиву в таблицю змінних
        extract($data);


        ob_start();
        require_once Container::get('viewsPath') . $view . '.php';
        $this->content = ob_get_clean();
    }

    public function __toString()
    {
        return $this->content;
    }
}