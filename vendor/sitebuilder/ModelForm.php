<?php

namespace app\sitebuilder;


abstract class ModelForm
{
    public $model = null;
    public $instance = null;
    public $errors = [];

    /*
     * @var $params - Array
     * @var $instance - Object
     * @return void
     * */
    function __construct($params = null, $instance = null)
    {
        if (!$this->model) {
            throw new \Exception('You don`t set model for ModelForm "' . get_called_class() . '"');
        }

        if (!class_exists($this->model)) {
            throw new \Exception('Class "' . $this->model . '" don`t exists.');
        }

        if (!$instance)
            $this->instance = new $this->model($params);
        else
            $this->instance = $instance;


        if ($params)
            $this->setParams($params);
    }

    private function setParams($params)
    {
        foreach ($params as $key => $value)
            $this->instance->$key = $value;
    }

    /*
     * @return String
     * */
    public function as_table($use_pk = false)
    {
        $html = '';

        foreach ($this->instance->fields as $field => $options) {
            if ($field !== $this->instance->primaryKey()) {
                $inputField = null;

                switch ($options['type']) {
                    case 'INT' : {
                        $inputField = '<input name = "' . $field . '" type = "int"
                            value="' . (!empty($this->instance->$field) ? $this->instance->$field : null) . '"
                            ' . (($options['required']) ? 'required' : null) . ' />';
                    }
                        break;

                    default : {
                        $inputField = '<input name = "' . $field . '" type = "text"
                            ' . (!empty($options['length']) ? 'maxlength="' . $options['length'] . '"' : null) . '
                            value="' . (!empty($this->instance->$field) ? $this->instance->$field : null) . '"
                            ' . (($options['required']) ? 'required' : null) . ' />';
                    }
                        break;

                    case 'FK' : {
                        $inputField = '<select name = "' . $field . '" ' . (($options['required']) ? 'required' : null) . '>';

                        $inputField .= '<option value = "">-- Select ' . ucfirst($this->instance->tableName()) . ' --</option>';

                        $values = call_user_func([$options['class'], 'getAll']);

                        foreach ($values as $model) {
                            $pk = $model->primaryKey();

                            $selected = null;


                            if (is_object($this->instance->$field)) {
                                if ($model->$pk == $this->instance->$field->$pk)
                                    $selected = 'selected';
                            } elseif ($model->$pk == $this->instance->$field)
                                $selected = 'selected';

                            $inputField .= '<option ' . $selected . ' value="' . ($model->$pk) . '">' . $model . '</option>';
                        }

                        $inputField .= '</select>';
                    }
                        break;
                }

                $html .= '<tr>';

                $html .= '<td>' . (!(empty($options['label'])) ? $options['label'] : null) . '</td>';
                $html .= '<td>' . $inputField . '</td>';

                $html .= '</tr>';
            } elseif ($use_pk) {
                if (!empty($this->instance))
                    $html .= '<input type = "hidden" name="' . $field . '" value = "' . $this->instance->$field . '"/>';
            }
        }

        return $html;
    }


    public function field($fieldName, array $options)
    {
        $field = $this->instance->fields[$fieldName];

        switch ($field['type']) {
            case 'INT' : {
                $html = '
                <label for = "_' . $fieldName . '">' . (!(empty($field['label'])) ? $field['label'] : null) . '</label>
                <input id = "_' . $fieldName . '" class = "' . (!empty($options['class']) ? $options['class'] : null) . '"
                    name = "' . $fieldName . '" type = "int"
                    value="' . (!empty($this->instance->$fieldName) ? $this->instance->$fieldName : null) . '"
                    ' . (($field['required']) ? 'required' : null) . ' />';
            }
                break;

            default : {
                $html = '
                <label for = "_' . $fieldName . '">' . (!(empty($field['label'])) ? $field['label'] : null) . '</label>
                <input id = "_' . $fieldName . '" class = "' . (!empty($options['class']) ? $options['class'] : null) . '" name = "' . $fieldName . '" type = "text"
                            ' . (!empty($field['length']) ? 'maxlength="' . $field['length'] . '"' : null) . '
                            value="' . (!empty($this->instance->$fieldName) ? $this->instance->$fieldName : null) . '"
                            ' . (($field['required']) ? 'required' : null) . ' />';
            }
                break;

            case 'FK' : {
                $html = '
                <label for = "_' . $fieldName . '">' . (!(empty($field['label'])) ? $field['label'] : null) . '</label>
                <select id = "_' . $fieldName . '" class = ' . (!empty($options['class']) ? $options['class'] : null) . ' name = "' . $fieldName . '" ' . (($field['required']) ? 'required' : null) . '>';

                $html .= '<option value = "">-- Select ' . ucfirst(call_user_func([$field['class'], 'tableName'])) . ' --</option>';

                $values = call_user_func([$field['class'], 'getAll']);

                foreach ($values as $model) {
                    $pk = $model->primaryKey();

                    $selected = null;


                    if (is_object($this->instance->$fieldName)) {
                        if ($model->$pk == $this->instance->$fieldName->$pk)
                            $selected = 'selected';
                    } elseif ($model->$pk == $this->instance->$fieldName)
                        $selected = 'selected';

                    $html .= '<option ' . $selected . ' value="' . ($model->$pk) . '">' . $model . '</option>';
                }

                $html .= '</select>';
            }
                break;
        }


        return $html;
    }

    public function is_valid()
    {
        $is_valid = $this->instance->is_valid();

        $this->errors = $this->instance->errors;

        return $is_valid;
    }

    public function save()
    {
        return $this->instance->save();
    }
}