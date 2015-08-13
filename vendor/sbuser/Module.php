<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 07.08.15
 * Time: 12:23
 */

namespace app\sbuser;


use app\sitebuilder\Component;

class Module implements Component {
    private $user;
    public $redirectTo = '/login';
    public $excluded;

    function init() {
        $this->user = new User();

        if (!$this->user->isAuth() and !in_array($_SERVER['REQUEST_URI'] , $this->excluded)) {
            header("Location: ". $this->redirectTo);
        }
    }
}