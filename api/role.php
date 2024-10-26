<?php

require_once "db_connect.php";

class Role {
    public $name;

    public function __construct($request) {
        @$this->name = $request['name'];
    }

    public function validation() {
        $errors = [];
        if (empty($this->name)) {
            $errors['name'] = "Название роли обязательно";
        }
        return $errors;
    }

    public function create($DB) {
        return query($DB, "INSERT INTO role (role_name) VALUES ?", [$this->name], "s");
    }

    public static function get_all($DB) {
        $result = query($DB, "SELECT * FROM role");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function get_by_id($DB, $id) {
        $result = query($DB, "SELECT * FROM role WHERE role_id = ?", [$id], "i");
        return mysqli_fetch_assoc($result);
    }

    public function update($DB, $id) {
        return query($DB, "UPDATE role SET role_name = ? WHERE role_id = ?", [$this->name, $id], "si");
    }

    public static function delete($DB, $id) {
        return query($DB, "DELETE FROM role WHERE role_id = ?", [$id], "i");
    }
}