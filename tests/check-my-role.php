<?php
session_start();
require_once '../app/scripts/connection.php';
require_once '../app/library/SessionManager.php';

echo "<h2>Verificación de Rol Actual</h2>";

try {
    $dbConn = getConnection();
    $sessionManager = new SessionManager($dbConn);
    
    if ($sessionManager->isLoggedIn()) {
        $user = $sessionManager->getCurrentUser();
        echo "<p>✅ <strong>Usuario logueado:</strong> " . $user['email'] . "</p>";
        echo "<p>📋 <strong>Rol actual:</strong> " . ($user['role'] ?? 'Sin rol asignado') . "</p>";
        
        if ($sessionManager->hasRole('root')) {
            echo "<p>🎉 <strong>¡Tienes permisos de root!</strong></p>";
        } else {
            echo "<p>⚠️ <strong>NO tienes permisos de root</strong></p>";
            echo "<p>Para obtener permisos de root, contacta al administrador.</p>";
        }
    } else {
        echo "<p>❌ <strong>No estás logueado</strong></p>";
        echo "<p>Ve a <a href='/?view=index&action=login'>login</a> para iniciar sesión.</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?> 