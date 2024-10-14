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
    $message = htmlspecialchars(urldecode(isset($_GET['message']) ? $_GET['message'] : "Сообщение об ошибке не предоставлено"));
    ?>
    <main class='fs-3 container min-vh-100 flex-wrap d-flex justify-content-center'>
        <div class='alert alert-danger w-100'>
            <div class='row d-flex justify-content-center'>
                <p class="justify-content-center d-flex flex-grow-1 text-center"><span class='fw-bold'>Ошибка: </span><?= $message ?></p>
                <a class="justify-content-end d-flex w-auto" href="javascript:history.go(-1)">Назад</a>
            </div>
        </div>
    </main>

    <script src="js/footer.js"></script>

</body>

</html>