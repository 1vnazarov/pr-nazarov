<?php
function db_connect() {
    $DB = mysqli_connect("localhost", "Nazarov_diplomas_archive", "12363znasS_", "Nazarov_diplomas_archive");
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $DB;
}

function db_query($DB, $query, $params = [], $types = "") {
    $stmt = mysqli_prepare($DB, $query);
    if ($stmt === false) {
        die("Failed to prepare statement: " . mysqli_error($DB));
    }

    if (!empty($params) && !empty($types)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (!mysqli_stmt_execute($stmt)) {
        die("Execute failed: " . mysqli_stmt_error($stmt));
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
