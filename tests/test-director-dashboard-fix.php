<?php
/**
 * Test para verificar que el dashboard del director funciona correctamente
 */

echo "<h1>Test: Dashboard del Director - Verificación de Estilos</h1>";

// Incluir las dependencias necesarias
require_once '../config.php';
require_once '../app/library/SessionManager.php';
require_once '../app/scripts/connection.php';

// Obtener conexión a la base de datos
$dbConn = getConnection();

// Crear instancia del SessionManager
$sessionManager = new SessionManager();

echo "<h2>1. Estado de la Sesión</h2>";
if (!$sessionManager->isLoggedIn()) {
    echo "<div class='alert alert-warning'>⚠️ No estás logueado</div>";
    echo "<p>Ve a <a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a> primero</p>";
} else {
    $user = $sessionManager->getCurrentUser();
    echo "<div class='alert alert-success'>✅ Logueado como: " . $user['email'] . " (Rol: " . $user['role'] . ")</div>";
    
    if (!$sessionManager->hasRole('director')) {
        echo "<div class='alert alert-info'>ℹ️ Tu rol no es director, pero puedes verificar los archivos</div>";
    } else {
        echo "<div class='alert alert-success'>✅ Tienes rol de director</div>";
    }
}

echo "<h2>2. Verificación de Archivos</h2>";
echo "<ul>";

// Verificar archivos principales
$files = [
    '../app/views/director/dashboard.php' => 'Dashboard del Director',
    '../app/views/director/menuDirector.php' => 'Menú del Director',
    '../app/views/director/directorSidebar.php' => 'Sidebar del Director',
    '../app/views/layouts/dashHeader.php' => 'Header del Dashboard',
    '../app/views/layouts/dashHead.php' => 'Head del Dashboard',
    '../app/views/layouts/dashFooter.php' => 'Footer del Dashboard',
    '../app/resources/css/dashboard.css' => 'CSS del Dashboard'
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "<li>✅ <strong>$description:</strong> Existe</li>";
    } else {
        echo "<li>❌ <strong>$description:</strong> NO existe</li>";
    }
}

echo "</ul>";

echo "<h2>3. Comparación con Dashboard Root</h2>";
echo "<ul>";
echo "<li><strong>Dashboard Root:</strong> " . (file_exists('../app/views/root/dashboard.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "<li><strong>Menú Root:</strong> " . (file_exists('../app/views/root/menuRoot.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "<li><strong>Sidebar Root:</strong> " . (file_exists('../app/views/root/rootSidebar.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "</ul>";

echo "<h2>4. URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=director&action=dashboard' target='_blank'>Dashboard Director</a></li>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root (para comparar)</a></li>";
echo "<li><a href='http://localhost:8000/?view=director&action=menuDirector' target='_blank'>Menú Director</a></li>";
echo "</ul>";

echo "<h2>5. Verificación de CSS</h2>";
$cssFile = '../app/resources/css/dashboard.css';
if (file_exists($cssFile)) {
    $cssContent = file_get_contents($cssFile);
    $cssSize = strlen($cssContent);
    echo "<p>✅ CSS del dashboard existe (tamaño: $cssSize bytes)</p>";
    
    // Verificar que contiene estilos importantes
    $importantStyles = [
        '.dashboard-container' => 'Contenedor del dashboard',
        '.sidebar' => 'Sidebar',
        '.mainContent' => 'Contenido principal',
        '.card' => 'Tarjetas',
        '.btn' => 'Botones'
    ];
    
    echo "<ul>";
    foreach ($importantStyles as $style => $description) {
        if (strpos($cssContent, $style) !== false) {
            echo "<li>✅ $description: Presente</li>";
        } else {
            echo "<li>❌ $description: Ausente</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>❌ CSS del dashboard NO existe</p>";
}

echo "<h2>6. Instrucciones de Prueba</h2>";
echo "<ol>";
echo "<li>Ve al <a href='http://localhost:8000/?view=director&action=dashboard' target='_blank'>Dashboard del Director</a></li>";
echo "<li>Verifica que se carguen los estilos correctamente</li>";
echo "<li>Compara con el <a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li>Ambos deberían verse similares en términos de estilos</li>";
echo "</ol>";

echo "<h2>7. Posibles Problemas</h2>";
echo "<ul>";
echo "<li><strong>CSS no cargado:</strong> Verificar que dashboard.css se incluya correctamente</li>";
echo "<li><strong>JavaScript no cargado:</strong> Verificar que loadView.js esté disponible</li>";
echo "<li><strong>Estructura HTML incorrecta:</strong> Verificar que el HTML sea válido</li>";
echo "<li><strong>Rutas incorrectas:</strong> Verificar que las rutas a recursos sean correctas</li>";
echo "</ul>";

echo "<h2>8. Debug del Navegador</h2>";
echo "<p>Para diagnosticar problemas:</p>";
echo "<ol>";
echo "<li>Abre las herramientas de desarrollador (F12)</li>";
echo "<li>Ve a la pestaña 'Console' para ver errores de JavaScript</li>";
echo "<li>Ve a la pestaña 'Network' para ver si se cargan los recursos</li>";
echo "<li>Ve a la pestaña 'Elements' para verificar la estructura HTML</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Test de dashboard completado</p>";
?> 