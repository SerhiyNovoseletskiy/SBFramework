<?php

namespace app\sitebuilder\db\mysql;


class DBConnector
{
    private $database;

    public $db = null;


    function __construct($database)
    {
        $this->database = $database;
    }

    public function connect()
    {
        if ($this->db == null) {
            if (!($this->db = mysqli_connect($this->database->host, $this->database->user, $this->database->password, $this->database->database)))
                throw new \Exception(mysqli_error());

            mysqli_set_charset($this->db, $this->database->charset);
            register_shutdown_function([$this, 'disconnect']);
        }
    }

    public function disconnect()
    {
        if ($this->db)
            mysqli_close($this->db);
    }
}