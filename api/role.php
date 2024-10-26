<?php

require_once "BaseModel.php";

class Role extends BaseModel {
    public $name;

    public function __construct($request) {
        @$this->name = $request['name'];

        $this->validations  = [
            'name' => [
                'rule' => fn($value) => !empty($value),
                'message' => "Название роли обязательно"
            ]
        ];
    }

    public function create() {
        return self::query("INSERT INTO role (role_name) VALUES ?", [$this->name], "s");
    }

    public static function get_all() {
        return self::query("SELECT * FROM role");
    }

    public static function get_by_id($id) {
        return self::query("SELECT * FROM role WHERE role_id = ?", [$id], "i");
    }

    public function update($id) {
        return self::query("UPDATE role SET role_name = ? WHERE role_id = ?", [$this->name, $id], "si");
    }

    public static function delete($id) {
        return self::query("DELETE FROM role WHERE role_id = ?", [$id], "i");
    }
}