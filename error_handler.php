<?php
function Error($message = null) {
    header("Location: /error.php?message=$message");
    exit();
}