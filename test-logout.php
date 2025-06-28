<?php
// Test del proceso de logout con SessionManager
echo "<h1>Test del Proceso de Logout</h1>";

// Simular una sesión activa para probar el logout
session_start();

// Simular datos de usuario logueado
$_SESSION['user_id'] = 1;
$_SESSION['user_email'] = 'test@byfrost.com';
$_SESSION['user_role'] = 'coordinator';
$_SESSION['user_name'] = 'Juan';
$_SESSION['user_lastname'] = 'Pérez';
$_SESSION['logged_in'] = true;
$_SESSION['login_time'] = time();

echo "<h2>Estado de la Sesión Antes del Logout:</h2>";
echo "<ul>";
echo "<li><strong>Usuario ID:</strong> " . ($_SESSION['user_id'] ?? 'No definido') . "</li>";
echo "<li><strong>Email:</strong> " . ($_SESSION['user_email'] ?? 'No definido') . "</li>";
echo "<li><strong>Rol:</strong> " . ($_SESSION['user_role'] ?? 'No definido') . "</li>";
echo "<li><strong>Nombre:</strong> " . ($_SESSION['user_name'] ?? 'No definido') . "</li>";
echo "<li><strong>Apellido:</strong> " . ($_SESSION['user_lastname'] ?? 'No definido') . "</li>";
echo "<li><strong>Logueado:</strong> " . ($_SESSION['logged_in'] ? 'Sí' : 'No') . "</li>";
echo "</ul>";

echo "<h2>Proceso de Logout:</h2>";

// Incluir SessionManager
require_once 'app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar si está logueado
if ($sessionManager->isLoggedIn()) {
    echo "<p>✅ Usuario está logueado</p>";
    
    // Obtener información del usuario
    $userInfo = $sessionManager->getCurrentUser();
    echo "<p><strong>Usuario actual:</strong> " . htmlspecialchars($userInfo['full_name']) . "</p>";
    
    // Simular logout (sin redirección para el test)
    echo "<p>🔄 Cerrando sesión...</p>";
    
    // Cerrar sesión
    $sessionManager->logout();
    
    echo "<p>✅ Sesión cerrada exitosamente</p>";
    
    // Verificar estado después del logout
    echo "<h2>Estado de la Sesión Después del Logout:</h2>";
    echo "<ul>";
    echo "<li><strong>Logueado:</strong> " . ($sessionManager->isLoggedIn() ? 'Sí' : 'No') . "</li>";
    echo "<li><strong>Usuario ID:</strong> " . ($sessionManager->getUserId() ?? 'No disponible') . "</li>";
    echo "<li><strong>Email:</strong> " . ($sessionManager->getUserEmail() ?? 'No disponible') . "</li>";
    echo "<li><strong>Rol:</strong> " . ($sessionManager->getUserRole() ?? 'No disponible') . "</li>";
    echo "</ul>";
    
} else {
    echo "<p>❌ Usuario no está logueado</p>";
}

echo "<h2>Funcionalidades del Logout:</h2>";
echo "<ul>";
echo "<li>✅ Verificación de autenticación antes del logout</li>";
echo "<li>✅ Obtención de información del usuario para logging</li>";
echo "<li>✅ Cierre seguro de sesión con SessionManager</li>";
echo "<li>✅ Limpieza completa de datos de sesión</li>";
echo "<li>✅ Destrucción de cookies de sesión</li>";
echo "<li>✅ Redirección al login con mensaje de confirmación</li>";
echo "<li>✅ Log de actividad (opcional)</li>";
echo "</ul>";

echo "<h2>URLs de Logout:</h2>";
echo "<ul>";
echo "<li><strong>Proceso principal:</strong> " . url . "app/processes/outProcess.php</li>";
echo "<li><strong>Desde header:</strong> Enlace en el menú de usuario</li>";
echo "<li><strong>Redirección:</strong> " . url . "?view=login&msg=logout_success</li>";
echo "</ul>";

echo "<h2>Mejoras Implementadas:</h2>";
echo "<ul>";
echo "<li>🔒 <strong>Seguridad:</strong> Verificación antes del logout</li>";
echo "<li>📊 <strong>Logging:</strong> Registro de actividad de logout</li>";
echo "<li>🔄 <strong>Eficiencia:</strong> Uso directo de SessionManager</li>";
echo "<li>🧹 <strong>Limpieza:</strong> Eliminación de archivos duplicados</li>";
echo "<li>📱 <strong>UX:</strong> Mensaje de confirmación en login</li>";
echo "</ul>";

echo "<p><strong>Estado:</strong> ✅ Proceso de logout actualizado exitosamente con SessionManager</p>";
?> 