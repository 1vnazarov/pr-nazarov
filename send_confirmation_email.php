<?php
require_once "error_handler.php";
require_once "db_connect.php";
require_once "white_date.php";

function send_confirmation_email($user)
{
    $subject = "Подтверждение адреса электронной почты";
    $token = white_date($user['user_date_created']);
    $email = $user['user_email'];
    $url = "https://pr-nazarov.сделай.site/confirm_email.php?email=$email&token=$token";
    $message = "
    <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    font-size: 18px;
                }
                .email-container {
                    background-color: white;
                    margin: 0 auto;
                    padding: 20px;
                    max-width: 600px;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }
                .email-header {
                    background-color: #007bff;
                    color: white;
                    padding: 10px;
                    text-align: center;
                    border-radius: 8px 8px 0 0;
                }
                .email-body {
                    padding: 20px;
                    text-align: center;
                }
                .email-footer {
                    background-color: #f4f4f4;
                    text-align: center;
                    padding: 10px;
                    color: #777;
                    border-radius: 0 0 8px 8px;
                }
                .button {
                    background-color: #28a745;
                    color: white;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                    display: inline-block;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                    <h1>Подтверждение электронной почты</h1>
                </div>
                <div class='email-body'>
                    <p>Здравствуйте!</p>
                    <p>Пожалуйста, подтвердите ваш адрес электронной почты, нажав на кнопку ниже:</p>
                    <a href='$url' class='button'>Подтвердить Email</a>
                    <p>Если вы не регистрировались на нашем сайте, просто проигнорируйте это письмо.</p>
                </div>
                <div class='email-footer'>
                    <p>&copy; Политехнический колледж городского хозяйства, 2024. Все права защищены.</p>
                </div>
            </div>
        </body>
    </html>";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($email, $subject, $message, $headers);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $DB = db_connect();
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT) or Error("Неверный идентификатор пользователя");

    $result = mysqli_fetch_assoc(db_query($DB, "SELECT * FROM user WHERE user_id = ?", [$user_id], "i"));
    session_start();
    if ($result) {
        if (send_confirmation_email($result)) {
            $_SESSION['email_sent_success'] = "Письмо для подтверждения было отправлено на ваш email";
        } else {
            $_SESSION['email_sent_error'] = "Произошла ошибка при отправке письма.";
        }
    } else {
        $_SESSION['email_sent_error'] = "Пользователь не найден.";
    }
    mysqli_close($DB);
    header("Location: /profile.php?id=$user_id");
}
