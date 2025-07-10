<?php
// Script de prueba para verificar dashboard del director corregido
echo "=== PRUEBA DE DASHBOARD DEL DIRECTOR CORREGIDO ===\n";

// Definir ROOT correctamente
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

echo "ROOT: " . ROOT . "\n";

// Verificar que config.php existe en la ruta correcta
$configPath = ROOT . '/config.php';
if (file_exists($configPath)) {
    echo "✓ config.php existe en: " . $configPath . "\n";
    require_once $configPath;
    echo "✓ config.php cargado correctamente\n";
} else {
    echo "✗ config.php NO existe en: " . $configPath . "\n";
    exit;
}

// Verificar que las constantes necesarias están definidas
echo "\nVerificando constantes:\n";
echo "- ROOT: " . (defined('ROOT') ? 'DEFINIDA' : 'NO DEFINIDA') . "\n";
echo "- url: " . (defined('url') ? 'DEFINIDA' : 'NO DEFINIDA') . "\n";

// Verificar que el archivo del dashboard existe
$dashboardPath = ROOT . '/app/views/director/dashboard.php';
echo "\nVerificando archivos:\n";
echo "- Dashboard: " . (file_exists($dashboardPath) ? 'EXISTE' : 'NO EXISTE') . "\n";

// Verificar que el dashFooter existe
$footerPath = ROOT . '/app/views/layouts/dashFooter.php';
echo "- DashFooter: " . (file_exists($footerPath) ? 'EXISTE' : 'NO EXISTE') . "\n";

// Verificar que no hay inclusión manual del footer en el dashboard
$dashboardContent = file_get_contents($dashboardPath);
if (strpos($dashboardContent, 'dashFooter.php') !== false) {
    echo "⚠ ADVERTENCIA: El dashboard incluye manualmente dashFooter.php\n";
} else {
    echo "✓ El dashboard NO incluye manualmente dashFooter.php\n";
}

echo "\n=== PRUEBA COMPLETADA ===\n";
echo "Si todas las verificaciones son correctas, el dashboard del director debería funcionar.\n";
?> 