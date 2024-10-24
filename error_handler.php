<?php
function Error($message = null) {
    header("Location: /error.php?message=$message");
    exit();
}

function errorMessage($message) {
    echo "<div class='alert alert-danger m-0'>$message</div>";
}