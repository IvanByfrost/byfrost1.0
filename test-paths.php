<?php
/**
 * Test para verificar que las rutas están correctas
 */

echo "<h1>Test: Verificación de Rutas</h1>";

// Definir ROOT correctamente - debe apuntar al directorio del proyecto byfrost
define('ROOT', __DIR__ . '/');

echo "<h2>1. Información de rutas:</h2>";
echo "<ul>";
echo "<li><strong>ROOT:</strong> " . ROOT . "</li>";
echo "<li><strong>__DIR__:</strong> " . __DIR__ . "</li>";
echo "<li><strong>dirname(__DIR__):</strong> " . dirname(__DIR__) . "</li>";
echo "</ul>";

echo "<h2>2. Verificar archivos importantes:</h2>";
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

echo "<h2>3. Probar inclusión de config.php:</h2>";
try {
    require_once ROOT . 'config.php';
    echo "<div class='alert alert-success'>✅ config.php cargado correctamente</div>";
    echo "<p><strong>URL:</strong> " . (defined('url') ? url : 'NO DEFINIDA') . "</p>";
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error al cargar config.php: " . $e->getMessage() . "</div>";
}

echo "<h2>4. Probar inclusión de connection.php:</h2>";
try {
    require_once ROOT . 'app/scripts/connection.php';
    echo "<div class='alert alert-success'>✅ connection.php cargado correctamente</div>";
    
    // Probar conexión
    $dbConn = getConnection();
    echo "<div class='alert alert-success'>✅ Conexión a BD exitosa</div>";
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error en connection.php: " . $e->getMessage() . "</div>";
}

echo "<h2>5. Probar UserController:</h2>";
try {
    require_once ROOT . 'app/controllers/UserController.php';
    echo "<div class='alert alert-success'>✅ UserController cargado correctamente</div>";
    
    $userController = new UserController($dbConn);
    echo "<div class='alert alert-success'>✅ UserController instanciado correctamente</div>";
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error en UserController: " . $e->getMessage() . "</div>";
}

echo "<h2>6. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139' target='_blank'>Asignar Roles (con parámetros)</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Verificación de rutas completada</p>";
?> 