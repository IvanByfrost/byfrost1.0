<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/controllers/LoginController.php';

$dbConn = getConnection();

$controller = new LoginController($dbConn);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset(_POST['subject']) && htmlspecialchars(_POST['subject']) && htmlspecialchars($_POST['subject']) === 'login') {
    $controller->authUser(); 
} else {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido o datos incompletos'
    ]);
}