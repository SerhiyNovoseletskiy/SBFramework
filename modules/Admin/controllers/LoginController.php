<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 07.08.15
 * Time: 12:40
 */

namespace modules\Admin\controllers;


use app\sbuser\User;
use app\sbuser\UserForm;
use app\sitebuilder\ModuleController;
use app\sitebuilder\SiteBuilder;

class LoginController extends ModuleController{
    function loginAction() {
        return $this->render('login/login', ['form' => new UserForm()]);
    }

    function sign_inAction() {
        $user = new User($_POST);
        $user->sign_in();

        if ($user->isAuth()) {
            $this->redirect(SiteBuilder::$app->request->get->referrer);
        } else {
            $user->password = null;
            return $this->render('login/login', ['form' => new UserForm(null, $user)]);
        }
    }
}