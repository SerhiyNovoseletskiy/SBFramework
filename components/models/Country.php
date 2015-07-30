<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 28.07.15
 * Time: 22:53
 */

namespace app\models;


use app\sitebuilder\Model;

class Country extends Model
{
    public $id;
    public $name;

    public $fields = [
        'id' => [
            'type' => 'INT'
        ], 'name' => [
            'type' => 'VARCHAR',
            'length' => 100
        ]
    ];

    function tableName()
    {
        return 'country';
    }

}