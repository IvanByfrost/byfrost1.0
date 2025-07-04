<?php
// Diagnóstico del error actual
echo "<h1>🔍 Diagnóstico de Error Actual</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once 'config.php';

echo "<h2>1. Verificación de Variables de Configuración:</h2>";
echo "<ul>";
echo "<li><strong>ROOT:</strong> " . (defined('ROOT') ? ROOT : 'NO DEFINIDA') . "</li>";
echo "<li><strong>url:</strong> " . (defined('url') ? url : 'NO DEFINIDA') . "</li>";
echo "<li><strong>app:</strong> " . (defined('app') ? app : 'NO DEFINIDA') . "</li>";
echo "<li><strong>rq:</strong> " . (defined('rq') ? rq : 'NO DEFINIDA') . "</li>";
echo "<li><strong>Ruta JS:</strong> " . (defined('url') && defined('app') && defined('rq') ? url . app . rq . "js/" : 'NO CALCULABLE') . "</li>";
echo "</ul>";

echo "<h2>2. Verificación de Archivos JavaScript:</h2>";
$jsPath = ROOT . '/app/resources/js/loadView.js';
echo "<ul>";
echo "<li><strong>loadView.js:</strong> " . (file_exists($jsPath) ? 'EXISTE' : 'NO EXISTE') . " - " . $jsPath . "</li>";
echo "<li><strong>Tamaño:</strong> " . (file_exists($jsPath) ? filesize($jsPath) . ' bytes' : 'N/A') . "</li>";
echo "</ul>";

echo "<h2>3. Verificación de Dashboards:</h2>";
$dashboards = [
    'root/dashboard.php',
    'director/dashboard.php', 
    'coordinator/dashboard.php',
    'school/dashboard.php',
    'parent/dashboard.php'
];

echo "<ul>";
foreach ($dashboards as $dashboard) {
    $path = ROOT . '/app/views/' . $dashboard;
    $content = file_exists($path) ? file_get_contents($path) : '';
    $hasLoadView = strpos($content, 'loadView.js') !== false;
    $hasDashFooter = strpos($content, 'dashFooter.php') !== false;
    
    echo "<li><strong>$dashboard:</strong> " . (file_exists($path) ? 'EXISTE' : 'NO EXISTE');
    echo " - loadView.js: " . ($hasLoadView ? 'SÍ' : 'NO');
    echo " - dashFooter.php: " . ($hasDashFooter ? 'SÍ' : 'NO') . "</li>";
}
echo "</ul>";

echo "<h2>4. Verificación de dashFooter.php:</h2>";
$dashFooterPath = ROOT . '/app/views/layouts/dashFooter.php';
if (file_exists($dashFooterPath)) {
    $content = file_get_contents($dashFooterPath);
    $loadViewCount = substr_count($content, 'loadView.js');
    echo "<ul>";
    echo "<li><strong>Archivo:</strong> EXISTE</li>";
    echo "<li><strong>Referencias a loadView.js:</strong> $loadViewCount</li>";
    echo "<li><strong>Incluye dashFooter.php:</strong> " . (strpos($content, 'dashFooter.php') !== false ? 'SÍ' : 'NO') . "</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ dashFooter.php NO EXISTE</p>";
}

echo "<h2>5. URLs de Prueba:</h2>";
echo "<div style='font-family: Arial, sans-serif;'>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Dashboard Root</h3>";
echo "<p><strong>URL:</strong> <a href='?view=root&action=dashboard' target='_blank'>?view=root&action=dashboard</a></p>";
echo "<p><strong>Debug:</strong> <a href='?view=root&action=dashboard&debug=1' target='_blank'>Con debug</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Dashboard Director</h3>";
echo "<p><strong>URL:</strong> <a href='?view=director&action=dashboard' target='_blank'>?view=director&action=dashboard</a></p>";
echo "<p><strong>Debug:</strong> <a href='?view=director&action=dashboard&debug=1' target='_blank'>Con debug</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Dashboard Coordinator</h3>";
echo "<p><strong>URL:</strong> <a href='?view=coordinator&action=dashboard' target='_blank'>?view=coordinator&action=dashboard</a></p>";
echo "<p><strong>Debug:</strong> <a href='?view=coordinator&action=dashboard&debug=1' target='_blank'>Con debug</a></p>";
echo "</div>";

echo "</div>";

echo "<h2>6. Instrucciones para Reportar el Error:</h2>";
echo "<ol>";
echo "<li>Haz clic en una de las URLs de prueba</li>";
echo "<li>Si hay error, copia el mensaje exacto</li>";
echo "<li>Abre las herramientas de desarrollador (F12)</li>";
echo "<li>Ve a la pestaña 'Console' y copia cualquier error</li>";
echo "<li>Ve a la pestaña 'Network' y busca errores 404 o 403</li>";
echo "<li>Comparte toda esta información para poder ayudarte mejor</li>";
echo "</ol>";

echo "<h2>7. Verificación de Logs:</h2>";
echo "<p>Revisa los logs de error de tu servidor web (Apache/XAMPP) para ver si hay errores PHP.</p>";
echo "<p>En XAMPP, los logs están en: <code>C:\\xampp\\apache\\logs\\error.log</code></p>";

// Verificar si hay errores en el log actual
$logFile = 'C:/xampp/apache/logs/error.log';
if (file_exists($logFile)) {
    echo "<p><strong>Últimas líneas del log de error:</strong></p>";
    $lines = file($logFile);
    $lastLines = array_slice($lines, -10);
    echo "<pre style='background: #f5f5f5; padding: 10px; font-size: 12px;'>";
    foreach ($lastLines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
} else {
    echo "<p>No se pudo acceder al archivo de log: $logFile</p>";
}
?> 