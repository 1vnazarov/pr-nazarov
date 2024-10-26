<?php

require_once "BaseModel.php";

class User extends BaseModel
{
    public $fullname;
    public $email;
    public $password;
    public $id_qualification;
    public $id_role;
    public $token;
    public $avatar;

    public function __construct($request)
    {
        @$this->fullname = $request['fullname'];
        @$this->email = $request['email'];
        @$this->password = $request['password'];
        @$this->id_qualification = $request['id_qualification'];
        @$this->id_role = $request['id_role'] ?? 1;
        @$this->token = boolval($request['token']);
        @$this->avatar = $request['avatar'];

        $this->validations = [
            'fullname' => [
                'rule' => fn($value) => !empty($value) && preg_match("/^[A-Za-zА-Яа-яЁё\s\-]+$/u", $value),
                'message' => "Некорректные ФИО"
            ],
            'email' => [
                'rule' => fn($value) => !empty($value) && filter_var($value, FILTER_VALIDATE_EMAIL) && self::query("SELECT COUNT(*) AS count FROM user WHERE user_email = ?", [$value], "s")['count'] == 0,
                'message' => "Некорректный email или уже занят"
            ],
            'password' => [
                'rule' => fn($value) => !empty($value) && preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!?.,&^_])[A-Za-z\d!?.,&^_]{8,}$/u", $value),
                'message' => "Пароль должен содержать минимум 8 символов, одну заглавную букву, одну строчную букву, одну цифру и один из спец.символов"
            ],
            'id_qualification' => [
                'rule' => fn($value) => $this->id_role != self::query("SELECT role_id FROM role WHERE role_name = ?", ["Студент"], "s")['role_id'] || !empty($value),
                'message' => "Специальность обязательна для студента"
            ],
            'avatar' => [
                'rule' => fn($value) => !empty($value) && preg_match("/^storage\/avatar_\d+_.+$/", $value),
                'message' => "Некорректный путь к аватару"
            ]
        ];
    }

    private function generate_token()
    {
        $this->token = bin2hex(random_bytes(32));
    }

    private function upload_avatar($user_id)
    {
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatar_filename = 'storage/avatar_' . $user_id . '_' . basename($_FILES['avatar']['name']);
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar_filename)) {
                return $avatar_filename;
            }
        }
        return null;
    }

    public function create()
    {
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->generate_token();
        $user_id = self::query(
            "INSERT INTO user (user_fullname, user_email, user_password, id_qualification, id_role, user_token) VALUES (?, ?, ?, ?, ?, ?)",
            [$this->fullname, $this->email, $hashed_password, $this->id_qualification, $this->id_role, $this->token],
            "sssiis"
        );
        if ($user_id) {
            $avatarPath = $this->upload_avatar($user_id);
            if ($avatarPath) self::query("UPDATE user SET avatar = ? WHERE user_id = ?", [$avatarPath, $user_id], "si");
            return $user_id;
        }
        return false;
    }

    public static function get_all()
    {
        return self::query("SELECT * FROM user");
    }

    public static function get_by_id($id)
    {
        return self::query("SELECT * FROM user WHERE user_id = ?", [$id], "i");
    }

    public function update($id)
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

        if ($this->avatar) {
            $updates[] = "avatar = ?";
            $params[] = $this->avatar;
            $types .= 's';
        }

        if (empty($updates)) return false;

        $params[] = $id;
        $types .= 'i';

        $sql = "UPDATE user SET " . implode(', ', $updates) . " WHERE user_id = ?";
        return self::query($sql, $params, $types);
    }

    public static function delete($id)
    {
        return self::query("DELETE FROM user WHERE user_id = ?", [$id], "i");
    }
}
