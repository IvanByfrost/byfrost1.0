<?php
define('ROOT', __DIR__ . '/..');
require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

echo "<h1>Test de Permisos del Director</h1>";

// Verificar si está logueado
echo "<h2>Estado de la Sesión</h2>";
echo "<ul>";
echo "<li><strong>¿Está logueado?</strong> " . ($sessionManager->isLoggedIn() ? '✅ Sí' : '❌ No') . "</li>";

if ($sessionManager->isLoggedIn()) {
    $user = $sessionManager->getCurrentUser();
    echo "<li><strong>ID del usuario:</strong> " . $user['id'] . "</li>";
    echo "<li><strong>Email:</strong> " . $user['email'] . "</li>";
    echo "<li><strong>Nombre:</strong> " . $user['full_name'] . "</li>";
    echo "<li><strong>Rol actual:</strong> " . $user['role'] . "</li>";
    
    echo "<h2>Verificación de Roles</h2>";
    echo "<ul>";
    echo "<li><strong>¿Es director?</strong> " . ($sessionManager->hasRole('director') ? '✅ Sí' : '❌ No') . "</li>";
    echo "<li><strong>¿Es root?</strong> " . ($sessionManager->hasRole('root') ? '✅ Sí' : '❌ No') . "</li>";
    echo "<li><strong>¿Es coordinador?</strong> " . ($sessionManager->hasRole('coordinator') ? '✅ Sí' : '❌ No') . "</li>";
    echo "<li><strong>¿Es tesorero?</strong> " . ($sessionManager->hasRole('treasurer') ? '✅ Sí' : '❌ No') . "</li>";
    
    echo "<h3>Verificación de hasAnyRole</h3>";
    echo "<li><strong>¿Tiene rol de director, coordinator, treasurer o root?</strong> " . 
         ($sessionManager->hasAnyRole(['director', 'coordinator', 'treasurer', 'root']) ? '✅ Sí' : '❌ No') . "</li>";
    echo "</ul>";
    
    echo "<h2>Datos de Sesión Completos</h2>";
    echo "<pre>" . print_r($_SESSION, true) . "</pre>";
} else {
    echo "<li><strong>No está logueado</strong></li>";
    echo "<p><a href='?view=login'>Ir al login</a></p>";
}
echo "</ul>";
?> 