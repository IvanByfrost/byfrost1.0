<?php
// Script de prueba para verificar dashFooter corregido
echo "=== PRUEBA DE DASHFOOTER CORREGIDO ===\n";

// Simular el contexto del dashboard del director
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/config.php';

// Asegurar que las constantes necesarias para dashFooter estén definidas
if (!defined('url')) {
    define('url', 'http://localhost:8000/');
}
if (!defined('app')) {
    define('app', 'app/resources/');
}
if (!defined('rq')) {
    define('rq', 'js/');
}

echo "Constantes definidas:\n";
echo "- url: " . url . "\n";
echo "- app: " . app . "\n";
echo "- rq: " . rq . "\n";

// Verificar rutas construidas
$jsPath = url . app . rq;
echo "\nRutas construidas:\n";
echo "- Ruta JS base: " . $jsPath . "\n";
echo "- loadView.js: " . $jsPath . "loadView.js\n";
echo "- directorDashboard.js: " . $jsPath . "directorDashboard.js\n";

// Verificar que los archivos JS existen
$jsFiles = [
    'loadView.js',
    'sessionHandler.js',
    'userSearch.js',
    'directorDashboard.js'
];

echo "\nVerificando archivos JS:\n";
foreach ($jsFiles as $file) {
    $fullPath = ROOT . '/app/resources/js/' . $file;
    if (file_exists($fullPath)) {
        echo "✓ " . $file . " existe\n";
    } else {
        echo "✗ " . $file . " NO existe en " . $fullPath . "\n";
    }
}

echo "\n=== PRUEBA COMPLETADA ===\n";
echo "Si todos los archivos existen, el dashFooter debería cargar correctamente.\n";
?> 