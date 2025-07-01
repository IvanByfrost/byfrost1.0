<?php
/**
 * Test para verificar que las alertas de SweetAlert2 funcionan para sesión expirada
 */

// Simular una respuesta de sesión expirada
$response = [
    'success' => false,
    'message' => 'Sesión expirada. Por favor, inicia sesión nuevamente.',
    'redirect' => '/?view=index&action=login',
    'showSwal' => true,
    'swalConfig' => [
        'icon' => 'warning',
        'title' => '¡Sesión Expirada!',
        'text' => 'Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.',
        'confirmButtonText' => 'Entendido',
        'confirmButtonColor' => '#3085d6'
    ]
];

header('Content-Type: application/json');
echo json_encode($response);
?> 