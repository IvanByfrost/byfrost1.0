<?php
/**
 * Test para verificar el estado de la sesión
 * 
 * Este script verifica:
 * 1. Si el usuario está logueado
 * 2. Qué rol tiene el usuario
 * 3. Si puede acceder a createSchool
 */

echo "<h1>Test: Estado de Sesión</h1>";

// Incluir las dependencias necesarias
require_once 'config.php';
require_once 'app/library/SessionManager.php';
require_once 'app/scripts/connection.php';

// Obtener conexión a la base de datos
$dbConn = getConnection();

// Crear instancia del SessionManager
$sessionManager = new SessionManager();

echo "<h2>1. Estado de la sesión:</h2>";
echo "<ul>";
echo "<li><strong>¿Está logueado?</strong> " . ($sessionManager->isLoggedIn() ? '✅ Sí' : '❌ No') . "</li>";

if ($sessionManager->isLoggedIn()) {
    $user = $sessionManager->getCurrentUser();
    echo "<li><strong>ID del usuario:</strong> " . $user['id'] . "</li>";
    echo "<li><strong>Email:</strong> " . $user['email'] . "</li>";
    echo "<li><strong>Rol:</strong> " . $user['role'] . "</li>";
    echo "<li><strong>Nombre completo:</strong> " . $user['full_name'] . "</li>";
    
    echo "<h3>Verificación de roles:</h3>";
    echo "<ul>";
    echo "<li><strong>¿Es director?</strong> " . ($sessionManager->hasRole('director') ? '✅ Sí' : '❌ No') . "</li>";
    echo "<li><strong>¿Es coordinador?</strong> " . ($sessionManager->hasRole('coordinator') ? '✅ Sí' : '❌ No') . "</li>";
    echo "<li><strong>¿Es tesorero?</strong> " . ($sessionManager->hasRole('treasurer') ? '✅ Sí' : '❌ No') . "</li>";
    echo "<li><strong>¿Tiene rol permitido?</strong> " . ($sessionManager->hasAnyRole(['director', 'coordinator', 'treasurer']) ? '✅ Sí' : '❌ No') . "</li>";
    echo "</ul>";
} else {
    echo "<li><strong>Usuario:</strong> No logueado</li>";
}
echo "</ul>";

echo "<h2>2. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost/byfrost/?view=school&action=createSchool' target='_blank'>Crear Escuela</a></li>";
echo "<li><a href='http://localhost/byfrost/?view=school&action=consultSchool' target='_blank'>Consultar Escuelas</a></li>";
echo "<li><a href='http://localhost/byfrost/?view=coordinator&action=dashboard' target='_blank'>Dashboard Coordinador</a></li>";
echo "<li><a href='http://localhost/byfrost/?view=director&action=dashboard' target='_blank'>Dashboard Director</a></li>";
echo "</ul>";

echo "<h2>3. Roles requeridos para SchoolController:</h2>";
echo "<ul>";
echo "<li>✅ <strong>director</strong></li>";
echo "<li>✅ <strong>coordinator</strong></li>";
echo "<li>✅ <strong>treasurer</strong></li>";
echo "</ul>";

echo "<h2>4. Para solucionar el problema:</h2>";

if (!$sessionManager->isLoggedIn()) {
    echo "<h3>❌ No estás logueado:</h3>";
    echo "<ol>";
    echo "<li>Ve a <a href='http://localhost/byfrost/?view=index&action=login' target='_blank'>Login</a></li>";
    echo "<li>Inicia sesión con un usuario que tenga rol de director, coordinador o tesorero</li>";
    echo "<li>Vuelve a intentar crear una escuela</li>";
    echo "</ol>";
} else {
    $user = $sessionManager->getCurrentUser();
    if (!$sessionManager->hasAnyRole(['director', 'coordinator', 'treasurer'])) {
        echo "<h3>❌ Tu rol no tiene permisos:</h3>";
        echo "<p>Tu rol actual es: <strong>" . $user['role'] . "</strong></p>";
        echo "<p>Necesitas uno de estos roles: <strong>director, coordinator, o treasurer</strong></p>";
        echo "<ol>";
        echo "<li>Contacta al administrador para cambiar tu rol</li>";
        echo "<li>O crea un usuario con rol de director/coordinador</li>";
        echo "</ol>";
    } else {
        echo "<h3>✅ Tienes permisos:</h3>";
        echo "<p>Tu rol <strong>" . $user['role'] . "</strong> tiene permisos para crear escuelas.</p>";
        echo "<p>El problema podría estar en otro lugar. Verifica:</p>";
        echo "<ul>";
        echo "<li>Que el formulario se envíe correctamente</li>";
        echo "<li>Que no haya errores en la consola del navegador</li>";
        echo "<li>Que la sesión no expire durante el envío</li>";
        echo "</ul>";
    }
}

echo "<h2>5. Información de debug:</h2>";
echo "<ul>";
echo "<li><strong>Session ID:</strong> " . session_id() . "</li>";
echo "<li><strong>Session Name:</strong> " . session_name() . "</li>";
echo "<li><strong>Session Status:</strong> " . session_status() . "</li>";
echo "<li><strong>Session Data:</strong> <pre>" . print_r($_SESSION, true) . "</pre></li>";
echo "</ul>";

echo "<h2>6. URLs de login:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost/byfrost/?view=index&action=login' target='_blank'>Login</a></li>";
echo "<li><a href='http://localhost/byfrost/?view=index&action=register' target='_blank'>Registro</a></li>";
echo "<li><a href='http://localhost/byfrost/?view=index&action=logout' target='_blank'>Logout</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Verificando sesión...</p>";
?> 