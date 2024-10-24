<?

require_once "db_connect.php";

class Diploma {
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
    }

    public function validation() {
        $valid = [];
        if (empty($this->diploma_topic)) {
            $valid['diploma_topic'] = "Тема диплома обязательна";
        }
        if (empty($this->diploma_abstract)) {
            $valid['diploma_abstract'] = "Аннотация диплома обязательна";
        }
        if (empty($this->id_student) || !filter_var($this->id_student, FILTER_VALIDATE_INT)) {
            $valid['id_student'] = "ID студента обязательно и должно быть целым числом";
        }
        if (empty($this->id_thesis_advisor) || !filter_var($this->id_thesis_advisor, FILTER_VALIDATE_INT)) {
            $valid['id_thesis_advisor'] = "ID дипломного руководителя обязательно и должно быть целым числом";
        }
        if (!in_array($this->diploma_mark, ['3', '4', '5'])) {
            $valid['diploma_mark'] = "Оценка диплома должна быть 3, 4 или 5";
        }
        if (empty($this->diploma_production_year) || !preg_match('/^\d{4}$/', $this->diploma_production_year)) {
            $valid['diploma_production_year'] = "Год защиты диплома обязателен и должен быть в формате YYYY";
        }
        return $valid;
    }

    public function create($DB) {
        return query($DB, "INSERT INTO diploma (diploma_topic, diploma_abstract, diploma_text_url, id_student, id_thesis_advisor, diploma_mark, diploma_production_year) VALUES (?, ?, ?, ?, ?, ?, ?)", 
            [$this->diploma_topic, $this->diploma_abstract, $this->diploma_text_url, $this->id_student, $this->id_thesis_advisor, $this->diploma_mark, $this->diploma_production_year], 
            "sssisii");
    }

    public static function get_all($DB) {
        $result = query($DB, "SELECT * FROM diploma");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function get_by_id($DB, $id) {
        $result = query($DB, "SELECT * FROM diploma WHERE diploma_id = ?", [$id], "i");
        return mysqli_fetch_assoc($result);
    }

    public function update($DB, $id) {
        return query($DB, "UPDATE diploma SET diploma_topic = ?, diploma_abstract = ?, diploma_text_url = ?, id_student = ?, id_thesis_advisor = ?, diploma_mark = ?, diploma_production_year = ? WHERE diploma_id = ?", 
            [$this->diploma_topic, $this->diploma_abstract, $this->diploma_text_url, $this->id_student, $this->id_thesis_advisor, $this->diploma_mark, $this->diploma_production_year, $id], "sssisii");
    }

    public static function delete($DB, $id) {
        return query($DB, "DELETE FROM diploma WHERE diploma_id = ?", [$id], "i");
    }
}