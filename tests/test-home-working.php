<?php
// Test para verificar que la página de inicio funciona correctamente
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Verificación - Página de Inicio Funcionando</h2>";

// 1. Verificar SessionMiddleware actualizado
echo "<h3>1. Verificación de SessionMiddleware</h3>";
$middlewareFile = '../app/library/SessionMiddleware.php';
if (file_exists($middlewareFile)) {
    echo "✓ SessionMiddleware.php existe<br>";
    
    $content = file_get_contents($middlewareFile);
    
    // Verificar que incluye 'index' en las rutas públicas
    if (strpos($content, "'index' => ['index', 'login', 'register'") !== false) {
        echo "✓ Incluye 'index' en las rutas públicas<br>";
    } else {
        echo "✗ NO incluye 'index' en las rutas públicas<br>";
    }
    
} else {
    echo "✗ SessionMiddleware.php NO existe<br>";
}

// 2. Simular acceso a la raíz (página de inicio)
echo "<h3>2. Simulación de Acceso a la Raíz</h3>";

// Simular acceso directo a la raíz
$_GET = []; // Sin parámetros

// Incluir archivos necesarios
require_once '../config.php';
require_once '../app/library/SessionMiddleware.php';

// Verificar si es ruta pública
$debugInfo = SessionMiddleware::getSessionDebugInfo();
echo "Vista actual: " . ($debugInfo['current_view'] ?: 'vacía') . "<br>";
echo "Acción actual: " . ($debugInfo['current_action'] ?: 'vacía') . "<br>";
echo "¿Es ruta pública?: " . ($debugInfo['is_public_route'] ? 'Sí' : 'No') . "<br>";

if ($debugInfo['is_public_route']) {
    echo "✓ CORRECTO: Acceso directo se detecta como ruta pública<br>";
} else {
    echo "✗ ERROR: Acceso directo NO se detecta como ruta pública<br>";
}

// 3. Simular acceso con view=index sin acción
echo "<h3>3. Simulación de Acceso con view=index</h3>";

$_GET['view'] = 'index';
$_GET['action'] = ''; // Sin acción específica

$debugInfo = SessionMiddleware::getSessionDebugInfo();
echo "Vista actual: " . $debugInfo['current_view'] . "<br>";
echo "Acción actual: " . ($debugInfo['current_action'] ?: 'vacía') . "<br>";
echo "¿Es ruta pública?: " . ($debugInfo['is_public_route'] ? 'Sí' : 'No') . "<br>";

if ($debugInfo['is_public_route']) {
    echo "✓ CORRECTO: view=index sin acción se detecta como ruta pública<br>";
} else {
    echo "✗ ERROR: view=index sin acción NO se detecta como ruta pública<br>";
}

// 4. Simular acceso con view=index y action=index
echo "<h3>4. Simulación de Acceso con view=index&action=index</h3>";

$_GET['view'] = 'index';
$_GET['action'] = 'index';

$debugInfo = SessionMiddleware::getSessionDebugInfo();
echo "Vista actual: " . $debugInfo['current_view'] . "<br>";
echo "Acción actual: " . $debugInfo['current_action'] . "<br>";
echo "¿Es ruta pública?: " . ($debugInfo['is_public_route'] ? 'Sí' : 'No') . "<br>";

if ($debugInfo['is_public_route']) {
    echo "✓ CORRECTO: view=index&action=index se detecta como ruta pública<br>";
} else {
    echo "✗ ERROR: view=index&action=index NO se detecta como ruta pública<br>";
}

// 5. Verificar IndexController
echo "<h3>5. Verificación de IndexController</h3>";
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
    
} else {
    echo "✗ IndexController.php NO existe<br>";
}

// 6. Verificar vista de index
echo "<h3>6. Verificación de Vista de Index</h3>";
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
    
} else {
    echo "✗ index.php NO existe<br>";
}

// 7. URLs de prueba
echo "<h3>7. URLs para Probar</h3>";
echo "Ahora puedes probar estas URLs:<br>";
echo "- <a href='http://localhost:8000/' target='_blank'>http://localhost:8000/</a> (acceso directo)<br>";
echo "- <a href='http://localhost:8000/?view=index' target='_blank'>http://localhost:8000/?view=index</a> (sin acción)<br>";
echo "- <a href='http://localhost:8000/?view=index&action=index' target='_blank'>http://localhost:8000/?view=index&action=index</a> (con acción explícita)<br>";

echo "<h3>8. Resumen</h3>";
echo "Cambios realizados:<br>";
echo "- Agregué 'index' a las rutas públicas en SessionMiddleware<br>";
echo "- Cambié Index() a index() en IndexController<br>";
echo "- Descomenté el footer en la vista index.php<br>";
echo "- El SessionMiddleware ahora detecta correctamente la página de inicio como ruta pública<br>";

echo "<br><strong>Test completado. La página de inicio debería funcionar correctamente ahora.</strong>";
?> 