<?php
namespace app\sitebuilder;


abstract class Model
{
    private static $self;
    private static $query;

    public $isNew = true;

    /*Список полів у таблиці*/
    protected $fields;

    public abstract function tableName();

    public abstract function primaryKey();

    /*
        Функція для отримання списку полів які потрібно вибрати з таблиці
    */
    private function getQueryFields($getPrimaryKey = true)
    {
        $queryFields = '';

        if ($getPrimaryKey)
            foreach ($this->fields as $key => $fields) {
                $queryFields .= "`{$key}`, ";
            }
        else  foreach ($this->fields as $key => $fields) {
            if ($this->primaryKey() !== $key)
                $queryFields .= "`{$key}`, ";
        }

        return substr($queryFields, 0, strlen($queryFields) - 2);
    }

    private function getInsertValues() {
        $insertValues = '';

        foreach ($this->fields as $key => $options) {
            if ($this->primaryKey() !== $key)
                $insertValues .= "'{$this->$key}', ";

        }

        return substr($insertValues, 0, strlen($insertValues) - 2);
    }

    private function getUpdateValues()
    {
        $updateValues = '';

        foreach ($this->fields as $key => $options) {
            $updateValues .= "`{$key}` = '{$this->$key}', ";
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
                    false) . ') VALUES ('.$this->getInsertValues().')';

            Application::$app->db->executeQuery(self::$query);

            $this->$pk = mysqli_insert_id(Application::$app->db->db);
        } // Якщо не новий
        else {
            self::$query = 'UPDATE `' . $this->tableName() . '` SET ' . $this->getUpdateValues() . ' WHERE `' . $pk . '` = ' . $this->$pk . '';
            echo self::$query;
            Application::$app->db->executeQuery(self::$query);
        }
    }

    public function delete()
    {
        $pk = $this->primaryKey();

        self::$query = 'DELETE FROM ' . $this->tableName() . ' WHERE `' . $pk . '` = ' . $this->$pk . '';

        Application::$app->db->executeQuery(self::$query);
        $this->isNew = true;
        return $this->$pk;
    }

    /*
     * END CRUD
     * */


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

        $queryResult = Application::$app->db->executeQuery(self::$query);

        if (!self::$self->hasFK())
            $result = mysqli_fetch_object($queryResult, get_called_class());

        $result->isNew = false;

        return $result;
    }

    public static function getAll()
    {
        self::init();

        // Our query
        $query = 'SELECT ' . self::$self->getQueryFields() . ' FROM `' . self::$self->tableName() . '`';

        return $query;
    }
}