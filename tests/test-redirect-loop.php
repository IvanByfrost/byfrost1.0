<?php
// Test para diagnosticar el bucle de redirección
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Diagnóstico - Bucle de Redirección</h2>";

// 1. Verificar configuración de sesión
echo "<h3>1. Estado de la Sesión</h3>";
echo "Session status: " . session_status() . "<br>";
echo "Session ID: " . (session_id() ?: 'No iniciada') . "<br>";
echo "Headers sent: " . (headers_sent() ? 'Sí' : 'No') . "<br>";

// 2. Verificar SessionMiddleware
echo "<h3>2. Verificación de SessionMiddleware</h3>";
$middlewareFile = '../app/library/SessionMiddleware.php';
if (file_exists($middlewareFile)) {
    echo "✓ SessionMiddleware.php existe<br>";
    
    // Verificar si hay redirecciones problemáticas
    $content = file_get_contents($middlewareFile);
    if (strpos($content, 'header(\'Location: /?view=index&action=login\')') !== false) {
        echo "⚠ PROBLEMA DETECTADO: SessionMiddleware redirige a login<br>";
        echo "Esto puede causar bucle infinito si login también usa SessionMiddleware<br>";
    }
} else {
    echo "✗ SessionMiddleware.php NO existe<br>";
}

// 3. Verificar IndexController
echo "<h3>3. Verificación de IndexController</h3>";
$controllerFile = '../app/controllers/indexController.php';
if (file_exists($controllerFile)) {
    echo "✓ IndexController.php existe<br>";
    
    $content = file_get_contents($controllerFile);
    if (strpos($content, 'SessionMiddleware::handle') !== false) {
        echo "⚠ PROBLEMA DETECTADO: IndexController usa SessionMiddleware<br>";
        echo "Esto puede causar bucle infinito con SessionMiddleware<br>";
    }
} else {
    echo "✗ IndexController.php NO existe<br>";
}

// 4. Verificar MainController
echo "<h3>4. Verificación de MainController</h3>";
$mainControllerFile = '../app/controllers/MainController.php';
if (file_exists($mainControllerFile)) {
    echo "✓ MainController.php existe<br>";
    
    $content = file_get_contents($mainControllerFile);
    if (strpos($content, 'SessionMiddleware::handle') !== false) {
        echo "⚠ PROBLEMA DETECTADO: MainController usa SessionMiddleware<br>";
        echo "Esto afecta a todos los controladores que heredan de MainController<br>";
    }
} else {
    echo "✗ MainController.php NO existe<br>";
}

// 5. Simular el flujo problemático
echo "<h3>5. Análisis del Flujo Problemático</h3>";
echo "Flujo actual:<br>";
echo "1. Usuario accede a /?view=index&action=login<br>";
echo "2. IndexController se instancia<br>";
echo "3. MainController constructor ejecuta SessionMiddleware::handle<br>";
echo "4. SessionMiddleware detecta que no hay sesión<br>";
echo "5. SessionMiddleware redirige a /?view=index&action=login<br>";
echo "6. Vuelve al paso 1 - BUCLE INFINITO<br>";

echo "<h3>6. Solución Propuesta</h3>";
echo "El SessionMiddleware debe excluir ciertas rutas públicas como login, register, etc.<br>";
echo "O el IndexController no debe usar SessionMiddleware para rutas públicas.<br>";

echo "<br><strong>Test completado.</strong>";
?> 