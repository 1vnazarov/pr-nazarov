<?php
require_once "error_handler.php";
require_once "db_connect.php";
require_once "white_date.php";

$DB = db_connect();
$email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL) or Error("Электронная почта введена в некорректном формате");
$token = htmlspecialchars($_GET['token']);

if ($email && $token) {
    session_start();
    // Проверяем, что переданный token совпадает с date_created в базе
    $query = "SELECT user_id, user_date_created FROM user WHERE user_email = ? AND check_email = 0";
    $result = mysqli_fetch_assoc(db_query($DB, $query, [$email], "s"));

    if ($result && white_date($result["user_date_created"]) === $token) {
        // Обновляем check_email на подтвержден
        db_query($DB, "UPDATE user SET check_email = 1 WHERE user_email = ?", [$email], "s");
        $_SESSION['confirm_email_success'] = "Ваш адрес электронной почты успешно подтвержден!";
        header("Location: /profile.php?id=$result[user_id]");
    } else {
        Error("Некорректная ссылка подтверждения или адрес уже подтвержден.");
    }
} else {
    Error("Неверные параметры.");
}

mysqli_close($DB);