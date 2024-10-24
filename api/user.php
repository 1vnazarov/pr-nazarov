<?php

require_once "db_connect.php";

class User {
    public $fullname, $email, $password, $id_qualification, $id_role;

    public function __construct($request) {
        @$this->fullname = $request['fullname'];
        @$this->email = $request['email'];
        @$this->password = $request['password'];
        @$this->id_qualification = $request['id_qualification'];
        @$this->id_role = $request['id_role'];
    }

    public function validation() {
        $valid = [];
        if (empty($this->fullname) || !preg_match("/^[A-Za-zА-Яа-яЁё\s\-]+$/u", $this->fullname)) {
            $valid['fullname'] = "Некорректные ФИО";
        }
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $valid['email'] = "Некорректный email";
        }
        if (empty($this->password) || !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!?.,&^_])[A-Za-z\d!?.,&^_]{8,}$/u", $this->password)) {
            $valid['password'] = "Пароль должен содержать минимум 8 символов, одну заглавную букву, одну строчную букву, одну цифру и один из спец.символов";
        }
        return $valid;
    }

    public function create($DB) {
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        return query($DB, "INSERT INTO user (user_fullname, user_email, user_password, id_qualification, id_role) VALUES (?, ?, ?, ?, ?)", [$this->fullname, $this->email, $hashed_password, $this->id_qualification, $this->id_role], "ssiii");
    }

    public static function get_all($DB) {
        $result = query($DB, "SELECT * FROM user");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function get_by_id($DB, $id) {
        $result = query($DB, "SELECT * FROM user WHERE user_id = ?", [$id], "i");
        return mysqli_fetch_assoc($result);
    }

    public function update($DB, $id) {
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        return query($DB, "UPDATE user SET user_fullname = ?, user_email = ?, user_password = ?, id_qualification = ?, id_role = ? WHERE user_id = ?", [$this->fullname, $this->email, $hashed_password, $this->id_qualification, $this->id_role, $id], "sssiii");
    }

    public static function delete($DB, $id) {
        return query($DB, "DELETE FROM user WHERE user_id = ?", [$id], "i");
    }
}
