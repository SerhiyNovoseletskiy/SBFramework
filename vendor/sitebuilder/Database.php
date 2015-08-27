<?php

namespace app\sitebuilder;

use app\sitebuilder\db\mysql\DBConnector;
use app\sitebuilder\db\mysql\DBProvider;

/**
 * Class Database
 * @package app\sitebuilder
 */
class Database implements Component {
    public static $RESULT_ARRAY = 1;
    public static $RESULT_OBJECT = 2;

    public $host;
    public $user;
    public $password;
    public $database;
    public $charset = 'utf8';
    public $dbConnector = DBConnector::class;
    public $dbProvider = DBProvider::class;

    public function init()
    {
        $this->dbConnector = new $this->dbConnector($this);
        $this->dbProvider = new $this->dbProvider($this->dbConnector);
    }

    public function query($query) {
        return $this->dbProvider->query($query);
    }

    public function getResultQuery($query, $type, $class_name) {
        return $this->dbProvider->getResultQuery($query, $type, $class_name);
    }

    public function real_escape_string($value) {
        return $this->dbProvider->real_escape_string($value);
    }

    public function insert_id() {
        return $this->dbProvider->insert_id();
    }
}