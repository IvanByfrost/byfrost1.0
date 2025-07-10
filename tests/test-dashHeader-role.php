<?php
require_once '../app/scripts/connection.php';
require_once '../app/library/SessionManager.php';

echo "<h2>🔍 PRUEBA DE DASHHEADER - ROL DE USUARIO</h2>";

// Inicializar SessionManager
$sessionManager = new SessionManager();

if ($sessionManager->isLoggedIn()) {
    echo "<h3>✅ Usuario logueado</h3>";
    
    // Simular el código del dashHeader
    $currentUser = $sessionManager->getCurrentUser();
    $userName = $currentUser['full_name'] ?: 'Usuario';
    $userRole = $sessionManager->getUserRole() ?: 'Sin rol asignado';
    
    echo "<h3>👤 Datos obtenidos:</h3>";
    echo "<p><strong>Nombre:</strong> " . htmlspecialchars($userName) . "</p>";
    echo "<p><strong>Rol:</strong> " . htmlspecialchars($userRole) . "</p>";
    
    echo "<h3>📝 Datos completos del usuario:</h3>";
    echo "<pre>" . print_r($currentUser, true) . "</pre>";
    
    echo "<h3>🎭 Rol específico:</h3>";
    echo "<p><strong>getUserRole():</strong> " . ($sessionManager->getUserRole() ?: 'NULL') . "</p>";
    
    // Simular la línea problemática del dashHeader
    echo "<h3>🔧 Simulación de la línea 45 del dashHeader:</h3>";
    echo "<div style='text-align: center; padding: 10px; border: 1px solid #ddd; margin: 10px 0;'>";
    echo "<strong>" . htmlspecialchars($userName) . "</strong><br>";
    echo "<small>" . ucfirst(htmlspecialchars($userRole)) . "</small>";
    echo "</div>";
    
} else {
    echo "<h3>❌ Usuario no está logueado</h3>";
    echo "<p>Para probar esta funcionalidad, primero debes iniciar sesión.</p>";
    echo "<p><a href='../app/views/index/login.php'>Ir al login</a></p>";
}

echo "<h3>🔧 Información adicional:</h3>";
echo "<p><strong>Session Status:</strong> " . session_status() . "</p>";
echo "<p><strong>Session ID:</strong> " . (session_id() ?: 'No iniciada') . "</p>";
?> 