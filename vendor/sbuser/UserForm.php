<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 07.08.15
 * Time: 12:44
 */

namespace app\sbuser;


use app\sitebuilder\ModelForm;

class UserForm extends ModelForm{
    public $model = User::class;
}