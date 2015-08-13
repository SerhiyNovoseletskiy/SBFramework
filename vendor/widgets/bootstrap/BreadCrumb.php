<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 23.07.15
 * Time: 0:42
 */

namespace app\widgets\bootstrap;


class BreadCrumb {
    public static function widget(array $items) {
        if (is_array($items)) {
            $html = '<ul class="breadcrumb">';

            foreach ($items as $item) {
                if ($_SERVER['REQUEST_URI'] !== $item['url']) {
                    $html .= '<li><a href="'.$item['url'].'">'.$item['label'].'</a></li>';
                } else {
                    $html .= '<li>'.$item['label'].'</li>';
                }
            }


            $html .= '</ul>';

            return $html;
        }

        return;
    }
}