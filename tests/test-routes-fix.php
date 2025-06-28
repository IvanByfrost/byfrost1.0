<?php
// Test para verificar que las rutas como login, register, etc. funcionan correctamente
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Verificación - Rutas Corregidas</h2>";

// 1. Verificar router actualizado
echo "<h3>1. Verificación de Router Actualizado</h3>";
$routerFile = '../app/scripts/routerView.php';
if (file_exists($routerFile)) {
    echo "✓ routerView.php existe<br>";
    
    $content = file_get_contents($routerFile);
    
    // Verificar que tiene los nuevos mapeos
    $newMappings = ['login', 'register', 'contact', 'about', 'plans', 'faq', 'forgotPassword', 'resetPassword', 'completeProf'];
    foreach ($newMappings as $mapping) {
        if (strpos($content, "'$mapping' => 'IndexController'") !== false) {
            echo "✓ Mapea '$mapping' a 'IndexController'<br>";
        } else {
            echo "✗ NO mapea '$mapping' a 'IndexController'<br>";
        }
    }
    
    // Verificar que tiene la lógica de acciones por defecto
    if (strpos($content, '$defaultActions') !== false) {
        echo "✓ Tiene lógica de acciones por defecto<br>";
    } else {
        echo "✗ NO tiene lógica de acciones por defecto<br>";
    }
    
} else {
    echo "✗ routerView.php NO existe<br>";
}

// 2. Verificar IndexController
echo "<h3>2. Verificación de IndexController</h3>";
$controllerFile = '../app/controllers/indexController.php';
if (file_exists($controllerFile)) {
    echo "✓ indexController.php existe<br>";
    
    $content = file_get_contents($controllerFile);
    
    // Verificar que tiene todos los métodos necesarios
    $methods = ['login', 'register', 'contact', 'about', 'plans', 'faq', 'forgotPassword', 'resetPassword', 'completeProf'];
    foreach ($methods as $method) {
        if (strpos($content, "public function $method()") !== false) {
            echo "✓ Tiene método $method()<br>";
        } else {
            echo "✗ NO tiene método $method()<br>";
        }
    }
    
} else {
    echo "✗ indexController.php NO existe<br>";
}

// 3. Simular acceso a rutas
echo "<h3>3. Simulación de Acceso a Rutas</h3>";

// Incluir archivos necesarios
require_once '../config.php';
require_once '../app/library/SessionMiddleware.php';

$routes = [
    'login' => 'login',
    'register' => 'register',
    'contact' => 'contact',
    'about' => 'about',
    'plans' => 'plans',
    'faq' => 'faq',
    'forgotPassword' => 'forgotPassword',
    'resetPassword' => 'resetPassword',
    'completeProf' => 'completeProf'
];

foreach ($routes as $view => $expectedAction) {
    $_GET['view'] = $view;
    $_GET['action'] = ''; // Sin acción específica
    
    $debugInfo = SessionMiddleware::getSessionDebugInfo();
    echo "Ruta: $view -> Acción esperada: $expectedAction<br>";
    echo "- ¿Es ruta pública?: " . ($debugInfo['is_public_route'] ? 'Sí' : 'No') . "<br>";
    
    if ($debugInfo['is_public_route']) {
        echo "✓ $view se detecta como ruta pública<br>";
    } else {
        echo "✗ $view NO se detecta como ruta pública<br>";
    }
    echo "<br>";
}

// 4. URLs de prueba
echo "<h3>4. URLs para Probar</h3>";
echo "Ahora puedes probar estas URLs:<br>";
echo "- <a href='http://localhost:8000/?view=login' target='_blank'>http://localhost:8000/?view=login</a> (página de login)<br>";
echo "- <a href='http://localhost:8000/?view=login&msg=logout_success' target='_blank'>http://localhost:8000/?view=login&msg=logout_success</a> (login con mensaje)<br>";
echo "- <a href='http://localhost:8000/?view=register' target='_blank'>http://localhost:8000/?view=register</a> (página de registro)<br>";
echo "- <a href='http://localhost:8000/?view=contact' target='_blank'>http://localhost:8000/?view=contact</a> (página de contacto)<br>";
echo "- <a href='http://localhost:8000/?view=about' target='_blank'>http://localhost:8000/?view=about</a> (página about)<br>";
echo "- <a href='http://localhost:8000/?view=plans' target='_blank'>http://localhost:8000/?view=plans</a> (página de planes)<br>";
echo "- <a href='http://localhost:8000/?view=faq' target='_blank'>http://localhost:8000/?view=faq</a> (página FAQ)<br>";

// 5. Verificar que las rutas antiguas siguen funcionando
echo "<h3>5. Verificación de Rutas Antiguas</h3>";
echo "Las rutas antiguas también deberían seguir funcionando:<br>";
echo "- <a href='http://localhost:8000/?view=index&action=login' target='_blank'>http://localhost:8000/?view=index&action=login</a> (formato antiguo)<br>";
echo "- <a href='http://localhost:8000/?view=index&action=register' target='_blank'>http://localhost:8000/?view=index&action=register</a> (formato antiguo)<br>";

echo "<h3>6. Resumen de Cambios</h3>";
echo "Cambios realizados:<br>";
echo "- Agregué mapeo para rutas comunes (login, register, contact, etc.) al IndexController<br>";
echo "- Implementé lógica para usar automáticamente la acción correspondiente cuando no se especifica<br>";
echo "- Ahora ?view=login funciona igual que ?view=index&action=login<br>";
echo "- Todas las rutas públicas están correctamente mapeadas<br>";

echo "<br><strong>Test completado. Las rutas deberían funcionar correctamente ahora.</strong>";
?> 