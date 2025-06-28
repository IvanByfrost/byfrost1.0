<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/controllers/SchoolController.php';

$dbConn = getConnection();

$controller = new SchoolController($dbConn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject']) && $_POST['subject'] === 'createSchool') {
    $controller->createSchool(); 
} else {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido o datos incompletos'
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject']) && $_POST['subject'] === 'consultSchool') {
    $controller->consultSchool(); 
} else {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido o datos incompletos'
    ]);
}