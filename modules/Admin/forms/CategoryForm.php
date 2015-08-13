<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 30.07.15
 * Time: 23:44
 */

namespace modules\Admin\forms;


use app\sitebuilder\ModelForm;
use modules\Admin\models\Category;

class CategoryForm extends ModelForm{
    public $model = Category::class;
}