<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 22.07.15
 * Time: 16:22
 */

namespace app\models;


use app\sitebuilder\Model;

class Rubric extends Model{
    public $id;
    public $title;

    protected $fields = [
        'id' => [
            'type' => 'INT'
        ],

        'title' => [
            'type' => 'VARCHAR'
        ]
    ];

    public function tableName() {
        return 'rubricks';
    }

    public function primaryKey() {
        return 'id';
    }
}