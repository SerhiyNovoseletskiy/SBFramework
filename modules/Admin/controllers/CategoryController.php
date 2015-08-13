<?php

namespace modules\Admin\controllers;


use app\sitebuilder\ModuleController;
use modules\Admin\forms\CategoryForm;
use modules\Admin\models\Category;

class CategoryController extends ModuleController{
    public function indexAction() {
        return $this->render('categories/list', [
            'categories' => Category::getAll()
        ]);
    }


    public function editAction($id) {
        $form = new CategoryForm(null, Category::getByPK($id));

        return $this->render('categories/form', [
            'form' => $form
        ]);
    }

    public function updateAction($id) {
        $form = new CategoryForm($_POST, Category::getByPK($id));

        if ($form->is_valid()) {
            $form->save();
            $this->redirect('/admin/category/'. $form->instance->id);
        } else {
            return $this->render('categories/form', [
                'form' => $form
            ]);
        }
    }

    public function addAction() {
        $form = new CategoryForm();
        return $this->render('categories/form', [
            'form' => $form
        ]);
    }

    public function createAction() {
        $form = new CategoryForm($_POST);

        if ($form->is_valid()) {
            $form->save();
            $this->redirect('/admin/category/'. $form->instance->id);
        } else {
            return $this->render('categories/form', [
                'form' => $form
            ]);
        }
    }

    public function deleteAction($id) {
        Category::deleteWhere(['id' => $id]);
        $this->redirect('/admin/categories');
    }
}