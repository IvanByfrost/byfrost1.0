<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/controllers/registerController.php';

$dbConn = getConnection();
$controller = new RegisterController($dbConn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject'])) {
    if ($_POST['subject'] === 'completeProfile') {
        $controller->completeProfile();
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
