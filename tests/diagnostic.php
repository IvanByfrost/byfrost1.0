<?php
// Script de diagnóstico para el dashboard del director
echo "<h2>=== DIAGNÓSTICO DASHBOARD DIRECTOR ===</h2>";

// Definir ROOT correctamente - apuntar al directorio byfrost
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

echo "<p><strong>ROOT:</strong> " . ROOT . "</p>";
echo "<p><strong>Directorio actual:</strong> " . __DIR__ . "</p>";

// Verificar archivos críticos
$files = [
    'config.php' => ROOT . '/config.php',
    'MainController.php' => ROOT . '/app/controllers/MainController.php',
    'DirectorDashboardController.php' => ROOT . '/app/controllers/directorDashboardController.php',
    'dashboard.php' => ROOT . '/app/views/director/dashboard.php',
    'dashFooter.php' => ROOT . '/app/views/layouts/dashFooter.php',
    'dashHeader.php' => ROOT . '/app/views/layouts/dashHeader.php',
    'head.php' => ROOT . '/app/views/layouts/head.php',
    'routerView.php' => ROOT . '/app/scripts/routerView.php'
];

echo "<h3>Verificando archivos:</h3>";
foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "<p style='color: green;'>✓ $name existe</p>";
    } else {
        echo "<p style='color: red;'>✗ $name NO existe en $path</p>";
    }
}

// Intentar cargar config.php
if (file_exists(ROOT . '/config.php')) {
    try {
        require_once ROOT . '/config.php';
        echo "<p style='color: green;'>✓ config.php cargado correctamente</p>";
        
        if (defined('url')) {
            echo "<p style='color: green;'>✓ url está definida: " . url . "</p>";
        } else {
            echo "<p style='color: red;'>✗ url NO está definida</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error cargando config.php: " . $e->getMessage() . "</p>";
    }
}

// Intentar cargar el controlador
if (file_exists(ROOT . '/app/controllers/MainController.php') && 
    file_exists(ROOT . '/app/controllers/directorDashboardController.php')) {
    
    try {
        require_once ROOT . '/app/controllers/MainController.php';
        require_once ROOT . '/app/controllers/directorDashboardController.php';
        
        echo "<p style='color: green;'>✓ Controladores cargados correctamente</p>";
        
        if (class_exists('DirectorDashboardController')) {
            echo "<p style='color: green;'>✓ Clase DirectorDashboardController existe</p>";
            
            $reflection = new ReflectionClass('DirectorDashboardController');
            $parentClass = $reflection->getParentClass();
            
            if ($parentClass && $parentClass->getName() === 'MainController') {
                echo "<p style='color: green;'>✓ Extiende correctamente de MainController</p>";
            } else {
                echo "<p style='color: red;'>✗ NO extiende de MainController</p>";
            }
            
            // Verificar métodos críticos
            $criticalMethods = ['showDashboard', 'getMetrics', 'loadDashboardView'];
            foreach ($criticalMethods as $method) {
                if ($reflection->hasMethod($method)) {
                    echo "<p style='color: green;'>✓ Método $method existe</p>";
                } else {
                    echo "<p style='color: red;'>✗ Método $method NO existe</p>";
                }
            }
            
        } else {
            echo "<p style='color: red;'>✗ Clase DirectorDashboardController NO existe</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error cargando controladores: " . $e->getMessage() . "</p>";
    }
}

// Verificar que el dashboard no incluye manualmente el footer
$dashboardPath = ROOT . '/app/views/director/dashboard.php';
if (file_exists($dashboardPath)) {
    $dashboardContent = file_get_contents($dashboardPath);
    if (strpos($dashboardContent, 'dashFooter.php') !== false) {
        echo "<p style='color: orange;'>⚠ ADVERTENCIA: El dashboard incluye manualmente dashFooter.php</p>";
    } else {
        echo "<p style='color: green;'>✓ El dashboard NO incluye manualmente dashFooter.php</p>";
    }
}

// Verificar que el dashFooter tiene las rutas corregidas
$footerPath = ROOT . '/app/views/layouts/dashFooter.php';
if (file_exists($footerPath)) {
    $footerContent = file_get_contents($footerPath);
    if (strpos($footerContent, 'js/loadView.js') !== false) {
        echo "<p style='color: orange;'>⚠ ADVERTENCIA: dashFooter tiene rutas duplicadas (js/loadView.js)</p>";
    } else {
        echo "<p style='color: green;'>✓ dashFooter tiene rutas corregidas</p>";
    }
}

echo "<h3>=== DIAGNÓSTICO COMPLETADO ===</h3>";
echo "<p>Si todas las verificaciones son correctas, el dashboard del director debería funcionar.</p>";
echo "<p><strong>URL de prueba:</strong> <a href='/?view=directorDashboard' target='_blank'>http://localhost:8000/?view=directorDashboard</a></p>";
?> 