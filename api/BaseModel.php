<?php

require_once 'db_connect.php';

abstract class BaseModel
{
    protected static function query($query, $params = [], $types = "") {
        $DB = connect();
        $result = query($DB, $query, $params, $types);
        mysqli_close($DB);
        return $result;
    }

    protected $validations = [];

    public function validation($isPost = true)
    {
        $errors = [];

        foreach ($this->validations as $field => $validation) {
            if ($isPost || isset($this->field)) {
                if (!$validation['rule']($this->$field)) {
                    $errors[$field] = $validation['message'];
                }
            }
        }

        return $errors;
    }

    abstract public function create();
    abstract public static function get_all();
    abstract public static function get_by_id($id);
    abstract public function update($id);
    abstract public static function delete($id);
}
