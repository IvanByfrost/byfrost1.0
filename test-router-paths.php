<?php
/**
 * Test para simular exactamente la lógica de rutas del routerView.php
 */

echo "<h1>Test: Simulación de RouterView</h1>";

// Simular estar en app/scripts/routerView.php
$currentDir = __DIR__ . '/app/scripts/';
echo "<h2>1. Simulando desde app/scripts/routerView.php:</h2>";
echo "<ul>";
echo "<li><strong>Directorio actual (simulado):</strong> " . $currentDir . "</li>";
echo "<li><strong>__DIR__ (real):</strong> " . __DIR__ . "</li>";
echo "</ul>";

// Definir ROOT como lo hace routerView.php
define('ROOT', dirname(dirname(dirname($currentDir))) . '/');

echo "<h2>2. ROOT calculado:</h2>";
echo "<p><strong>ROOT:</strong> " . ROOT . "</p>";

echo "<h2>3. Verificar archivos importantes:</h2>";
$files = [
    'config.php' => ROOT . 'config.php',
    'app/scripts/connection.php' => ROOT . 'app/scripts/connection.php',
    'app/library/SecurityMiddleware.php' => ROOT . 'app/library/SecurityMiddleware.php',
    'app/controllers/UserController.php' => ROOT . 'app/controllers/UserController.php',
    'app/views/user/assignRole.php' => ROOT . 'app/views/user/assignRole.php'
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$name</strong>: $path " . ($exists ? "(EXISTE)" : "(NO EXISTE)") . "</div>";
}

echo "<h2>4. Probar inclusión de config.php:</h2>";
try {
    require_once ROOT . 'config.php';
    echo "<div style='color: green;'>✅ config.php cargado correctamente</div>";
    echo "<p><strong>URL:</strong> " . (defined('url') ? url : 'NO DEFINIDA') . "</p>";
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error al cargar config.php: " . $e->getMessage() . "</div>";
}

echo "<h2>5. Probar inclusión de connection.php:</h2>";
try {
    require_once ROOT . 'app/scripts/connection.php';
    echo "<div style='color: green;'>✅ connection.php cargado correctamente</div>";
    
    // Probar conexión
    $dbConn = getConnection();
    echo "<div style='color: green;'>✅ Conexión a BD exitosa</div>";
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en connection.php: " . $e->getMessage() . "</div>";
}

echo "<h2>6. Probar UserController:</h2>";
try {
    require_once ROOT . 'app/controllers/UserController.php';
    echo "<div style='color: green;'>✅ UserController cargado correctamente</div>";
    
    $userController = new UserController($dbConn);
    echo "<div style='color: green;'>✅ UserController instanciado correctamente</div>";
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en UserController: " . $e->getMessage() . "</div>";
}

echo "<h2>7. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139' target='_blank'>Asignar Roles (con parámetros)</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Simulación de RouterView completada</p>";
?> 