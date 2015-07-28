<?php
namespace app\widgets\bootstrap;


class Nav
{
    private static $iterator = 0;

    private static function navbar($navBars)
    {
        $html = '';
        foreach ($navBars as $navBar) {
            $html .= '<ul class="' . $navBar['class'] . '">';

            $html .= self::items($navBar['items']);

            $html .= '</ul>';
        }

        return $html;
    }

    private static function items($items)
    {
        $html = '';
        if (!empty($items))
            foreach ($items as $item) {
                if (!isset($item['items']) && @!is_array($item['items'])) {
                    $html .= '<li>';
                    $html .= '<a href="' . $item['url'] . '">' . $item['label'] . '</a>';
                    $html .= '</li>';
                } else {
                    $html .= '<li class="dropdown">';
                    $html .= '<a class="dropdown-toggle" href="' . $item['url'] . '" data-toggle="dropdown" aria-expanded="false">
                        ' . $item['label'] . ' <b class="caret"></b></a>';

                    $html .= '<ul class="dropdown-menu">';
                    $html .= self::items($item['items']);
                    $html .= '</ul>';
                    $html .= '</li>';
                }
            }
        return $html;
    }

    public static function widget(array $options)
    {
        extract($options);
        $html = '';

        $html .= '
        <div class="navbar ' . $class . '">
        <div class = "container-fluid">
        ';

        $html .= '
        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w' . self::$iterator . '-collapse"><span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button><a class="navbar-brand" href="' . $brandUrl . '">' . $brandLabel . '</a></div>';

        $html .= '<div id="w' . self::$iterator . '-collapse" class="collapse navbar-collapse">';

        if (!empty($navBars))
            $html .= self::navbar($navBars);

        $html .= '</div>';

        $html .= '</div></div>';

        self::$iterator++;
        return $html;
    }
}