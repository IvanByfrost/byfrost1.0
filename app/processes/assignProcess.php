<?php
require_once '../app/models/userModel.php';
$db = getConnection();
$model = new userModel($db);

$userId = $_POST['userId'];
$roleId = $_POST['roleId'];

$ok = $model->assignRole($userId, $roleId);

echo json_encode([
    'status' => $ok ? 'ok' : 'error',
    'msg' => $ok ? 'Rol asignado con éxito' : 'Error al asignar rol'
]);
?>