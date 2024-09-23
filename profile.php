<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <style>
        table {
            border-collapse: collapse;
        }

        .profile-image {
            max-width: 100%;
            height: auto;
        }

        .table-responsive {
            overflow: auto;
        }
    </style>
</head>

<body class="bg-dark">
    <?php
    require_once "error_handler.php";
    require_once "db_connect.php";
    $DB = db_connect();
    $user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) or Error(E_USER_ERROR, "Неверный идентификатор пользователя");
    $result = mysqli_fetch_assoc(db_query($DB, "SELECT * FROM user WHERE user_id = ?;", [$user_id], 'i'));
    mysqli_close($DB);
    ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <h1 class="text-center text-white mb-4">Личный кабинет</h1>
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <?php
                        $avatar = $result["user_avatar"] or "avatar.png";
                        echo "<img src=$avatar alt='Avatar' class='profile-image img-fluid mx-auto d-block'>";
                        ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?php
                        echo "<h2 class='text-center text-md-start text-white'>";
                        $DB = db_connect();
                        $user_qualification = mysqli_fetch_assoc(db_query($DB, "SELECT qualification_name FROM qualification WHERE qualification_id = ?;", [$result["id_qualification"]], "i"))["qualification_name"];
                        $hours = date("H");
                        if ($hours < 12) echo "Доброе утро";
                        elseif ($hours < 18) echo "Добрый день";
                        else echo "Добрый вечер";
                        echo ", " . $result["user_fullname"] . "</h2>";
                        echo "<p class='text-white fw-bold'>Специальность: " . $user_qualification . "</p>";
                        echo "<p class='text-white'>Электронная почта: " . $result["user_email"] . "</p>";
                        echo "<p class='text-white'>IP-адрес: " . $_SERVER["REMOTE_ADDR"] . "</p>";
                        ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table border border-dark text-center align-middle m-0">
                        <thead>
                            <tr>
                                <th scope="col">Тема</th>
                                <th scope="col">Аннотация</th>
                                <th scope="col">Дипломный руководитель</th>
                                <th scope="col">Оценка</th>
                                <th scope="col">Год защиты</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Система учета дипломов</td>
                                <td>Много букаф бла-бла</td>
                                <td>Ильюшенков Л.В.</td>
                                <td>2</td>
                                <td>2024</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-3">
                    <button class="btn btn-success">Скачать</button>
                    <button class="btn btn-danger">Выйти</button>
                </div>
            </div>
        </div>
    </div>
    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>