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
        
        $this->rules = [
            'diploma_topic' => [['required', "Тема диплома обязательна"]],
            'diploma_abstract' => [['required', "Аннотация диплома обязательна"]],
            'id_student' => [['required', "ID студента обязательно"], ['int', "ID студента должно быть числом"]],
            'id_thesis_advisor' => [['required', "ID дипломного руководителя обязательно"], ['int', "ID дипломного руководителя должно быть числом"]],
            'diploma_mark' => [[fn($key, $value) => in_array($value, ['3', '4', '5']), "Оценка диплома должна быть 3, 4 или 5"]],
            'diploma_production_year' => [['required', "Год защиты диплома обязателен"], ['patterm:/^\d{4}$/', "Год защиты диплома должен быть в формате YYYY"]]
        ];
    }

    public function create() {
        return self::query("INSERT INTO diploma (diploma_topic, diploma_abstract, diploma_text_url, id_student, id_thesis_advisor, diploma_mark, diploma_production_year) VALUES (?, ?, ?, ?, ?, ?, ?)", 
            [$this->diploma_topic, $this->diploma_abstract, $this->diploma_text_url, $this->id_student, $this->id_thesis_advisor, $this->diploma_mark, $this->diploma_production_year], 
            "sssisii");
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
}