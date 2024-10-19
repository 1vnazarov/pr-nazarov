<?php
require_once "db_connect.php";
require_once "error_handler.php";
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL) or Error("Неверный email");
$DB = db_connect();
$user_id = db_query($DB, "SELECT user_id FROM user WHERE user_email = ?", [$email], "s");
if (mysqli_num_rows($user_id) > 0) {
    http_response_code(204);
}
 else {
    http_response_code(404);
}