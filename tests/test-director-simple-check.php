<?php
// Script de prueba simple para verificar archivos del dashboard del director
echo "=== PRUEBA SIMPLE DASHBOARD DIRECTOR ===\n";

// Definir ROOT correctamente
$root = dirname(__DIR__);
echo "ROOT: " . $root . "\n";

// Verificar archivos críticos
$files = [
    'config.php' => $root . '/config.php',
    'MainController.php' => $root . '/app/controllers/MainController.php',
    'DirectorDashboardController.php' => $root . '/app/controllers/directorDashboardController.php',
    'dashboard.php' => $root . '/app/views/director/dashboard.php',
    'dashFooter.php' => $root . '/app/views/layouts/dashFooter.php',
    'dashHeader.php' => $root . '/app/views/layouts/dashHeader.php',
    'head.php' => $root . '/app/views/layouts/head.php'
];

echo "\nVerificando archivos:\n";
foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "✓ $name existe\n";
    } else {
        echo "✗ $name NO existe en $path\n";
    }
}

// Verificar que el dashboard no incluye manualmente el footer
$dashboardPath = $root . '/app/views/director/dashboard.php';
if (file_exists($dashboardPath)) {
    $dashboardContent = file_get_contents($dashboardPath);
    if (strpos($dashboardContent, 'dashFooter.php') !== false) {
        echo "\n⚠ ADVERTENCIA: El dashboard incluye manualmente dashFooter.php\n";
    } else {
        echo "\n✓ El dashboard NO incluye manualmente dashFooter.php\n";
    }
}

// Verificar que el dashFooter tiene las rutas corregidas
$footerPath = $root . '/app/views/layouts/dashFooter.php';
if (file_exists($footerPath)) {
    $footerContent = file_get_contents($footerPath);
    if (strpos($footerContent, 'js/loadView.js') !== false) {
        echo "⚠ ADVERTENCIA: dashFooter tiene rutas duplicadas (js/loadView.js)\n";
    } else {
        echo "✓ dashFooter tiene rutas corregidas\n";
    }
}

echo "\n=== PRUEBA COMPLETADA ===\n";
echo "Si todos los archivos existen, el dashboard del director debería funcionar.\n";
echo "URL de prueba: http://localhost:8000/?view=directorDashboard\n";
?> 