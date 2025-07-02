<?php
session_start();
require_once '../app/scripts/connection.php';
require_once '../app/library/SessionManager.php';

echo "<h3>Verificación Rápida de Rol</h3>";

try {
    $dbConn = getConnection();
    $sessionManager = new SessionManager($dbConn);
    
    if ($sessionManager->isLoggedIn()) {
        $user = $sessionManager->getCurrentUser();
        echo "<p><strong>Usuario:</strong> " . $user['email'] . "</p>";
        echo "<p><strong>Rol actual:</strong> " . ($user['role'] ?? 'Sin rol') . "</p>";
        
        if ($sessionManager->hasRole('root')) {
            echo "<p style='color: green;'><strong>✅ Tienes permisos de root</strong></p>";
        } else {
            echo "<p style='color: red;'><strong>❌ NO tienes permisos de root</strong></p>";
            echo "<p>Para obtener permisos de root, necesitas:</p>";
            echo "<ol>";
            echo "<li>Contactar al administrador del sistema</li>";
            echo "<li>O usar una cuenta que ya tenga rol de root</li>";
            echo "<li>O asignar el rol manualmente en la base de datos</li>";
            echo "</ol>";
        }
    } else {
        echo "<p style='color: red;'><strong>❌ No estás logueado</strong></p>";
        echo "<p><a href='/?view=index&action=login'>Ir al login</a></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?> 