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
    'email' => $_POST['userEmail'] ?? '',
    'userName' => $_POST['userName'] ?? '',
    'telefono' => $_POST['userPhone'] ?? '',
    'direccion' => $_POST['addressUser'] ?? ''
];

if (empty($data['userEmail']) || empty($data['userName']) ||empty($data['userPhone'] )) {
    echo json_encode([
        'estatus' => 'error',
        'msg' => 'Campos obligatorios faltantes.'
    ]);
    exit;
}

// Actualizar en la BD
$success = $userModel->updateProfile($data); // Crea esta función en tu modelo

if ($success) {
    echo json_encode([
        'estatus' => 'ok',
        'msg' => 'Perfil actualizado correctamente.'
    ]);
} else {
    echo json_encode([
        'estatus' => 'error',
        'msg' => 'No se pudo guardar el perfil.'
    ]);
}
