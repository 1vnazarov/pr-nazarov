<?php
require_once "db_connect.php";
$DB = db_connect();
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
if (db_query($DB, "INSERT INTO user (user_fullname, user_email, user_password, id_qualification) VALUES ('$_POST[fullname]', '$_POST[email]', '$password_hash', $_POST[qualification]);")) {
    $user_id = mysqli_insert_id($DB);
    $qalification_result = mysqli_fetch_assoc(db_query($DB, "SELECT qualification_name FROM qualification WHERE qualification_id = $_POST[qualification];"));
    header("Location: profile.php?id=$user_id");
    mysqli_close($DB);
}
?>