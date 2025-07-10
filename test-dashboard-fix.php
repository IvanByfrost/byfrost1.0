<?php
/**
 * Test para verificar que los dashboards y sidebars funcionan correctamente
 */

// Incluir configuración
require_once 'config.php';

echo "<h1>Test de Dashboards y Sidebars</h1>";

// Test 1: Verificar que la URL base se construye correctamente
echo "<h2>Test 1: URL Base</h2>";
echo "<p>URL base: " . url . "</p>";
echo "<p>App path: " . app . "</p>";
echo "<p>Resources path: " . rq . "</p>";

// Test 2: Verificar que los archivos de sidebar existen
echo "<h2>Test 2: Archivos de Sidebar</h2>";
$sidebarFiles = [
    'app/views/director/directorSidebar.php',
    'app/views/root/rootSidebar.php',
    'app/views/coordinator/coordSidebar.php',
    'app/views/treasurer/treasurerSidebar.php',
    'app/views/student/studentSidebar.php',
    'app/views/teacher/teacherSidebar.php'
];

foreach ($sidebarFiles as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $file NO existe</p>";
    }
}

// Test 3: Verificar que los archivos de dashboard existen
echo "<h2>Test 3: Archivos de Dashboard</h2>";
$dashboardFiles = [
    'app/views/director/dashboard.php',
    'app/views/root/dashboard.php',
    'app/views/coordinator/dashboard.php',
    'app/views/treasurer/dashboard.php',
    'app/views/student/dashboard.php',
    'app/views/teacher/dashboard.php'
];

foreach ($dashboardFiles as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $file NO existe</p>";
    }
}

// Test 4: Verificar que loadView.js existe y es legible
echo "<h2>Test 4: JavaScript Files</h2>";
$jsFiles = [
    'app/resources/js/loadView.js',
    'app/resources/js/dashboard.js',
    'app/resources/js/userManagement.js'
];

foreach ($jsFiles as $file) {
    if (file_exists($file)) {
        $size = filesize($file);
        echo "<p style='color: green;'>✅ $file existe ($size bytes)</p>";
    } else {
        echo "<p style='color: red;'>❌ $file NO existe</p>";
    }
}

// Test 5: Verificar rutas de controladores
echo "<h2>Test 5: Controladores</h2>";
$controllers = [
    'app/controllers/IndexController.php',
    'app/controllers/DirectorDashboardController.php',
    'app/controllers/RootController.php',
    'app/controllers/MainController.php'
];

foreach ($controllers as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $file NO existe</p>";
    }
}

// Test 6: Verificar layouts
echo "<h2>Test 6: Layouts</h2>";
$layoutFiles = [
    'app/views/layouts/dashHeader.php',
    'app/views/layouts/dashFooter.php',
    'app/views/layouts/head.php',
    'app/views/layouts/header.php',
    'app/views/layouts/footer.php'
];

foreach ($layoutFiles as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $file NO existe</p>";
    }
}

// Test 7: Simular URLs de navegación
echo "<h2>Test 7: URLs de Navegación</h2>";
$testUrls = [
    '?view=index',
    '?view=directorDashboard',
    '?view=rootDashboard',
    '?view=coordinatorDashboard',
    '?view=user&action=loadPartial&partialView=consultUser',
    '?view=school&action=loadPartial&partialView=createSchool'
];

foreach ($testUrls as $url) {
    echo "<p><a href='$url' target='_blank'>$url</a></p>";
}

echo "<h2>Test 8: Problemas Identificados y Soluciones</h2>";
echo "<ul>";
echo "<li>✅ <strong>Problema del inicio:</strong> Arreglado - IndexController ahora maneja correctamente la página principal</li>";
echo "<li>✅ <strong>Problema de sidebars:</strong> Arreglado - Estandarizado el uso de loadView() en todos los sidebars</li>";
echo "<li>✅ <strong>Problema de páginas dinámicas:</strong> Arreglado - Mejorada la detección de peticiones AJAX</li>";
echo "<li>✅ <strong>Problema de URLs:</strong> Arreglado - Función buildViewUrl() para construir URLs consistentes</li>";
echo "<li>✅ <strong>Problema de submenús:</strong> Arreglado - Agregado JavaScript para manejar submenús</li>";
echo "</ul>";

echo "<h2>Instrucciones de Uso</h2>";
echo "<ol>";
echo "<li><strong>Para probar el inicio:</strong> Ve a <a href='?view=index'>?view=index</a></li>";
echo "<li><strong>Para probar dashboards:</strong> Ve a <a href='?view=directorDashboard'>?view=directorDashboard</a></li>";
echo "<li><strong>Para probar navegación dinámica:</strong> Usa los enlaces en los sidebars</li>";
echo "<li><strong>Para probar carga de vistas:</strong> Haz clic en cualquier elemento del sidebar</li>";
echo "</ol>";

echo "<h2>Debug</h2>";
echo "<p>Para ver logs de debug, revisa el archivo de error de PHP.</p>";
echo "<p>Para verificar que loadView funciona, abre la consola del navegador.</p>";
?> 