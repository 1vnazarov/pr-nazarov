<?php
require_once "db_connect.php";
$DB = db_connect();
$fullname = filter_input(INPUT_POST, 'fullname', FILTER_VALIDATE_REGEXP, [
    'options' => ['regexp' => "/^[А-ЯЁ][а-яё]+(?:-[А-ЯЁ][а-яё]+)?\s[А-ЯЁ][а-яё]+(?:-[А-ЯЁ][а-яё]+)?(?:\s[А-ЯЁ][а-яё]+(?:-[А-ЯЁ][а-яё]+)?)?$/u"]
]);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password_hash = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT);
$qualification = filter_input(INPUT_POST, 'qualification', FILTER_VALIDATE_INT);
if (db_query($DB, "INSERT INTO user (user_fullname, user_email, user_password, id_qualification) VALUES (?, ?, ?, ?);", [$fullname, $email, $password_hash, $qualification], 'sssi')) {
    $user_id = mysqli_insert_id($DB);
    $qalification_result = mysqli_fetch_assoc(db_query($DB, "SELECT qualification_name FROM qualification WHERE qualification_id = ?;", [$qualification], 'i'));
    header("Location: profile.php?id=$user_id");
    mysqli_close($DB);
}
?>