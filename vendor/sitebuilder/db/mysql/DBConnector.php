<?php

namespace app\sitebuilder\db\mysql;


class DBConnector {
    private $host;
    private $user;
    private $password;
    private $database;
    private $charset = 'utf8';

    public $db = null;


    function __construct($host, $user, $password, $database, $charset) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;
    }

    public function connect() {
        if ($this->db == null) {
            if (!($this->db = mysqli_connect($this->host, $this->user, $this->password, $this->database)))
                throw new \Exception(mysqli_error());

            mysqli_set_charset($this->db, $this->charset);
            register_shutdown_function([$this, 'disconnect']);
        }
    }

    public function disconnect() {
        if ($this->db)
            mysqli_close($this->db);
    }
}