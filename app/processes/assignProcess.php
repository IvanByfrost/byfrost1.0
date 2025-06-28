<?php
// Headers para CORS y JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

require_once '../app/models/UserModel.php';
require_once '../app/scripts/connection.php';

// Obtener conexión a la base de datos
$dbConn = getConnection();
$model = new UserModel($dbConn);

$userId = $_POST['user_id'] ?? null;
$roleType = $_POST['role_type'] ?? null;

if (!$userId || !$roleType) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos requeridos'
    ]);
    exit;
}

try {
    $ok = $model->assignRole($userId, $roleType);
    
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Rol asignado con éxito' : 'Error al asignar rol'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

try {
    $ok = $model->searchUsersByDocument($credentialType, $credentialNumber);
    
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Usuario encontrado' : 'Error al asignar rol'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>