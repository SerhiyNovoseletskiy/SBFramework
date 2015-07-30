<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 28.07.15
 * Time: 23:17
 */

namespace app\models;


use app\sitebuilder\Model;

class Container extends Model{
    public $id;
    public $name;
    public $owner;
    public $city;

    public $fields = [
        'id' => [
            'type' => 'INT'
        ], 'name' => [
            'type' => 'VARCHAR'
        ], 'owner' => [
            'type' => 'FK',
            'class' => 'app\models\Owner'
        ], 'city' => [
            'type' => 'FK',
            'class' => 'app\models\City'
        ]
    ];

    public function tableName() {
        return 'countainer';
    }
}