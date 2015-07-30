<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 28.07.15
 * Time: 23:12
 */

namespace app\models;


use app\sitebuilder\Model;

class Owner extends Model{
    public $id;
    public $fio;
    public $email;
    public $telephone;

    public $fields = [
      'id' => [
          'type' => 'INT'
      ],

        'fio' => [
            'type' => 'VARCHAR',
            'length' => 100,
            'NOT NULL' => true
        ],

        'email' => [
            'type' => 'VARCHAR',
            'length' => 150,
            'NOT NULL'
        ],

        'telephone' => [
            'type' => 'VARCHAR',
            'length' => 20
        ]
    ];

    function tableName() {
        return 'owner';
    }
}