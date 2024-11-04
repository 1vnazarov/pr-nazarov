<?php

require_once "BaseModel.php";

class Role extends BaseModel {
    public $name;

    public function __construct($request) {
        @$this->name = $request['name'];

        $this->fields = [
            'name' => 'role_name'
        ];

        $this->rules  = [
            'name' => [['required', 'Название роли обязательно']]
        ];
    }

    public function create() {
        return self::query("INSERT INTO role (role_name) VALUES ?", [$this->name], "s");
    }

    public function update($id) {
        return self::query("UPDATE role SET role_name = ? WHERE role_id = ?", [$this->name, $id], "si");
    }
}