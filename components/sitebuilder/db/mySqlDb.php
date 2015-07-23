<?php
namespace app\sitebuilder\db;


use app\sitebuilder\Component;

class mySqlDb implements Component
{
    public $host;
    public $user;
    public $password;
    public $database;
    public $charset = 'utf8';


    public $db;

    function init()
    {
        $this->db = null;
    }

    function executeQuery($query)
    {
        if ($this->db == null) {
            $this->db = mysqli_connect($this->host, $this->user, $this->password, $this->database) or die(mysqli_error($this->db));
            mysqli_set_charset($this->db, $this->charset);
        }

        return mysqli_query($this->db, $query);
    }

    function getAll($query)
    {
        return mysqli_fetch_all($this->executeQuery($query), MYSQLI_ASSOC);
    }

    function getOne($query)
    {
        return mysqli_fetch_row($this->executeQuery($query));
    }

    function __destruct()
    {
        if (!empty($this->db))
            mysqli_close($this->db);
    }
}