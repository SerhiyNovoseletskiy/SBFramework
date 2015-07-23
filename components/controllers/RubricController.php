<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 22.07.15
 * Time: 22:07
 */

namespace app\controllers;


use app\models\Rubric;
use app\sitebuilder\Application;
use app\sitebuilder\Controller;

class RubricController extends Controller {
    function newAction() {
        return $this->render('rubric/new');
    }

    function deleteAction($id) {
        return $id;
    }
}