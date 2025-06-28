<?php
/**
 * Test final para verificar rutas corregidas
 */

echo "<h1>Test Final: Verificación de Rutas</h1>";

// Simular exactamente lo que hace routerView.php
define('ROOT', dirname(dirname(dirname(__DIR__))) . '/');

echo "<h2>1. ROOT calculado:</h2>";
echo "<p><strong>ROOT:</strong> " . ROOT . "</p>";

echo "<h2>2. Rutas que se van a usar:</h2>";
$routes = [
    'config.php' => ROOT . 'config.php',
    'app/library/SecurityMiddleware.php' => ROOT . 'app/library/SecurityMiddleware.php',
    'app/scripts/connection.php' => ROOT . 'app/scripts/connection.php',
    'app/controllers/UserController.php' => ROOT . 'app/controllers/UserController.php',
    'app/views/user/assignRole.php' => ROOT . 'app/views/user/assignRole.php'
];

foreach ($routes as $name => $path) {
    $exists = file_exists($path);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$name</strong>: $path " . ($exists ? "(EXISTE)" : "(NO EXISTE)") . "</div>";
}

echo "<h2>3. Probar inclusión de config.php:</h2>";
try {
    require_once ROOT . 'config.php';
    echo "<div style='color: green;'>✅ config.php cargado correctamente</div>";
    echo "<p><strong>URL:</strong> " . (defined('url') ? url : 'NO DEFINIDA') . "</p>";
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error al cargar config.php: " . $e->getMessage() . "</div>";
}

echo "<h2>4. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139' target='_blank'>Asignar Roles (con parámetros)</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🎯 Rutas corregidas - ¡Listo para probar!</p>";
?> 