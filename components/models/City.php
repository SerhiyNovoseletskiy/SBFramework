<?php


namespace app\models;

use app\sitebuilder\Model;

class City extends Model
{
    public $id;
    public $name;
    public $country;

    public $fields = [
        'id' => [
            'type' => 'INT'
        ], 'name' => [
            'type' => 'VARCHAR',
            'length' => 100,
            'NOT NULL' => true
        ], 'country' => [
            'type' => 'FK',
            'class' => 'app\models\Country',
            'NOT NULL' => true
        ]
    ];

    function tableName()
    {
        return 'city';
    }

}