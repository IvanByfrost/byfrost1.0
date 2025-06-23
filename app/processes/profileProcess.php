<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}
require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/models/userModel.php';

$dbConn = getConnection();
$userModel = new userModel($dbConn);

// Obtener y validar
$data = [
    'userDocument' => $_POST['userDocument'] ?? '',
    'userName' => $_POST['userName'] ?? '',
    'lastnameUser' => $_POST['lastnameUser'] ?? '',
    'dob' => $_POST['dob'] ?? '',
    'userPhone' => $_POST['userPhone'] ?? '',
    'addressUser' => $_POST['addressUser'] ?? ''
];

if (empty($data['userDocument']) || empty($data['userName']) || empty($data['lastnameUser']) || empty($data['dob']) || empty($data['userPhone']) || empty($data['addressUser'])) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Campos obligatorios faltantes.'
    ]);
    exit;
}

// Actualizar en la BD
$success = $userModel->completeProfile($data); // Crea esta función en tu modelo

if ($success) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'ok',
        'msg' => 'Perfil actualizado correctamente.'
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'msg' => 'No se pudo guardar el perfil.'
    ]);
}
