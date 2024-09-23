<?php
function customErrorHandler($errno = null, $errstr = null, $errfile = null, $errline = null) {
    // Если ошибка не передана, проверяем фатальные ошибки через error_get_last()
    if ($errno === null) {
        $error = error_get_last();
        if ($error) {
            $errno = $error['type'];
            $errstr = $error['message'];
            $errfile = $error['file'];
            $errline = $error['line'];
        } else {
            return;
        }
    }

    $errorLevel = match ($errno) {
        E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR => "danger",
        E_WARNING, E_USER_WARNING => "warning",
        E_NOTICE, E_USER_NOTICE => "info",
        default => "secondary"
    };
    echo "
    <div class='container flex-wrap d-flex justify-content-center'>
        <div class='alert alert-$errorLevel mt-3'>
            <div class='row'>
                <p><span class='fw-bold'>Error [$errno]:</span> $errstr</p>
            </div>
            <div class='row'>
                <p><span class='fw-bold'>File:</span> $errfile</p>
            </div>
            <div class='row'>
                <p><span class='fw-bold'>Line:</span> $errline</p>
            </div>
        </div>
    </div>";
    exit();
}
error_reporting(0);
register_shutdown_function('customErrorHandler');
set_error_handler("customErrorHandler");
?>
