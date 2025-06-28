<?php
// Test para verificar que la página de inicio funciona correctamente
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Verificación - Página de Inicio</h2>";

// 1. Verificar IndexController
echo "<h3>1. Verificación de IndexController</h3>";
$controllerFile = '../app/controllers/indexController.php';
if (file_exists($controllerFile)) {
    echo "✓ IndexController.php existe<br>";
    
    $content = file_get_contents($controllerFile);
    
    // Verificar que tiene el método index() (en minúscula)
    if (strpos($content, 'public function index()') !== false) {
        echo "✓ Tiene método index() (en minúscula)<br>";
    } else {
        echo "✗ NO tiene método index() (en minúscula)<br>";
    }
    
    // Verificar que no tiene Index() (en mayúscula)
    if (strpos($content, 'public function Index()') !== false) {
        echo "✗ Aún tiene método Index() (en mayúscula) - PROBLEMA<br>";
    } else {
        echo "✓ NO tiene método Index() (en mayúscula) - CORRECTO<br>";
    }
    
} else {
    echo "✗ IndexController.php NO existe<br>";
}

// 2. Verificar vista de index
echo "<h3>2. Verificación de Vista de Index</h3>";
$viewFile = '../app/views/index/index.php';
if (file_exists($viewFile)) {
    echo "✓ index.php existe<br>";
    
    $content = file_get_contents($viewFile);
    
    // Verificar que incluye el footer
    if (strpos($content, 'require_once __DIR__ . \'/../layouts/footer.php\';') !== false) {
        echo "✓ Incluye el footer<br>";
    } else {
        echo "✗ NO incluye el footer<br>";
    }
    
    // Verificar que incluye el header
    if (strpos($content, 'require_once __DIR__ . \'/../layouts/header.php\';') !== false) {
        echo "✓ Incluye el header<br>";
    } else {
        echo "✗ NO incluye el header<br>";
    }
    
    // Verificar que incluye el head
    if (strpos($content, 'require_once __DIR__ . \'/../layouts/head.php\';') !== false) {
        echo "✓ Incluye el head<br>";
    } else {
        echo "✗ NO incluye el head<br>";
    }
    
} else {
    echo "✗ index.php NO existe<br>";
}

// 3. Verificar router
echo "<h3>3. Verificación de Router</h3>";
$routerFile = '../app/scripts/routerView.php';
if (file_exists($routerFile)) {
    echo "✓ routerView.php existe<br>";
    
    $content = file_get_contents($routerFile);
    
    // Verificar que mapea 'index' a 'IndexController'
    if (strpos($content, "'index' => 'IndexController'") !== false) {
        echo "✓ Mapea 'index' a 'IndexController'<br>";
    } else {
        echo "✗ NO mapea 'index' a 'IndexController'<br>";
    }
    
    // Verificar que busca método 'index()'
    if (strpos($content, "method_exists(\$controller, 'index')") !== false) {
        echo "✓ Busca método 'index()'<br>";
    } else {
        echo "✗ NO busca método 'index()'<br>";
    }
    
} else {
    echo "✗ routerView.php NO existe<br>";
}

// 4. Simular acceso a la página de inicio
echo "<h3>4. Simulación de Acceso a Página de Inicio</h3>";

// Simular parámetros GET para la página de inicio
$_GET['view'] = 'index';
$_GET['action'] = ''; // Sin acción específica

// Incluir archivos necesarios
require_once '../config.php';
require_once '../app/library/SessionMiddleware.php';

// Verificar si es ruta pública
$debugInfo = SessionMiddleware::getSessionDebugInfo();
echo "Vista actual: " . $debugInfo['current_view'] . "<br>";
echo "Acción actual: " . ($debugInfo['current_action'] ?: 'vacía') . "<br>";
echo "¿Es ruta pública?: " . ($debugInfo['is_public_route'] ? 'Sí' : 'No') . "<br>";

if ($debugInfo['is_public_route']) {
    echo "✓ CORRECTO: Página de inicio se detecta como ruta pública<br>";
} else {
    echo "✗ ERROR: Página de inicio NO se detecta como ruta pública<br>";
}

// 5. Verificar URLs de acceso
echo "<h3>5. URLs de Acceso</h3>";
echo "URLs que deberían mostrar la página de inicio:<br>";
echo "- http://localhost:8000/ (acceso directo)<br>";
echo "- http://localhost:8000/?view=index (sin acción)<br>";
echo "- http://localhost:8000/?view=index&action=index (con acción explícita)<br>";

// 6. Verificar que el método index() existe en IndexController
echo "<h3>6. Verificación de Método</h3>";
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Simular conexión a BD
    $mockDbConn = null;
    
    try {
        $controller = new IndexController($mockDbConn);
        
        if (method_exists($controller, 'index')) {
            echo "✓ El método index() existe en IndexController<br>";
        } else {
            echo "✗ El método index() NO existe en IndexController<br>";
        }
        
        // Mostrar métodos disponibles
        $methods = get_class_methods($controller);
        echo "Métodos disponibles: " . implode(', ', $methods) . "<br>";
        
    } catch (Exception $e) {
        echo "✗ Error al instanciar IndexController: " . $e->getMessage() . "<br>";
    }
}

echo "<h3>7. Resumen</h3>";
echo "Para que la página de inicio funcione correctamente:<br>";
echo "- IndexController debe tener método index() (en minúscula)<br>";
echo "- La vista index.php debe incluir head, header y footer<br>";
echo "- El router debe mapear 'index' a 'IndexController'<br>";
echo "- La ruta debe ser detectada como pública por SessionMiddleware<br>";

echo "<br><strong>Test completado. La página de inicio debería funcionar ahora.</strong>";
?> 