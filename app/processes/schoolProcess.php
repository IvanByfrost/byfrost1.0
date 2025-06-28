<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/controllers/SchoolController.php';

$dbConn = getConnection();
$controller = new SchoolController($dbConn);

// Manejar peticiones POST para crear escuela
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['school_name'])) {
    $controller->createSchool(); 
    return;
}

// Manejar peticiones POST para consultar escuela
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['nit']) || isset($_POST['school_name']) || isset($_POST['codigoDANE']) || isset($_POST['search']))) {
    $controller->consultSchool(); 
    return;
}

// Si no es una petición válida
echo json_encode([
    'status' => 'error',
    'msg' => 'Método no permitido o datos incompletos'
]);