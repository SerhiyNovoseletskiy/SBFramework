<?php
namespace app\sitebuilder;


abstract class Model
{
    private static $self;
    private static $query;

    public $isNew = true;

    /*Список полів у таблиці*/
    protected $fields;

    private $messages = [
        'NOT_NULL' => 'This field is required',
        'MAX_LENGTH' => 'Max length',
        'INT' => 'It`s not numeric',
        'EMAIL' => 'It`s not email',
        'PATTERN' => 'Invalid format'
    ];

    public $errors = [];

    public function __construct($params = null) {
        if ($params !== null) {
            foreach($params as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public abstract function tableName();

    public function primaryKey()
    {
        return 'id';
    }

    /*
        Функція для отримання списку полів які потрібно вибрати з таблиці
    */
    private function getQueryFields($getPrimaryKey = true, $fields = null)
    {
        $queryFields = '';

        if (empty($fields))
            if ($getPrimaryKey)
                foreach ($this->fields as $key => $fields) {
                    $queryFields .= "`{$key}`, ";
                }
            else  foreach ($this->fields as $key => $fields) {
                if ($this->primaryKey() !== $key)
                    $queryFields .= "`{$key}`, ";
            }

        else
            foreach ($fields as $field)
                $queryFields .= "`{$field}`, ";

        return substr($queryFields, 0, strlen($queryFields) - 2);
    }

    private function getInsertValues()
    {
        $insertValues = '';

        foreach ($this->fields as $key => $options) {
            if ($this->primaryKey() !== $key)
                $insertValues .= "'{$this->$key}', ";

        }

        return substr($insertValues, 0, strlen($insertValues) - 2);
    }

    private function getUpdateValues($fields = null)
    {
        $updateValues = '';

        if (empty($fields))
            foreach ($this->fields as $key => $options) {
                $updateValues .= "`{$key}` = '{$this->$key}', ";
            }
        else if (is_array($fields))
            foreach ($fields as $key => $value) {
                $updateValues .= "`{$key}` = '{$value}', ";
            }

        return substr($updateValues, 0, strlen($updateValues) - 2);
    }

    /*Чи є зовнішні ключі*/
    private function hasFK()
    {
        foreach ($this->fields as $key => $options) {
            if ($options['type'] == 'FK') {
                return true;
            }
        }

        return false;
    }


    public function save()
    {
        $pk = $this->primaryKey();

        // Якщо новий запис виконаємо операцію INSERT
        if ($this->isNew) {
            self::$query = 'INSERT INTO `' . $this->tableName() . '` (' . $this->getQueryFields(/*getPrimaryKey = */
                    false) . ') VALUES (' . $this->getInsertValues() . ')';

            SiteBuilder::$app->db->executeQuery(self::$query);

            $this->$pk = mysqli_insert_id(SiteBuilder::$app->db->db);
        } // Якщо не новий
        else {
            self::$query = 'UPDATE `' . $this->tableName() . '` SET ' . $this->getUpdateValues() . ' WHERE `' . $pk . '` = ' . $this->$pk . '';
            echo self::$query;
            SiteBuilder::$app->db->executeQuery(self::$query);
        }

        $this->isNew = false;
    }

    public function delete()
    {
        $pk = $this->primaryKey();

        self::$query = 'DELETE FROM ' . $this->tableName() . ' WHERE `' . $pk . '` = ' . $this->$pk . '';

        SiteBuilder::$app->db->executeQuery(self::$query);
        $this->isNew = true;
        return $this->$pk;
    }

    /*
     * END CRUD
     * */


    public function is_valid()
    {
        $is_valid = true;
        $this->errors = [];

        foreach ($this->fields as $field => $options) {
            switch ($options['type']) {
                case 'INT': {

                    if ($field == $this->primaryKey()) {
                        if (!empty($this->$field)) {

                            if (!is_numeric($this->$field)) {
                                $is_valid = false;
                                array_push($this->errors, ['field' => $field, 'message' => $this->messages['INT']]);
                            }
                        }
                    } else {
                        if (!is_numeric($this->$field)) {
                            $is_valid = false;
                            array_push($this->errors, ['field' => $field, 'message' => $this->messages['INT']]);
                        }
                    }
                }
                    break;

                case 'VARCHAR': {
                    !empty($options['length']) ? $max_length = $options['length'] : $max_length = 0;

                    if (strlen($this->$field) > $max_length and $max_length !== 0) {
                        $is_valid = false;
                        array_push($this->errors, ['field' => $field, 'message' => $this->messages['MAX_LENGTH']]);
                    }
                } break;

            }


            if ($options['NOT NULL']) {
                if (empty($this->$field)) {
                    $is_valid = false;
                    array_push($this->errors, ['field' => $field, 'message' => $this->messages['NOT_NULL']]);
                }
            }
        }

        return $is_valid;
    }

    /*
        STATIC METHODS
    */


    // Початкові налаштування
    private static function init()
    {
        /*
         * Створюю екзмемляр даного класу щоб отримати доступ
         * до його методів і полів
         */
        self::$self = get_called_class();
        self::$self = new self::$self;
    }

    public static function getByPK($pk)
    {
        self::init();

        self::$query = 'SELECT ' . self::$self->getQueryFields() . 'FROM `' . self::$self->tableName() . '` WHERE `' . self::$self->primaryKey() . '` = ' . $pk . '';

        $queryResult = SiteBuilder::$app->db->executeQuery(self::$query);


        $result = mysqli_fetch_object($queryResult, get_called_class());

        $result->isNew = false;

        // Якщо є зовнішні ключі
        if (self::$self->hasFK()) {
            foreach (self::$self->fields as $key => $options) {
                if ($options['type'] == 'FK') {
                    $fk = $result->$key;

                    !empty($options['field']) ? $field = $options['field'] : $field = $key;

                    $result->$field = call_user_func_array($options['class'] . '::getByPK', [$fk]);
                }
            }
        }

        return $result;
    }

    public static function getAll($options = [])
    {
        self::init();
        extract($options);

        // Our query
        self::$query = 'SELECT ' . self::$self->getQueryFields(true, $fields) . ' FROM `' . self::$self->tableName() . '`';

        $result = [];

        if (!empty($orderBy)) {
            self::$query .= ' ORDER BY ' . $orderBy;

            if (!empty($orderType)) {
                self::$query .= ' ' . $orderType;
            }
        }

        if (!empty($limit)) {
            self::$query .= ' LIMIT ' . $limit;

            if (!empty($offset)) {
                self::$query .= ', ' . $offset;
            }
        }

        $res = SiteBuilder::$app->db->executeQuery(self::$query);

        while ($r = mysqli_fetch_object($res, get_called_class())) {

            // Якщо є зовнішні ключі
            if ($r->hasFK()) {
                foreach ($r->fields as $key => $options) {
                    if ($options['type'] == 'FK') {
                        $fk = $r->$key;

                        !empty($options['field']) ? $field = $options['field'] : $field = $key;

                        if (!empty($fk))
                            $r->$field = call_user_func_array($options['class'] . '::getByPK', [$fk]);
                    }
                }
            }

            array_push($result, $r);
        }

        return $result;
    }

    public static function where($params = null, $options = [])
    {
        self::init();

        extract($options);

        if (!empty($params)) {

            self::$query = 'SELECT ' . self::$self->getQueryFields(true, $fields) . ' FROM `' . self::$self->tableName() . '` WHERE ';


            foreach ($params as $key => $param) {
                self::$query .= "`{$key}` = '{$param}' and ";
            }

            self::$query = substr(self::$query, 0, strlen(self::$query) - 4);

            if (!empty($orderBy)) {
                self::$query .= ' ORDER BY ' . $orderBy;

                if (!empty($orderType)) {
                    self::$query .= ' ' . $orderType;
                }
            }

            if (!empty($limit)) {
                self::$query .= ' LIMIT ' . $limit;

                if (!empty($offset)) {
                    self::$query .= ', ' . $offset;
                }
            }

            $result = [];

            $res = SiteBuilder::$app->db->executeQuery(self::$query);

            while ($r = mysqli_fetch_object($res, get_called_class())) {

                // Якщо є зовнішні ключі
                if ($r->hasFK()) {
                    foreach ($r->fields as $key => $options) {
                        if ($options['type'] == 'FK') {
                            $fk = $r->$key;

                            !empty($options['field']) ? $field = $options['field'] : $field = $key;

                            if (!empty($fk))
                                $r->$field = call_user_func_array($options['class'] . '::getByPK', [$fk]);
                        }
                    }
                }

                array_push($result, $r);
            }

            return $result;
        }

        return false;
    }

    public static function select($query)
    {
        self::init();

        if (!empty($query)) {

            self::$query = $query;


            $result = [];

            $res = SiteBuilder::$app->db->executeQuery(self::$query);

            while ($r = mysqli_fetch_object($res, get_called_class())) {

                // Якщо є зовнішні ключі
                if ($r->hasFK()) {
                    foreach ($r->fields as $key => $options) {
                        if ($options['type'] == 'FK') {
                            $fk = $r->$key;

                            !empty($options['field']) ? $field = $options['field'] : $field = $key;

                            if (!empty($fk))
                                $r->$field = call_user_func_array($options['class'] . '::getByPK', [$fk]);
                        }
                    }
                }

                array_push($result, $r);
            }

            return $result;
        }

        return false;
    }

    public static function queryWhere($where, $options = [])
    {
        self::init();
        extract($options);

        // Our query
        self::$query = 'SELECT ' . self::$self->getQueryFields(true, $fields) . ' FROM `' . self::$self->tableName() . '` WHERE ' . $where;

        return self::query(self::$query);
    }

    public static function updateWhere($values, $params)
    {
        self::init();

        self::$query = 'UPDATE `' . self::$self->tableName() . '` SET ' . self::$self->getUpdateValues($values);
        self::$query .= ' WHERE ';

        foreach ($params as $key => $param) {
            self::$query .= "`{$key}` = '{$param}' and ";
        }

        self::$query = substr(self::$query, 0, strlen(self::$query) - 4);

        return SiteBuilder::$app->db->executeQuery(self::$query);
    }

    public static function deleteWhere($params)
    {
        self::init();

        self::$query = 'DELETE FROM `' . self::$self->tableName() . '` WHERE ';

        foreach ($params as $key => $param) {
            self::$query .= "`{$key}` = '{$param}' and ";
        }

        self::$query = substr(self::$query, 0, strlen(self::$query) - 4);

        return SiteBuilder::$app->db->executeQuery(self::$query);
    }
}