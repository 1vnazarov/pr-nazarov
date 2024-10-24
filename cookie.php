<?php
function setCookies($id, $token, $remember) {
    if ($remember) {
        setcookie('id', $id, time() + 3600 * 24 * 60);
        setcookie('token', $token, time() + 3600 * 24 * 60);
    }
    else {
        setcookie('id', $id, time() + 3600);
        setcookie('token', $token, time() + 3600);
    }
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

function updateToken($DB, $userId) {
    $token = generateToken();
    $stmt = mysqli_prepare($DB, "UPDATE user SET user_token = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "si", $token, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $token;
}

function checkToken($DB) {
    if (!isset($_COOKIE['id']) || !isset($_COOKIE['token'])) {
        session_start();
        $_SESSION['error'] = "Пожалуйста, войдите в аккаунт снова.";
        header("Location: /index.php");
        exit();
    }

    $user_id = filter_input(INPUT_COOKIE, 'id', FILTER_VALIDATE_INT);
    $cookie_token = $_COOKIE['token'];
    
    $res = mysqli_fetch_assoc(db_query($DB, 'SELECT user_token FROM user WHERE user_id = ?', [$user_id], "i"));

    if ($cookie_token !== $res['user_token']) {
        session_start();
        $_SESSION['error'] = "Пожалуйста, войдите в аккаунт снова.";
        header("Location: /index.php");
        exit();
    }
}