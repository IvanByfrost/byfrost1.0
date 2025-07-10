<?php
// Script de diagnóstico para el dashboard del director
echo "=== DIAGNÓSTICO DASHBOARD DIRECTOR ===\n";

// Verificar configuración básica
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

echo "ROOT: " . ROOT . "\n";

// Verificar si config.php existe
$configPath = ROOT . '/config.php';
if (file_exists($configPath)) {
    echo "✓ config.php existe\n";
    require_once $configPath;
    echo "✓ config.php cargado\n";
} else {
    echo "✗ config.php NO existe\n";
    exit;
}

// Verificar si el controlador existe
$controllerPath = ROOT . '/app/controllers/directorDashboardController.php';
if (file_exists($controllerPath)) {
    echo "✓ directorDashboardController.php existe\n";
} else {
    echo "✗ directorDashboardController.php NO existe\n";
    exit;
}

// Verificar si MainController existe
$mainControllerPath = ROOT . '/app/controllers/MainController.php';
if (file_exists($mainControllerPath)) {
    echo "✓ MainController.php existe\n";
} else {
    echo "✗ MainController.php NO existe\n";
    exit;
}

// Intentar cargar el controlador
try {
    require_once $mainControllerPath;
    require_once $controllerPath;
    echo "✓ Controladores cargados correctamente\n";
    
    // Verificar que la clase existe
    if (class_exists('DirectorDashboardController')) {
        echo "✓ Clase DirectorDashboardController existe\n";
        
        // Verificar herencia
        $reflection = new ReflectionClass('DirectorDashboardController');
        $parentClass = $reflection->getParentClass();
        
        if ($parentClass && $parentClass->getName() === 'MainController') {
            echo "✓ Extiende correctamente de MainController\n";
        } else {
            echo "✗ NO extiende de MainController\n";
        }
        
        // Verificar métodos
        if ($reflection->hasMethod('showDashboard')) {
            echo "✓ Método showDashboard existe\n";
        } else {
            echo "✗ Método showDashboard NO existe\n";
        }
        
        if ($reflection->hasMethod('loadDashboardView')) {
            echo "✓ Método loadDashboardView existe\n";
        } else {
            echo "✗ Método loadDashboardView NO existe\n";
        }
        
    } else {
        echo "✗ Clase DirectorDashboardController NO existe\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error cargando controladores: " . $e->getMessage() . "\n";
}

// Verificar archivos de vista
$dashboardPath = ROOT . '/app/views/director/dashboard.php';
if (file_exists($dashboardPath)) {
    echo "✓ dashboard.php existe\n";
} else {
    echo "✗ dashboard.php NO existe\n";
}

$footerPath = ROOT . '/app/views/layouts/dashFooter.php';
if (file_exists($footerPath)) {
    echo "✓ dashFooter.php existe\n";
} else {
    echo "✗ dashFooter.php NO existe\n";
}

echo "\n=== DIAGNÓSTICO COMPLETADO ===\n";
?> 