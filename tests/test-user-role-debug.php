<?php
require_once '../app/scripts/connection.php';
require_once '../app/library/SessionManager.php';

echo "<h2>🔍 DIAGNÓSTICO DE SESIÓN Y ROL DE USUARIO</h2>";

// Inicializar SessionManager
$sessionManager = new SessionManager();

echo "<h3>📊 Estado de la sesión:</h3>";
echo "<p><strong>¿Está logueado?</strong> " . ($sessionManager->isLoggedIn() ? '✅ Sí' : '❌ No') . "</p>";

if ($sessionManager->isLoggedIn()) {
    echo "<h3>👤 Datos del usuario actual:</h3>";
    $currentUser = $sessionManager->getCurrentUser();
    echo "<pre>" . print_r($currentUser, true) . "</pre>";
    
    echo "<h3>🎭 Rol específico:</h3>";
    echo "<p><strong>getUserRole():</strong> " . ($sessionManager->getUserRole() ?: 'NULL') . "</p>";
    
    echo "<h3>📝 Variables de sesión:</h3>";
    echo "<pre>";
    if (isset($_SESSION)) {
        print_r($_SESSION);
    } else {
        echo "❌ No hay variables de sesión disponibles";
    }
    echo "</pre>";
    
    echo "<h3>🔍 Verificación de roles:</h3>";
    $roles = ['admin', 'director', 'teacher', 'student', 'parent', 'coordinator', 'treasurer'];
    foreach ($roles as $role) {
        $hasRole = $sessionManager->hasRole($role);
        echo "<p><strong>hasRole('$role'):</strong> " . ($hasRole ? '✅ Sí' : '❌ No') . "</p>";
    }
    
} else {
    echo "<h3>❌ Usuario no está logueado</h3>";
    echo "<p>Redirige al login para probar la funcionalidad.</p>";
}

echo "<h3>🔧 Información del sistema:</h3>";
echo "<p><strong>PHP Session Status:</strong> " . session_status() . "</p>";
echo "<p><strong>Session ID:</strong> " . (session_id() ?: 'No iniciada') . "</p>";
echo "<p><strong>Session Name:</strong> " . session_name() . "</p>";
?> 