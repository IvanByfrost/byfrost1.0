<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/controllers/registerController.php';

// Debug: mostrar datos recibidos
error_log("DEBUG registerProcess - POST data: " . print_r($_POST, true));
error_log("DEBUG registerProcess - REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);

$dbConn = getConnection();

$controller = new RegisterController($dbConn);

// Verificar método y subject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['subject']) && $_POST['subject'] === 'register') {
        error_log("DEBUG registerProcess - Subject válido, llamando registerUser()");
        $controller->registerUser(); 
    } else {
        error_log("DEBUG registerProcess - Subject inválido o faltante. Subject recibido: " . ($_POST['subject'] ?? 'NO DEFINIDO'));
        echo json_encode([
            'status' => 'error',
            'msg' => 'Subject inválido o faltante. Se requiere subject=register',
            'debug' => [
                'received_subject' => $_POST['subject'] ?? 'NO DEFINIDO',
                'expected_subject' => 'register',
                'post_data' => $_POST
            ]
        ]);
    }
} else {
    error_log("DEBUG registerProcess - Método no permitido: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido. Se requiere POST',
        'debug' => [
            'method' => $_SERVER['REQUEST_METHOD'],
            'expected_method' => 'POST'
        ]
    ]);
}