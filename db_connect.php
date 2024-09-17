<?php
function db_connect() {
    $DB = mysqli_connect("localhost", "Nazarov_diplomas_archive", "12363znasS_", "Nazarov_diplomas_archive");
    mysqli_set_charset($DB, "utf8");
    return $DB;
}

function db_query($DB, $query) {
    $result = mysqli_query($DB, $query);
    if ($result) return $result;
    die(mysqli_error($DB));
}

function db_close($DB) {
    return mysqli_close($DB);
}