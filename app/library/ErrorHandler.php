<?php
/**
 * Global Error Handler - ByFrost
 */

function handleError($errno, $errstr, $errfile, $errline) {
    $errorMessage = date("Y-m-d H:i:s") . " - Error: [$errno] $errstr in $errfile on line $errline\n";
    error_log($errorMessage, 3, "app/logs/validation_errors.log");
    
    if (ini_get("display_errors")) {
        echo "Error: $errstr";
    }
    
    return true;
}

function handleException($exception) {
    $errorMessage = date("Y-m-d H:i:s") . " - Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";
    error_log($errorMessage, 3, "app/logs/validation_errors.log");
    
    $_SESSION["error"] = "Ha ocurrido un error inesperado. Por favor, inténtelo de nuevo.";
    header("Location: " . url . "error");
    exit();
}

set_error_handler("handleError");
set_exception_handler("handleException");
?>