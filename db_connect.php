<?php
function db_connect() {
    return mysqli_connect("localhost", "Nazarov_diplomas_archive", "12363znasS_", "Nazarov_diplomas_archive");
}

function db_query($DB, $query) {
    $result = mysqli_query($DB, $query);
    if ($result) return $result;
    die(mysqli_error($DB));
}