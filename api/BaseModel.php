<?php

require_once 'db_connect.php';

abstract class BaseModel
{
    protected $fields = [];
    protected static function tableName() {
        return lcfirst(get_called_class());
    }

    protected static function primaryKey() {
        return get_called_class()::tableName() . '_id';
    }

    public static function auth($userId)
    {
        $token = null;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $matches = [];
            if (preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
                $token = $matches[1];
            }
        }
        $userToken = self::query("SELECT user_token FROM user WHERE user_id = ?", [$userId], 'i')['user_token'] ?? null;
        return !empty($token) && $token === $userToken;
    }
    
    protected static function query($query, $params = [], $types = "") {
        $DB = connect();
        $result = query($DB, $query, $params, $types);
        mysqli_close($DB);
        return $result;
    }
    
    private static function isTableExists() {
        $table = get_called_class()::tableName();
        return !empty(self::query("SHOW TABLES LIKE '$table'"));
    }

    private function validate_required($field, $val) {
        return [!empty($val), "Поле $field обязательно для заполнения"];
    }

    private function validate_email($field, $val) {
        return [filter_var($val, FILTER_VALIDATE_EMAIL) !== false, "Некорректный email"];
    }

    private function validate_unique($field, $val) {
        $table = get_called_class()::tableName();
        $db_field = array_key_exists($field, $this->fields) ? $this->fields[$field] : $field;
        return [self::query("SELECT COUNT(*) AS count FROM $table WHERE $db_field = ?", [$val], "s")['count'] == 0, "Поле $field должно быть уникальным"];
    }

    private function validate_pattern($field, $val, $pattern) {
        return [preg_match($pattern, $val) === 1, "Поле $field введено некорректно"];
    }
     
    private function validate_int($field, $val) {
        return [is_int($val), "Поле $field должно быть целым числом"];
    }

    protected $rules = [];
    public function validation($isPost = true)
    {
        $errors = [];

        foreach ($this->rules as $field => $validation) {
            if ($isPost || isset($this->$field)) {
                foreach ($validation as $rule) {
                    $rule_message = null;

                    if (is_array($rule)) {
                        list($rule, $rule_message) = $rule;
                    }

                    if (is_callable($rule)) { // Если кастомное правило
                        if (!$rule($field, $this->$field)) {
                            $errors[$field] = $rule_message;
                        }
                        continue;
                    }

                    $args = explode(':', $rule); // 0 - правило, 1 - значение (если есть)
                    $rule_name = array_shift($args);
                    $validate_func = "validate_$rule_name";
                    if (!method_exists($this, $validate_func)) continue;

                    list($ok, $message) = $this->$validate_func($field, $this->$field, ...$args);
                    if (!$ok) {
                        $errors[$field] = $rule_message ?? $message;
                    }
                }
            }
        }
        return $errors;
    }


    public static function get($id = null)
    {
        if (!self::isTableExists()) return false;
        $table = get_called_class()::tableName();
        if (!$id) {
            return self::query("SELECT * FROM $table");
        } else if (is_numeric($id)) {
            $id_column = get_called_class()::primaryKey();
            return self::query("SELECT * FROM $table WHERE $id_column = ?", [$id], 'i');
        }
        return false;
    }

    public static function delete($id) {
        if (!is_numeric($id) || !self::isTableExists()) return false;
        $table = get_called_class()::tableName();
        $id_column = get_called_class()::primaryKey();
        return self::query("DELETE FROM $table WHERE $id_column = ?", [$id], "i");
    }

    abstract public function create();
    abstract public function update($id);
}