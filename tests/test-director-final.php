<?php
// Script de prueba final para el dashboard del director
echo "=== PRUEBA FINAL DASHBOARD DIRECTOR ===\n";

// Definir ROOT si no está definido
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

echo "ROOT: " . ROOT . "\n";

// Verificar archivos críticos
$criticalFiles = [
    'config.php' => ROOT . '/config.php',
    'MainController.php' => ROOT . '/app/controllers/MainController.php',
    'DirectorDashboardController.php' => ROOT . '/app/controllers/directorDashboardController.php',
    'dashboard.php' => ROOT . '/app/views/director/dashboard.php',
    'dashFooter.php' => ROOT . '/app/views/layouts/dashFooter.php',
    'dashHeader.php' => ROOT . '/app/views/layouts/dashHeader.php',
    'head.php' => ROOT . '/app/views/layouts/head.php'
];

echo "\nVerificando archivos críticos:\n";
foreach ($criticalFiles as $name => $path) {
    if (file_exists($path)) {
        echo "✓ $name existe\n";
    } else {
        echo "✗ $name NO existe en $path\n";
    }
}

// Verificar que el controlador se puede cargar
if (file_exists(ROOT . '/config.php')) {
    require_once ROOT . '/config.php';
    echo "\n✓ config.php cargado\n";
    
    if (defined('url')) {
        echo "✓ url está definida: " . url . "\n";
    } else {
        echo "✗ url NO está definida\n";
    }
}

// Verificar que el controlador se puede instanciar
if (file_exists(ROOT . '/app/controllers/MainController.php') && 
    file_exists(ROOT . '/app/controllers/directorDashboardController.php')) {
    
    try {
        require_once ROOT . '/app/controllers/MainController.php';
        require_once ROOT . '/app/controllers/directorDashboardController.php';
        
        echo "\n✓ Controladores cargados correctamente\n";
        
        if (class_exists('DirectorDashboardController')) {
            echo "✓ Clase DirectorDashboardController existe\n";
            
            $reflection = new ReflectionClass('DirectorDashboardController');
            $parentClass = $reflection->getParentClass();
            
            if ($parentClass && $parentClass->getName() === 'MainController') {
                echo "✓ Extiende correctamente de MainController\n";
            } else {
                echo "✗ NO extiende de MainController\n";
            }
            
            // Verificar métodos críticos
            $criticalMethods = ['showDashboard', 'getMetrics', 'loadDashboardView'];
            foreach ($criticalMethods as $method) {
                if ($reflection->hasMethod($method)) {
                    echo "✓ Método $method existe\n";
                } else {
                    echo "✗ Método $method NO existe\n";
                }
            }
            
        } else {
            echo "✗ Clase DirectorDashboardController NO existe\n";
        }
        
    } catch (Exception $e) {
        echo "✗ Error cargando controladores: " . $e->getMessage() . "\n";
    }
}

// Verificar que el dashboard no incluye manualmente el footer
$dashboardContent = file_get_contents(ROOT . '/app/views/director/dashboard.php');
if (strpos($dashboardContent, 'dashFooter.php') !== false) {
    echo "\n⚠ ADVERTENCIA: El dashboard incluye manualmente dashFooter.php\n";
} else {
    echo "\n✓ El dashboard NO incluye manualmente dashFooter.php\n";
}

// Verificar que el dashFooter tiene las rutas corregidas
$footerContent = file_get_contents(ROOT . '/app/views/layouts/dashFooter.php');
if (strpos($footerContent, 'js/loadView.js') !== false) {
    echo "⚠ ADVERTENCIA: dashFooter tiene rutas duplicadas (js/loadView.js)\n";
} else {
    echo "✓ dashFooter tiene rutas corregidas\n";
}

echo "\n=== PRUEBA FINAL COMPLETADA ===\n";
echo "Si todas las verificaciones son correctas, el dashboard del director debería funcionar.\n";
echo "URL de prueba: http://localhost:8000/?view=directorDashboard\n";
?> 