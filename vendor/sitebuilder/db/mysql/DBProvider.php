<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 15.08.15
 * Time: 22:14
 */

namespace app\sitebuilder\db\mysql;

use app\sitebuilder\Database;

class DBProvider
{
    /**
     * @var DBConnector
     */
    private $db_connector;

    function __construct(DBConnector $connector)
    {
        $this->db_connector = $connector;
    }

    /**
     * @param string $query
     * @return bool|\mysqli_result
     * @throws \Exception
     */
    function query($query)
    {
        if (!$this->db_connector->db)
            $this->db_connector->connect();

        return mysqli_query($this->db_connector->db, $query);
    }

    /**
     * @param string $query
     * @param int $type
     * @param string|null $class_name
     * @return array
     */
    function getResultQuery($query, $type, $class_name = null)
    {
        $result = [];

        $q = $this->query($query);


        switch ($type) {
            case Database::$RESULT_OBJECT : {
                while ($res = mysqli_fetch_object($q, $class_name)) {
                    array_push($result ,$res);
                }
            }
                break;

            case Database::$RESULT_ARRAY : {
                while ($res = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
                    array_push($result, $res);
                }
            }
                break;
        };

        return $result;
    }

    /**
     * Функція для екранування
     * @param string $value
     * @return string
     */
    function real_escape_string($value) {
        return mysql_real_escape_string($value);
    }

    /**
     * Функція для отримання id останього запису
     * @return int|string
     */
    function insert_id() {
        return mysqli_insert_id($this->db_connector->db);
    }
}