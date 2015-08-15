<?php

namespace app\sitebuilder;


use app\helpers\Finder;
use app\sitebuilder\exceptions\NotFoundHttpException;

class ModuleController {
    protected $layout = 'index';
    protected $pathToView;
    protected $pathToLayouts;

    function __construct() {
        if (empty($this->pathToView)) {
            $this->pathToView = dirname(Finder::getPathFromClass(get_called_class()));
            $this->pathToView = realpath($this->pathToView . '/../views/') . '/';
        }

        if (empty($this->pathToLayouts)) {
            $this->pathToLayouts = dirname(Finder::getPathFromClass(get_called_class()));
            $this->pathToLayouts = realpath($this->pathToLayouts . '/../layouts/') . '/';
        }
    }

    protected function render($view, $data = [])
    {
        if (file_exists($this->pathToView . $view . '.php')) {
            // Витягуємо вмістиме масиву в таблицю змінних
            extract($data);

            ob_start();
            require_once $this->pathToView . $view . '.php';
            $this->content = ob_get_clean();

            ob_start();
            require_once $this->pathToLayouts . $this->layout . '.php';
            $this->html = ob_get_clean();
            return $this->html;
        } else
            throw new NotFoundHttpException('View "' . $view .  '" not found. Path to views "'.SiteBuilder::$app->viewsPath.'"');
    }

    private function getCssFiles()
    {
        $css = SiteBuilder::$app->assets;
        $css = $css['css'];

        foreach ($css as $sc) {
            echo '<link rel="stylesheet" href="' . $sc . '" />';
        }
    }

    private function getJsFiles()
    {
        $js = SiteBuilder::$app->assets;
        $js = $js['js'];

        foreach ($js as $j) {
            echo '<script src="' . $j . '"></script>';
        }
    }

    protected function ajaxRender($view, $data = []) {
        return new Render($view, $data);
    }

    protected function redirect($url) {
        header("Location: {$url}");
    }
}