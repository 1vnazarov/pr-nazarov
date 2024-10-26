<?php

require_once "BaseModel.php";

class Qualification extends BaseModel {
    public $name;

    public function __construct($request) {
        @$this->name = $request['name'];

        $this->validations = [
            'name' => [
                'rule' => fn($value) => !empty($value),
                'message' => "Название специальности обязательно"
            ]
        ];
    }

    public function create() {
        return self::query("INSERT INTO qualification (qualification_name) VALUES ?", [$this->name], "s");
    }

    public static function get_all() {
        return self::query("SELECT * FROM qualification");
    }

    public static function get_by_id($id) {
        return self::query("SELECT * FROM qualification WHERE qualification_id = ?", [$id], "i");
    }

    public function update($id) {
        return self::query("UPDATE qualification SET qualification_name = ? WHERE qualification_id = ?", [$this->name, $id], "si");
    }

    public static function delete($id) {
        return self::query("DELETE FROM qualification WHERE qualification_id = ?", [$id], "i");
    }
}