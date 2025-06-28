<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar si el usuario está logueado antes de hacer logout
if ($sessionManager->isLoggedIn()) {
    // Obtener información del usuario antes de cerrar sesión (para logging)
    $userInfo = $sessionManager->getCurrentUser();
    
    // Cerrar sesión de forma segura
    $sessionManager->logout();
    
    // Log de logout (opcional)
    error_log("Usuario {$userInfo['full_name']} (ID: {$userInfo['id']}) cerró sesión exitosamente");
}

// Redirigir al login con mensaje de confirmación
$loginUrl = url . "?view=login&msg=logout_success";
header("Location: " . $loginUrl);
exit;