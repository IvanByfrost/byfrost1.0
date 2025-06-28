<?php
// Test para verificar que se solucionó el bucle de redirección
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Verificación - Bucle de Redirección Solucionado</h2>";

// 1. Verificar SessionMiddleware actualizado
echo "<h3>1. Verificación de SessionMiddleware Actualizado</h3>";
$middlewareFile = '../app/library/SessionMiddleware.php';
if (file_exists($middlewareFile)) {
    echo "✓ SessionMiddleware.php existe<br>";
    
    $content = file_get_contents($middlewareFile);
    
    // Verificar que tiene la nueva funcionalidad
    if (strpos($content, 'private static $publicRoutes') !== false) {
        echo "✓ Tiene definición de rutas públicas<br>";
    } else {
        echo "✗ NO tiene definición de rutas públicas<br>";
    }
    
    if (strpos($content, 'isPublicRoute()') !== false) {
        echo "✓ Tiene método isPublicRoute()<br>";
    } else {
        echo "✗ NO tiene método isPublicRoute()<br>";
    }
    
    if (strpos($content, 'if (self::isPublicRoute())') !== false) {
        echo "✓ Verifica rutas públicas antes de redirigir<br>";
    } else {
        echo "✗ NO verifica rutas públicas<br>";
    }
    
} else {
    echo "✗ SessionMiddleware.php NO existe<br>";
}

// 2. Simular acceso a ruta pública
echo "<h3>2. Simulación de Acceso a Ruta Pública</h3>";

// Simular parámetros GET para login
$_GET['view'] = 'index';
$_GET['action'] = 'login';

// Incluir SessionMiddleware
require_once '../app/library/SessionMiddleware.php';

// Verificar si detecta como ruta pública
$debugInfo = SessionMiddleware::getSessionDebugInfo();
echo "Vista actual: " . $debugInfo['current_view'] . "<br>";
echo "Acción actual: " . $debugInfo['current_action'] . "<br>";
echo "¿Es ruta pública?: " . ($debugInfo['is_public_route'] ? 'Sí' : 'No') . "<br>";

if ($debugInfo['is_public_route']) {
    echo "✓ CORRECTO: Login se detecta como ruta pública<br>";
} else {
    echo "✗ ERROR: Login NO se detecta como ruta pública<br>";
}

// 3. Simular acceso a ruta privada
echo "<h3>3. Simulación de Acceso a Ruta Privada</h3>";

// Simular parámetros GET para dashboard
$_GET['view'] = 'root';
$_GET['action'] = 'dashboard';

// Verificar si detecta como ruta privada
$debugInfo = SessionMiddleware::getSessionDebugInfo();
echo "Vista actual: " . $debugInfo['current_view'] . "<br>";
echo "Acción actual: " . $debugInfo['current_action'] . "<br>";
echo "¿Es ruta pública?: " . ($debugInfo['is_public_route'] ? 'Sí' : 'No') . "<br>";

if (!$debugInfo['is_public_route']) {
    echo "✓ CORRECTO: Dashboard se detecta como ruta privada<br>";
} else {
    echo "✗ ERROR: Dashboard se detecta como ruta pública<br>";
}

// 4. Verificar otras rutas públicas
echo "<h3>4. Verificación de Otras Rutas Públicas</h3>";
$publicRoutes = [
    ['view' => 'index', 'action' => 'register'],
    ['view' => 'index', 'action' => 'contact'],
    ['view' => 'index', 'action' => 'about'],
    ['view' => 'index', 'action' => 'plans'],
    ['view' => 'index', 'action' => 'faq'],
    ['view' => 'Error', 'action' => '404'],
    ['view' => 'Error', 'action' => '403']
];

foreach ($publicRoutes as $route) {
    $_GET['view'] = $route['view'];
    $_GET['action'] = $route['action'];
    
    $debugInfo = SessionMiddleware::getSessionDebugInfo();
    $isPublic = $debugInfo['is_public_route'];
    
    if ($isPublic) {
        echo "✓ {$route['view']}/{$route['action']} - Pública<br>";
    } else {
        echo "✗ {$route['view']}/{$route['action']} - Privada (ERROR)<br>";
    }
}

// 5. Verificar rutas privadas
echo "<h3>5. Verificación de Rutas Privadas</h3>";
$privateRoutes = [
    ['view' => 'root', 'action' => 'dashboard'],
    ['view' => 'director', 'action' => 'dashboard'],
    ['view' => 'coordinator', 'action' => 'dashboard'],
    ['view' => 'teacher', 'action' => 'dashboard'],
    ['view' => 'student', 'action' => 'dashboard'],
    ['view' => 'user', 'action' => 'assignRole']
];

foreach ($privateRoutes as $route) {
    $_GET['view'] = $route['view'];
    $_GET['action'] = $route['action'];
    
    $debugInfo = SessionMiddleware::getSessionDebugInfo();
    $isPublic = $debugInfo['is_public_route'];
    
    if (!$isPublic) {
        echo "✓ {$route['view']}/{$route['action']} - Privada<br>";
    } else {
        echo "✗ {$route['view']}/{$route['action']} - Pública (ERROR)<br>";
    }
}

// 6. Test de acceso directo
echo "<h3>6. Test de Acceso Directo</h3>";
// Simular acceso sin parámetros
unset($_GET['view']);
unset($_GET['action']);

$debugInfo = SessionMiddleware::getSessionDebugInfo();
echo "¿Es ruta pública sin parámetros?: " . ($debugInfo['is_public_route'] ? 'Sí' : 'No') . "<br>";

if ($debugInfo['is_public_route']) {
    echo "✓ CORRECTO: Acceso directo se considera público<br>";
} else {
    echo "✗ ERROR: Acceso directo NO se considera público<br>";
}

echo "<h3>7. Resumen</h3>";
echo "El SessionMiddleware ahora:<br>";
echo "- Detecta rutas públicas y no redirige en ellas<br>";
echo "- Solo verifica sesión completa en rutas privadas<br>";
echo "- Evita el bucle infinito de redirección<br>";

echo "<br><strong>Test completado. El bucle de redirección debería estar solucionado.</strong>";
?> 