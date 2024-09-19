<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" type="text/css" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<style>
    body {
        background-color: #79d491;
    }

    table {
        border-collapse: collapse;
    }

    th {
        margin: auto;
        text-align: center;
        padding: 0 1rem;
    }

    td {
        text-align: center;
    }

    th, td {
        max-width: 150px;
        border: 2px solid black;
    }

    #table {
        overflow: auto;
        width: calc(100% - 2 * 1rem);
        margin: 0 1rem;
    }
</style>
<body>
    <h1 class="d-flex justify-content-center">Личный кабинет</h1>
    <div class="row mx-0">
        <div class="col-sm-5 col-md-3 col-lg-2">
            <img width="250px" height="300px" src="avatar.png">
        </div>
        <?php
        echo "<h2 class='col-sm-7 col-md-9 col-lg-10 m-auto'>";
        require_once "db_connect.php";
        $DB = db_connect();
        $result = mysqli_fetch_assoc(db_query($DB, "SELECT * FROM user WHERE user_id = $_GET[id];"));
        $user_qualification = mysqli_fetch_assoc(db_query($DB,"SELECT qualification_name FROM qualification WHERE qualification_id = $result[id_qualification];"))["qualification_name"];
        $hours = date("H");
        if ($hours < 12) echo "Доброе утро";
        elseif ($hours < 18) echo "Добрый день";
        else echo "Добрый вечер";
        echo ", " . $result["user_fullname"] . "</h2>";
        echo "</div>";
        echo "<p class='mx-1 my-0 fw-bold'>Специальность: " . $user_qualification . "</p>";
        echo "<p class='mx-1 my-0'>Ваша электронная почта: " . $result["user_email"] . "</p>";
        echo "<p class='mx-1'>Ваш IP-адрес: " . $_SERVER["REMOTE_ADDR"] . "</p>";
        ?>
        <div id="table">
            <table class="m-auto">
                <thead>
                    <tr>
                        <th>Тема</th>
                        <th>Аннотация</th>
                        <th>Дипломный руководитель</th>
                        <th>Оценка</th>
                        <th>Год защиты</th>
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
        <div class="d-flex justify-content-center mt-1">
            <button class="btn btn-success">Скачать</button>
        </div>
        <div>
            <button class="btn btn-danger mx-1 mt-1">Выйти</button>
        </div>
    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>