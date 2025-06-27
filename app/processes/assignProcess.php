<?php
require_once '../app/models/UserModel.php';
$model = new userModel();

$userId = $_POST['userId'];
$roleId = $_POST['roleId'];

$ok = $model->assignRole($userId, $roleId);

echo json_encode([
    'status' => $ok ? 'ok' : 'error',
    'msg' => $ok ? 'Rol asignado con éxito' : 'Error al asignar rol'
]);



?>