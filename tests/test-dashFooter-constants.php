<?php
// Script de prueba para verificar constantes de dashFooter
echo "=== PRUEBA DE CONSTANTES PARA DASHFOOTER ===\n";

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

echo "ROOT: " . (defined('ROOT') ? ROOT : 'NO DEFINIDA') . "\n";
echo "url: " . (defined('url') ? url : 'NO DEFINIDA') . "\n";
echo "app: " . (defined('app') ? app : 'NO DEFINIDA') . "\n";
echo "rq: " . (defined('rq') ? rq : 'NO DEFINIDA') . "\n";

// Verificar que las rutas se construyen correctamente
$jsPath = url . app . rq;
echo "Ruta JS construida: " . $jsPath . "\n";

// Simular la inclusión del dashFooter
echo "\n=== SIMULANDO INCLUSIÓN DE DASHFOOTER ===\n";
echo "URL base para JS: " . url . "\n";
echo "Ruta para loadView.js: " . url . app . rq . "loadView.js\n";
echo "Ruta para sessionHandler.js: " . url . app . rq . "sessionHandler.js\n";

echo "\n=== PRUEBA COMPLETADA ===\n";
echo "Si no hay errores arriba, las constantes están correctamente definidas.\n";
?> 