<?php

require_once "db_connect.php";

class User
{
    public $fullname;
    public $email;
    public $password;
    public $id_qualification;
    public $id_role;
    public $token;

    public function __construct($request)
    {
        @$this->fullname = $request['fullname'];
        @$this->email = $request['email'];
        @$this->password = $request['password'];
        @$this->id_qualification = $request['id_qualification'];
        @$this->id_role = $request['id_role'] ?? 1;
        @$this->token = boolval($request['token']);
    }

    private function generate_token()
    {
        $this->token = bin2hex(random_bytes(32));
    }

    public function validation($isPost = true)
    {
        $errors = [];

        if ($isPost || isset($this->fullname)) {
            if (empty($this->fullname) || !preg_match("/^[A-Za-zА-Яа-яЁё\s\-]+$/u", $this->fullname)) {
                $errors['fullname'] = "Некорректные ФИО";
            }
        }

        if ($isPost || isset($this->email)) {
            if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Некорректный email";
            }
        }

        if ($isPost || isset($this->password)) {
            if (empty($this->password) || !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!?.,&^_])[A-Za-z\d!?.,&^_]{8,}$/u", $this->password)) {
                $errors['password'] = "Пароль должен содержать минимум 8 символов, одну заглавную букву, одну строчную букву, одну цифру и один из спец.символов";
            }
        }

        if ($isPost || isset($this->id_qualification)) {
            if ($this->id_role == 1 && empty($this->id_qualification)) {
                $errors['id_qualification'] = "Специальность обязательна для студента";
            }
        }

        return $errors;
    }

    public function create($DB)
    {
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->generate_token();
        return query($DB, "INSERT INTO user (user_fullname, user_email, user_password, id_qualification, id_role, user_token) VALUES (?, ?, ?, ?, ?, ?)", [$this->fullname, $this->email, $hashed_password, $this->id_qualification, $this->id_role, $this->token], "sssiis");
    }

    public static function get_all($DB)
    {
        $result = query($DB, "SELECT * FROM user");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function get_by_id($DB, $id)
    {
        $result = query($DB, "SELECT * FROM user WHERE user_id = ?", [$id], "i");
        return mysqli_fetch_assoc($result);
    }

    public function update($DB, $id)
    {
        $updates = [];
        $params = [];
        $types = '';

        if (!empty($this->fullname)) {
            $updates[] = "user_fullname = ?";
            $params[] = $this->fullname;
            $types .= 's';
        }
        if (!empty($this->email)) {
            $updates[] = "user_email = ?";
            $params[] = $this->email;
            $types .= 's';
        }
        if (!empty($this->password)) {
            $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
            $updates[] = "user_password = ?";
            $params[] = $hashed_password;
            $types .= 's';
        }
        if (!empty($this->id_qualification)) {
            $updates[] = "id_qualification = ?";
            $params[] = $this->id_qualification;
            $types .= 'i';
        }
        if (!empty($this->id_role)) {
            $updates[] = "id_role = ?";
            $params[] = $this->id_role;
            $types .= 'i';
        }

        if (!empty($this->token)) {
            $this->generate_token();
            $updates[] = "user_token = ?";
            $params[] = $this->token;
            $types .= 's';
        }

        if (empty($updates)) return false;

        $params[] = $id;
        $types .= 'i';

        $sql = "UPDATE user SET " . implode(', ', $updates) . " WHERE user_id = ?";
        return query($DB, $sql, $params, $types);
    }

    public static function delete($DB, $id)
    {
        return query($DB, "DELETE FROM user WHERE user_id = ?", [$id], "i");
    }
}
