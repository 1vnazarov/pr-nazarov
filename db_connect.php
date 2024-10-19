<?php
require_once "error_handler.php";
function db_connect() {
    $DB = null;
    try {
        $DB = mysqli_connect("localhost", "Nazarov_diplomas_archive", "12363znasS_", "Nazarov_diplomas_archive");
    } catch (Exception $e) {
        Error("Connection failed: " . $e->getMessage());
    }
    return $DB;
}

function db_query($DB, $query, $params = [], $types = "") {
    $stmt = mysqli_prepare($DB, $query);
    if ($stmt === false) {
        Error("Failed to prepare statement: " . mysqli_error($DB));
    }

    if (!empty($params) && !empty($types)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (!mysqli_stmt_execute($stmt)) {
        Error("Execute failed: " . mysqli_stmt_error($stmt));
    }

    // Получение результата для SELECT-запроса
    $result = mysqli_stmt_get_result($stmt);

    // Если результат есть (для SELECT), возвращаем его
    if ($result !== false) {
        return $result;
    }

    // Иначе возвращаем успешность выполнения для других запросов
    return $stmt;
}
?>
