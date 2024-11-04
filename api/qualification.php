<?php

require_once "BaseModel.php";

class Qualification extends BaseModel {
    public $name;

    public function __construct($request) {
        @$this->name = $request['name'];

        
        $this->fields = [
            'name' => 'qualification_name'
        ];

        $this->rules = [
            'name' => [['required', 'Название специальности обязательно']]
        ];
    }

    public function create() {
        return self::query("INSERT INTO qualification (qualification_name) VALUES ?", [$this->name], "s");
    }

    public function update($id) {
        return self::query("UPDATE qualification SET qualification_name = ? WHERE qualification_id = ?", [$this->name, $id], "si");
    }
}