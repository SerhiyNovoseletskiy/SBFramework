<?php

namespace app\helpers;


class Html
{
    public static function encodeHtml($html)
    {
        return htmlspecialchars($html);
    }

    public static function decodeHtml($html)
    {
        return htmlspecialchars_decode($html);
    }
}