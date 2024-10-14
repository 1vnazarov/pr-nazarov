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
    session_start();
    ?>
    <main class="container my-5 min-vh-100">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-dark">
                    <div class="card-body">
                        <h3 class="text-center text-white mb-4">Авторизация</h3>
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
                                <input type="checkbox" class="form-check-input" id="check">
                                <label class="form-check-label text-white" for="check">Оставаться на сайте</label>
                            </div>
                    </div>
                    </fieldset>
                    <div class="d-flex justify-content-center gap-2 mb-3 d-flex-md-column d-flex-row flex-wrap">
                        <a href="register.php"><input type="button" class="btn btn-light"
                                value="Зарегистрироваться"></a>
                        <input type="submit" class="btn btn-success" name="submit" value="Войти">
                        <input type="button" class="btn btn-light" value="Забыли пароль">
                    </div>
                    <?php
                    if (isset($_SESSION['error'])) {
                        echo "<div class='alert alert-danger m-0'>$_SESSION[error]</div>";
                        unset($_SESSION['error']);
                    }
                    ?>
                    </form>
    </main>
    <?php
    if (isset($_POST['submit'])) {
        require_once "db_connect.php";
        $DB = db_connect();
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL) or Error("Неверный email");
        $result = mysqli_fetch_assoc(db_query($DB, "SELECT user_id, user_password FROM user WHERE user_email = ?", [$email], 's'));

        if ($result && password_verify(htmlspecialchars($_POST["password"]), $result["user_password"])) {
            header("Location: profile.php?id=$result[user_id]");
        } else {
            $_SESSION['error'] = 'Не удалось войти в аккаунт. Проверьте правильность введенных данных';
            header("Location: index.php");
        }
    }
    ?>
    <script src="js/footer.js"></script>
</body>

</html>