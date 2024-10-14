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
    $message = "<h1>Здравствуйте!</h1>
    <p>Пожалуйста, подтвердите ваш адрес электронной почты, перейдя по следующей</p>
    <a href='$url'>ссылке</a>
    <p>Если вы не регистрировались на нашем сайте, просто проигнорируйте это письмо.</p>";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($email, $subject, $message, $headers);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $DB = db_connect();
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT) or Error("Неверный идентификатор пользователя");

    $result = mysqli_fetch_assoc(db_query($DB, "SELECT * FROM user WHERE user_id = ?", [$user_id], "i"));
    ?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <title>Практическая работа</title>
    </head>

    <body class="bg-light">
        <header class="bg-dark py-3">
            <h1 class="text-center text-white">Санкт-Петербургское государственное бюджетное профессиональное
                образовательное учреждение "Политехнический колледж городского хозяйства"</h1>
        </header>
        <main class="container my-5 min-vh-100">
            <?php
            if ($result) {
                if (send_confirmation_email($result)) { ?>
                    <div class="alert alert-success text-center" role="alert">
                        Письмо для подтверждения было отправлено на ваш email
                    </div> <?php
                        } else { ?>
                    <div class="alert alert-danger text-center" role="alert">
                        Произошла ошибка при отправке письма.
                    </div> <?php
                        }
                    } else { ?>
                <div class="alert alert-danger text-center" role="alert">
                    Пользователь не найден.
                </div> <?php
                    }?>
        </main>
        <script src="js/footer.js"></script>

    </body>
<?php
    mysqli_close($DB);
}
