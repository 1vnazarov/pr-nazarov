<?php

require_once "db_connect.php";

class Qualification {
    public $name;

    public function __construct($request) {
        @$this->name = $request['name'];
    }

    public function validation() {
        $valid = [];
        if (empty($this->name)) {
            $valid['name'] = "Название специальности обязательно";
        }
        return $valid;
    }

    public function create($DB) {
        return query($DB, "INSERT INTO qualification (qualification_name) VALUES ?", [$this->name], "s");
    }

    public static function get_all($DB) {
        $result = query($DB, "SELECT * FROM qualification");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function get_by_id($DB, $id) {
        $result = query($DB, "SELECT * FROM qualification WHERE qualification_id = ?", [$id], "i");
        return mysqli_fetch_assoc($result);
    }

    public function update($DB, $id) {
        return query($DB, "UPDATE qualification SET qualification_name = ? WHERE qualification_id = ?", [$this->name, $id], "si");
    }

    public static function delete($DB, $id) {
        return query($DB, "DELETE FROM qualification WHERE qualification_id = ?", [$id], "i");
    }
}