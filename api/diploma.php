<?php

require_once "BaseModel.php";

class Diploma extends BaseModel {
    public $diploma_id;
    public $diploma_topic;
    public $diploma_abstract;
    public $diploma_text_url;
    public $id_student;
    public $id_thesis_advisor;
    public $diploma_mark;
    public $diploma_production_year;

    public function __construct($request) {
        @$this->diploma_id = $request['diploma_id'];
        @$this->diploma_topic = $request['diploma_topic'];
        @$this->diploma_abstract = $request['diploma_abstract'];
        @$this->diploma_text_url = $request['diploma_text_url'];
        @$this->id_student = $request['id_student'];
        @$this->id_thesis_advisor = $request['id_thesis_advisor'];
        @$this->diploma_mark = $request['diploma_mark'];
        @$this->diploma_production_year = $request['diploma_production_year'];
        
        $this->validations = [
            'diploma_topic' => [
                'rule' => fn($value) => !empty($value),
                'message' => "Тема диплома обязательна"
            ],
            'diploma_abstract' => [
                'rule' => fn($value) => !empty($value),
                'message' => "Аннотация диплома обязательна"
            ],
            'id_student' => [
                'rule' => fn($value) => !empty($value) && filter_var($value, FILTER_VALIDATE_INT),
                'message' => "ID студента обязательно и должно быть целым числом"
            ],
            'id_thesis_advisor' => [
                'rule' => fn($value) => !empty($value) && filter_var($value, FILTER_VALIDATE_INT),
                'message' => "ID дипломного руководителя обязательно и должно быть целым числом"
            ],
            'diploma_mark' => [
                'rule' => fn($value) => in_array($value, ['3', '4', '5']),
                'message' => "Оценка диплома должна быть 3, 4 или 5"
            ],
            'diploma_production_year' => [
                'rule' => fn($value) => !empty($value) && preg_match('/^\d{4}$/', $value),
                'message' => "Год защиты диплома обязателен и должен быть в формате YYYY"
            ]
        ];
    }

    public function create() {
        return self::query("INSERT INTO diploma (diploma_topic, diploma_abstract, diploma_text_url, id_student, id_thesis_advisor, diploma_mark, diploma_production_year) VALUES (?, ?, ?, ?, ?, ?, ?)", 
            [$this->diploma_topic, $this->diploma_abstract, $this->diploma_text_url, $this->id_student, $this->id_thesis_advisor, $this->diploma_mark, $this->diploma_production_year], 
            "sssisii");
    }

    public static function get_all() {
        return self::query("SELECT * FROM diploma");
    }

    public static function get_by_id($id) {
        return self::query("SELECT * FROM diploma WHERE diploma_id = ?", [$id], "i");
    }

    public function update($id) {
        $updates = [];
        $params = [];
        $types = '';

        if (!empty($this->diploma_topic)) {
            $updates[] = "diploma_topic = ?";
            $params[] = $this->diploma_topic;
            $types .= 's';
        }
        if (!empty($this->diploma_abstract)) {
            $updates[] = "diploma_abstract = ?";
            $params[] = $this->diploma_abstract;
            $types .= 's';
        }
        if (!empty($this->diploma_text_url)) {
            $updates[] = "diploma_text_url = ?";
            $params[] = $this->diploma_text_url;
            $types .= 's';
        }
        if (!empty($this->id_student)) {
            $updates[] = "id_student = ?";
            $params[] = $this->id_student;
            $types .= 'i';
        }
        if (!empty($this->id_thesis_advisor)) {
            $updates[] = "id_thesis_advisor = ?";
            $params[] = $this->id_thesis_advisor;
            $types .= 'i';
        }
        if (!empty($this->diploma_mark)) {
            $updates[] = "diploma_mark = ?";
            $params[] = $this->diploma_mark;
            $types .= 'i';
        }
        if (!empty($this->diploma_production_year)) {
            $updates[] = "diploma_production_year = ?";
            $params[] = $this->diploma_production_year;
            $types .= 'i';
        }

        if (empty($updates)) return false;

        $params[] = $id;
        $types .= 'i';

        $sql = "UPDATE diploma SET " . implode(', ', $updates) . " WHERE diploma_id = ?";
        return self::query($sql, $params, $types);
    }    

    public static function delete($id) {
        return self::query("DELETE FROM diploma WHERE diploma_id = ?", [$id], "i");
    }
}