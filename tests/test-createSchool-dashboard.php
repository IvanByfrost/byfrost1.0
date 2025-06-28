<?php
/**
 * Test para verificar que createSchool funciona dentro del dashboard
 */

echo "<h1>Test: CreateSchool en Dashboard</h1>";

echo "<h2>1. Verificar que el usuario esté logueado:</h2>";

// Incluir las dependencias necesarias
require_once 'config.php';
require_once 'app/library/SessionManager.php';
require_once 'app/scripts/connection.php';

// Obtener conexión a la base de datos
$dbConn = getConnection();

// Crear instancia del SessionManager
$sessionManager = new SessionManager();

if (!$sessionManager->isLoggedIn()) {
    echo "<div class='alert alert-warning'>⚠️ No estás logueado</div>";
    echo "<p>Ve a <a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a> primero</p>";
} else {
    $user = $sessionManager->getCurrentUser();
    echo "<div class='alert alert-success'>✅ Logueado como: " . $user['email'] . " (Rol: " . $user['role'] . ")</div>";
    
    if (!$sessionManager->hasAnyRole(['director', 'coordinator', 'treasurer'])) {
        echo "<div class='alert alert-danger'>❌ Tu rol no tiene permisos para crear escuelas</div>";
    } else {
        echo "<div class='alert alert-success'>✅ Tienes permisos para crear escuelas</div>";
    }
}

echo "<h2>2. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><a href='http://localhost:8000/?view=director&action=dashboard' target='_blank'>Dashboard Director</a></li>";
echo "<li><a href='http://localhost:8000/?view=coordinator&action=dashboard' target='_blank'>Dashboard Coordinador</a></li>";
echo "</ul>";

echo "<h2>3. Pasos para probar:</h2>";
echo "<ol>";
echo "<li>Ve a uno de los dashboards de arriba</li>";
echo "<li>Haz clic en 'Registrar Colegio' en el sidebar</li>";
echo "<li>Llena el formulario</li>";
echo "<li>Envía el formulario</li>";
echo "<li>Deberías permanecer en el dashboard</li>";
echo "</ol>";

echo "<h2>4. Verificar archivos:</h2>";
echo "<ul>";
echo "<li><strong>createSchool.js:</strong> " . (file_exists('app/resources/js/createSchool.js') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "<li><strong>createSchool.php:</strong> " . (file_exists('app/views/school/createSchool.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "<li><strong>SchoolController.php:</strong> " . (file_exists('app/controllers/SchoolController.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "<li><strong>MainController.php:</strong> " . (file_exists('app/controllers/MainController.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "</ul>";

echo "<h2>5. Verificar métodos en MainController:</h2>";
if (file_exists('app/controllers/MainController.php')) {
    $content = file_get_contents('app/controllers/MainController.php');
    echo "<ul>";
    echo "<li><strong>isAjaxRequest():</strong> " . (strpos($content, 'isAjaxRequest') !== false ? '✅ Existe' : '❌ No existe') . "</li>";
    echo "<li><strong>sendJsonResponse():</strong> " . (strpos($content, 'sendJsonResponse') !== false ? '✅ Existe' : '❌ No existe') . "</li>";
    echo "</ul>";
}

echo "<h2>6. Verificar JavaScript:</h2>";
if (file_exists('app/resources/js/createSchool.js')) {
    $jsContent = file_get_contents('app/resources/js/createSchool.js');
    echo "<ul>";
    echo "<li><strong>X-Requested-With header:</strong> " . (strpos($jsContent, 'X-Requested-With') !== false ? '✅ Existe' : '❌ No existe') . "</li>";
    echo "<li><strong>loadView function:</strong> " . (strpos($jsContent, 'loadView') !== false ? '✅ Existe' : '❌ No existe') . "</li>";
    echo "</ul>";
}

echo "<h2>7. Problemas comunes:</h2>";
echo "<ul>";
echo "<li><strong>No estás logueado:</strong> Ve a login primero</li>";
echo "<li><strong>Rol incorrecto:</strong> Necesitas director, coordinator o treasurer</li>";
echo "<li><strong>JavaScript no carga:</strong> Verifica la consola del navegador</li>";
echo "<li><strong>Error 403:</strong> Problema de permisos</li>";
echo "<li><strong>Error 404:</strong> Archivo no encontrado</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Verificando configuración...</p>";
?> 