<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}
require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/models/userModel.php';

$dbConn = getConnection();
$userModel = new UserModel();

// Obtener y validar datos
$data = [
    'credential_number' => $_POST['userDocument'] ?? '',
    'first_name' => $_POST['userName'] ?? '',
    'last_name' => $_POST['lastnameUser'] ?? '',
    'date_of_birth' => $_POST['dob'] ?? '',
    'phone' => $_POST['userPhone'] ?? '',
    'address' => $_POST['addressUser'] ?? ''
];

// Validar campos obligatorios
if (empty($data['credential_number']) || empty($data['first_name']) || 
    empty($data['last_name']) || empty($data['date_of_birth']) || 
    empty($data['phone']) || empty($data['address'])) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Todos los campos son obligatorios.'
    ]);
    exit;
}

try {
    // Actualizar en la BD
    $success = $userModel->completeProfile($data);

    if ($success) {
        echo json_encode([
            'status' => 'ok',
            'msg' => 'Perfil actualizado correctamente.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'msg' => 'No se pudo guardar el perfil.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Error: ' . $e->getMessage()
    ]);
}
