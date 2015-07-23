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
        Application::$app->cache->setCache('rubric');
        $page = Application::$app->cache->get('new_page');

        if (!$page) {
            $page = $this->render('rubric/new');
            Application::$app->cache->set('new_page', $page);
        }

        return $page;
    }

    function deleteAction($id) {
        return $id;
    }
}