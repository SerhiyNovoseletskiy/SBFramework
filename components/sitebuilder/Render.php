<?php
namespace app\sitebuilder;

use app\helpers\VarDumper;
use app\sitebuilder\Container;
use app\sitebuilder\exceptions\Exception;
use app\sitebuilder\exceptions\NotFoundHttpException;

class Render
{
    protected $content;
    protected $title;

    public function __construct($view, $data = [])
    {
        if (file_exists(Application::$app->viewsPath . $view . '.php')) {
            // Витягуємо вмістиме масиву в таблицю змінних
            extract($data);

            ob_start();
            require_once Application::$app->viewsPath . $view . '.php';
            $this->content = ob_get_clean();
        } else
            throw new NotFoundHttpException('View ' . $view .  ' not found. Path to views '.Application::$app->viewsPath.'');
    }

    public function __toString()
    {
        return $this->content;
    }
}