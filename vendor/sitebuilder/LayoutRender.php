<?php


namespace app\sitebuilder;


class LayoutRender extends Render
{
    private $layout;
    private $html;

    function __construct($view, $data = [], $layout = null)
    {
        parent::__construct($view, $data);

        if ($layout == null) {
            $this->layout = SiteBuilder::$app->layout;
        } else {
            $this->layout = $layout;
        }
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

    function __toString()
    {
        ob_start();
        require_once SiteBuilder::$app->layoutPath . $this->layout . '.php';
        $this->html = ob_get_clean();
        return $this->html;
    }
}