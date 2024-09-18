<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Регистрация</title>
</head>

<body class="bg-light">
    <header class="bg-dark py-3">
        <h1 class="text-center text-white">Санкт-Петербургское государственное бюджетное профессиональное
            образовательное учреждение "Политехнический колледж городского хозяйства"</h1>
    </header>

    <main class="container my-5 min-vh-100">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-dark">
                    <div class="card-body">
                        <h3 class="text-center text-white mb-4">Регистрация</h3>
                        <form action="create_user.php" method="POST" id="registerForm" class="needs-validation" novalidate
                            enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="fullname" class="text-white">ФИО</label>
                                <input type="text" name="fullname" id="fullname" class="form-control"
                                    placeholder="Фамилия Имя Отчество"
                                    pattern="^[А-ЯЁ][а-яё]+(?:-[А-ЯЁ][а-яё]+)?\s[А-ЯЁ][а-яё]+(?:-[А-ЯЁ][а-яё]+)?(?:\s[А-ЯЁ][а-яё]+(?:-[А-ЯЁ][а-яё]+)?)?$"
                                    required>
                                <div class="invalid-feedback">Введите корректные фамилию, имя и отчество (последнее -
                                    при наличии).</div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="email" class="text-white">Электронная почта</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Электронная почта"
                                    pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$" required>
                                <div class="invalid-feedback">Введите корректный адрес электронной почты.</div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="password" class="text-white">Пароль</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Придумайте пароль"
                                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$" required>
                                <div class="invalid-feedback">Пароль должен содержать минимум 8 символов, одну заглавную
                                    букву, одну строчную букву и одну цифру.</div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="qualification" class="text-white">Специальность</label>
                                <select name="qualification" id="qualification" class="form-control" required>
                                    <option value="">Выберите специальность</option>
                                    <?php
                                    require_once "db_connect.php";
                                    $DB = db_connect();
                                    $result = mysqli_query($DB, "SELECT * FROM qualification;");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='$row[qualification_id]'>$row[qualification_name]</option>";
                                    }
                                    mysqli_close($DB);
                                    ?>
                                </select>
                                <div class="invalid-feedback">Выберите специальность.</div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="avatar" class="text-white">Фото</label>
                                <input type="file" name="avatar" id="avatar" class="form-control-file" accept="image/*"
                                    required>
                                <div class="invalid-feedback">Загрузите фото.</div>
                            </div>
                            <div class="d-flex mt-3">
                                <button type="submit"
                                    class="btn btn-success m-auto justify-content-center">Зарегистрироваться</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script src="js/footer.js"></script>
    <script src="js/validateForms.js"></script>
</body>

</html>