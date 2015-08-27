<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 27.08.15
 * Time: 21:58
 */

namespace models;


use app\sitebuilder\Model;

class Category extends Model{
    public $name;

    public $fields = ['name' => []];

    public function tableName()
    {
        return 'e_categories';
    }
}