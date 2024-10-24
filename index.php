<!DOCTYPE html>
<html lang="ru">

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
    <?php
    require_once "error_handler.php";
    require_once "session.php";
    session_start();
    if (filter_input(INPUT_COOKIE, "id") && filter_input(INPUT_COOKIE, "token")) {
        header("Location: /profile.php");
        exit();
    }
    $remaining = checkForBlock();
    ?>
    <main class="container my-5 min-vh-100">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-dark">
                    <div class="card-body">
                        <h3 class="text-center text-white mb-4">Авторизация</h3>
                        <?php if ($remaining) errorMessage("Вы заблокированы. Осталось времени: " . ceil($remaining / 60) . " минут(ы)."); ?>
                        <form action="" method="POST" id="authForm" class="needs-validation"
                            novalidate enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="email" class="text-white">Адрес электронной почты</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Адрес электронной почты" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="password" class="text-white">Пароль</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Пароль" required>
                            </div>
                            <div class="form-check mt-3">
                                <input type="checkbox" class="form-check-input" id="check" name="remember">
                                <label class="form-check-label text-white" for="check">Оставаться на сайте</label>
                            </div>
                    </div>
                    </fieldset>
                    <div class="d-flex justify-content-center gap-2 mb-3 d-flex-md-column d-flex-row flex-wrap">
                        <a href="register.php"><input type="button" class="btn btn-light"
                                value="Зарегистрироваться"></a>
                                <input type="submit" class="btn btn-success" name="submit" value="Войти" <?= $remaining ? 'disabled' : '' ?>>
                        <input type="button" class="btn btn-light" value="Забыли пароль">
                    </div>
                    <?php
                    if (isset($_SESSION['error'])) {
                        errorMessage($_SESSION['error']);
                        unset($_SESSION['error']);
                    }
                    ?>
                    </form>
    </main>
    <?php
    if (isset($_POST['submit'])) {
        require_once "db_connect.php";
        require_once "cookie.php";
        $DB = db_connect();
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL) or Error("Неверный email");
        $result = mysqli_fetch_assoc(db_query($DB, "SELECT user_id, user_password FROM user WHERE user_email = ?", [$email], 's'));
        if ($result && password_verify(htmlspecialchars($_POST["password"]), $result["user_password"])) {
            // Сбросить счетчик неудачных попыток при успешном входе
            unset($_SESSION['login_attempts']);
            unset($_SESSION['block_time']);

            $token = updateToken($DB, $result['user_id']);
            setCookies($result['user_id'], $token, isset($_POST['remember']));
            header("Location: /profile.php");
            exit();
        } else {
            // Инициализация счетчика попыток, если не установлен
            if (!isset($_SESSION['login_attempts'])) {
                $_SESSION['login_attempts'] = [];
            }

            // Добавить время текущей попытки
            $_SESSION['login_attempts'][] = time();

            // Фильтрация попыток, оставляем только последние 5 минут
            $_SESSION['login_attempts'] = array_filter(
                $_SESSION['login_attempts'],
                function($timestamp) {
                    return time() - $timestamp < 300;
                }
            );

            // Если попыток за последние 5 минут >= 3, устанавливаем время блокировки
            if (count($_SESSION['login_attempts']) >= 3) {
                $_SESSION['block_time'] = time();
                $_SESSION['error'] = 'Слишком много неудачных попыток. Вы заблокированы на 10 минут.';
            } else {
                $_SESSION['error'] = 'Не удалось войти в аккаунт. Проверьте правильность введенных данных.';
            }

            header("Location: /index.php");
            exit();
        }
    }
    ?>
    <script src="js/footer.js"></script>
</body>

</html>