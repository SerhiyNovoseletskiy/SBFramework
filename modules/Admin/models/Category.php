<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 30.07.15
 * Time: 23:37
 */

namespace modules\Admin\models;


use app\sitebuilder\Model;

class Category extends Model
{
    public $id;
    public $name;
    public $alias;

    public $fields = [
        'id' => [
            'type' => 'INT'
        ],
        'name' => [
            'type' => 'VARCHAR',
            'length' => 70,
            'required' => true,
            'label' => 'Категорія'
        ],
        'alias' => [
            'type' => 'VARCHAR',
            'length' => 70,
            'required' => true,
            'label' => 'Аліас'
        ]
    ];

    function tableName()
    {
        return 'e_categories';
    }
}