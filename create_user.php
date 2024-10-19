<?php
require_once "db_connect.php";
require_once "error_handler.php";
$DB = db_connect();
$fullname = filter_input(INPUT_POST, 'fullname', FILTER_VALIDATE_REGEXP, [
    'options' => ['regexp' => "/^[А-ЯЁ][а-яё]+(?:-[А-ЯЁ][а-яё]+)?\s[А-ЯЁ][а-яё]+(?:-[А-ЯЁ][а-яё]+)?(?:\s[А-ЯЁ][а-яё]+(?:-[А-ЯЁ][а-яё]+)?)?$/u"]
]) or Error(E_USER_ERROR, "Неверное ФИО");
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) or Error("Неверный email");
$password = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, [
    'options'=> ['regexp'=> '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!?.,&^_])[A-Za-z\d!?.,&^_]{8,}$/u']
]) or Error(E_USER_ERROR, "Пароль не может быть пустым");
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$password_hash = password_hash(htmlspecialchars($password), PASSWORD_DEFAULT);
$qualification = filter_input(INPUT_POST, 'qualification', FILTER_VALIDATE_INT) or Error("Неверная специальность");
$image_fieldname = "avatar";
if ($_FILES[$image_fieldname]['error'] !== 0) {
    Error("Ошибка загрузки изображения");
}

if (!is_uploaded_file($_FILES[$image_fieldname]['tmp_name'])) {
    Error("Изображение не загружено корректно");
}

if (!getimagesize($_FILES[$image_fieldname]['tmp_name'])) {
    Error("Загруженный файл не является изображением");
}

if (db_query($DB, "INSERT INTO user (user_fullname, user_email, user_password, id_qualification) VALUES (?, ?, ?, ?);", [$fullname, $email, $password_hash, $qualification], 'sssi')) {
    $user_id = mysqli_insert_id($DB);
    $qalification_result = mysqli_fetch_assoc(db_query($DB, "SELECT qualification_name FROM qualification WHERE qualification_id = ?;", [$qualification], 'i'));
    $avatar_filename = 'storage/avatar_' . $user_id . '_' . $_FILES[$image_fieldname]['name'];
    if (!move_uploaded_file($_FILES[$image_fieldname]['tmp_name'], $avatar_filename)) {
        Error("Ошибка перемещения файла изображения");
    }
    db_query($DB, "UPDATE user SET user_avatar = ? WHERE user_id = ?", [$avatar_filename, $user_id], "si");
    header("Location: profile.php?id=$user_id");
    mysqli_close($DB);
}
?>