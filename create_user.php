<?php
require_once "db_connect.php";
$DB = db_connect();
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
//die("INSERT INTO user (user_fullname, user_email, user_password, id_qualification) VALUES ('$_POST[fullname]', '$_POST[email]', '$password_hash', $_POST[qualification]);");
if (db_query($DB, "INSERT INTO user (user_fullname, user_email, user_password, id_qualification) VALUES ('$_POST[fullname]', '$_POST[email]', '$password_hash', $_POST[qualification]);")) {
    $qalification_result = mysqli_fetch_assoc(db_query($DB, "SELECT qualification_name FROM qualification WHERE qualification_id = $_POST[qualification];"));
    mysqli_close($DB);
    header("Location: profile.php?fullname=$_POST[fullname]&email=$_POST[email]&qualification=$qalification_result[qualification_name]&email=$_POST[email]");
}
?>