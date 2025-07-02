<?php
// Simular el error de permisos que estás viendo
header('Content-Type: application/json; charset=utf-8');

// Simular que el usuario no tiene permisos de root
$response = [
    'success' => false,
    'message' => 'No tienes permisos para realizar esta acción. Necesitas rol de root.',
    'data' => []
];

echo json_encode($response);
exit;
?> 