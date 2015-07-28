<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 23.07.15
 * Time: 16:22
 */

namespace app\sitebuilder\exceptions;


class Exception extends \Exception{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Exception';
    }
}