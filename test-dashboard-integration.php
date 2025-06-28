<?php
/**
 * Test de integración del dashboard
 */

echo "<h1>Test: Integración del Dashboard</h1>";

// 1. Verificar archivos del dashboard
echo "<h2>1. Verificar archivos del dashboard:</h2>";
define('ROOT', __DIR__ . '/');

$files = [
    'app/views/root/dashboard.php' => ROOT . 'app/views/root/dashboard.php',
    'app/views/root/rootSidebar.php' => ROOT . 'app/views/root/rootSidebar.php',
    'app/resources/js/loadView.js' => ROOT . 'app/resources/js/loadView.js',
    'app/views/user/assignRole.php' => ROOT . 'app/views/user/assignRole.php',
    'app/resources/js/assignRole.js' => ROOT . 'app/resources/js/assignRole.js'
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$name</strong>: " . ($exists ? "EXISTE" : "NO EXISTE") . "</div>";
}

// 2. Verificar rutas del router
echo "<h2>2. Verificar mapeo en router:</h2>";
$routerFile = ROOT . 'app/scripts/routerView.php';
if (file_exists($routerFile)) {
    $routerContent = file_get_contents($routerFile);
    if (strpos($routerContent, "'user' => 'UserController'") !== false) {
        echo "<div style='color: green;'>✅ Mapeo 'user' => 'UserController' encontrado</div>";
    } else {
        echo "<div style='color: red;'>❌ Mapeo 'user' => 'UserController' NO encontrado</div>";
    }
} else {
    echo "<div style='color: red;'>❌ Archivo router no encontrado</div>";
}

// 3. Verificar UserController
echo "<h2>3. Verificar UserController:</h2>";
$controllerFile = ROOT . 'app/controllers/UserController.php';
if (file_exists($controllerFile)) {
    $controllerContent = file_get_contents($controllerFile);
    if (strpos($controllerContent, 'public function assignRole()') !== false) {
        echo "<div style='color: green;'>✅ Método assignRole() encontrado</div>";
    } else {
        echo "<div style='color: red;'>❌ Método assignRole() NO encontrado</div>";
    }
    
    if (strpos($controllerContent, 'public function processAssignRole()') !== false) {
        echo "<div style='color: green;'>✅ Método processAssignRole() encontrado</div>";
    } else {
        echo "<div style='color: red;'>❌ Método processAssignRole() NO encontrado</div>";
    }
    
    if (strpos($controllerContent, 'public function getUsersWithoutRole()') !== false) {
        echo "<div style='color: green;'>✅ Método getUsersWithoutRole() encontrado</div>";
    } else {
        echo "<div style='color: red;'>❌ Método getUsersWithoutRole() NO encontrado</div>";
    }
} else {
    echo "<div style='color: red;'>❌ UserController no encontrado</div>";
}

// 4. URLs de prueba
echo "<h2>4. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles (Directo)</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139' target='_blank'>Buscar Usuario Específico</a></li>";
echo "</ul>";

// 5. Verificar JavaScript
echo "<h2>5. Verificar JavaScript:</h2>";
$loadViewFile = ROOT . 'app/resources/js/loadView.js';
if (file_exists($loadViewFile)) {
    $loadViewContent = file_get_contents($loadViewFile);
    if (strpos($loadViewContent, 'fetch(`${BASE_URL}?view=${viewName}`)') !== false) {
        echo "<div style='color: green;'>✅ loadView.js usa ruta correcta</div>";
    } else {
        echo "<div style='color: red;'>❌ loadView.js NO usa ruta correcta</div>";
    }
} else {
    echo "<div style='color: red;'>❌ loadView.js no encontrado</div>";
}

$assignRoleFile = ROOT . 'app/resources/js/assignRole.js';
if (file_exists($assignRoleFile)) {
    $assignRoleContent = file_get_contents($assignRoleFile);
    if (strpos($assignRoleContent, 'e.preventDefault()') !== false) {
        echo "<div style='color: green;'>✅ assignRole.js previene recarga de página</div>";
    } else {
        echo "<div style='color: red;'>❌ assignRole.js NO previene recarga de página</div>";
    }
} else {
    echo "<div style='color: red;'>❌ assignRole.js no encontrado</div>";
}

// 6. Instrucciones de prueba
echo "<h2>6. Instrucciones de prueba:</h2>";
echo "<ol>";
echo "<li><strong>Acceder al dashboard:</strong> <code>http://localhost:8000/?view=root&action=dashboard</code></li>";
echo "<li><strong>Hacer login como root</strong> (si no estás logueado)</li>";
echo "<li><strong>Ir a:</strong> Usuarios → Asignar rol</li>";
echo "<li><strong>Verificar que:</strong>";
echo "<ul>";
echo "<li>✅ No te saque del dashboard</li>";
echo "<li>✅ Se cargue la vista de asignación de roles</li>";
echo "<li>✅ El formulario de búsqueda funcione con AJAX</li>";
echo "<li>✅ Se pueda asignar roles sin recargar la página</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🎯 Dashboard listo para probar</p>";
echo "<p><strong>Nota:</strong> Asegúrate de estar logueado como usuario root para acceder al dashboard.</p>";
?> 