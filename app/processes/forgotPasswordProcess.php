<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname((__DIR__))));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/controllers/ForgotPasswordController.php';

$dbConn = getConnection();
$controller = new ForgotPasswordController($dbConn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject']) && htmlspecialchars($_POST['subject'])) {
    if (htmlspecialchars($_POST['subject']) === 'requestReset') {
        $controller->requestReset();
    } elseif (htmlspecialchars($_POST['subject']) === 'resetPassword') {
        $controller->resetPassword();
    } else {
        echo json_encode([
            'status' => 'error',
            'msg' => 'Acción no válida'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido o datos incompletos'
    ]);
} 