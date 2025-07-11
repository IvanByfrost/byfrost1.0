<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/controllers/LoginController.php';

$dbConn = getConnection();

$controller = new LoginController($dbConn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->authUser(); 
} else {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido'
    ]);
}