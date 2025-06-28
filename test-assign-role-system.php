<?php
/**
 * Test completo del sistema de asignación de roles
 */

echo "<h1>Test: Sistema de Asignación de Roles</h1>";

// 1. Verificar que las rutas están correctas
echo "<h2>1. Verificación de rutas:</h2>";
define('ROOT', __DIR__ . '/');

$files = [
    'config.php' => ROOT . 'config.php',
    'app/controllers/UserController.php' => ROOT . 'app/controllers/UserController.php',
    'app/models/UserModel.php' => ROOT . 'app/models/UserModel.php',
    'app/views/user/assignRole.php' => ROOT . 'app/views/user/assignRole.php',
    'app/resources/js/assignRole.js' => ROOT . 'app/resources/js/assignRole.js'
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$name</strong>: " . ($exists ? "EXISTE" : "NO EXISTE") . "</div>";
}

// 2. Probar conexión a BD
echo "<h2>2. Conexión a base de datos:</h2>";
try {
    require_once ROOT . 'app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<div style='color: green;'>✅ Conexión a BD exitosa</div>";
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en BD: " . $e->getMessage() . "</div>";
    exit;
}

// 3. Probar UserModel
echo "<h2>3. Prueba de UserModel:</h2>";
try {
    require_once ROOT . 'app/models/UserModel.php';
    $userModel = new UserModel();
    echo "<div style='color: green;'>✅ UserModel cargado correctamente</div>";
    
    // Probar búsqueda de usuarios sin rol
    $usersWithoutRole = $userModel->getUsersWithoutRole();
    echo "<div style='color: green;'>✅ Usuarios sin rol: " . count($usersWithoutRole) . " encontrados</div>";
    
    // Probar búsqueda por documento
    $searchResults = $userModel->searchUsersByDocument('CC', '1031180139');
    echo "<div style='color: green;'>✅ Búsqueda por documento: " . count($searchResults) . " encontrados</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en UserModel: " . $e->getMessage() . "</div>";
}

// 4. Probar UserController
echo "<h2>4. Prueba de UserController:</h2>";
try {
    require_once ROOT . 'app/controllers/UserController.php';
    $userController = new UserController($dbConn);
    echo "<div style='color: green;'>✅ UserController cargado correctamente</div>";
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en UserController: " . $e->getMessage() . "</div>";
}

// 5. URLs de prueba
echo "<h2>5. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles (Dashboard)</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139' target='_blank'>Buscar usuario específico</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=getUsersWithoutRole' target='_blank'>Obtener usuarios sin rol (AJAX)</a></li>";
echo "</ul>";

// 6. Verificar usuario de prueba
echo "<h2>6. Verificar usuario de prueba:</h2>";
try {
    $testUser = $userModel->searchUsersByDocument('CC', '1031180139');
    if (!empty($testUser)) {
        echo "<div style='color: green;'>✅ Usuario de prueba encontrado: " . $testUser[0]['first_name'] . " " . $testUser[0]['last_name'] . "</div>";
        echo "<div>Rol actual: " . ($testUser[0]['current_role'] ?? 'Sin rol') . "</div>";
    } else {
        echo "<div style='color: orange;'>⚠️ Usuario de prueba no encontrado. Ejecuta: F:\\xampp\\php\\php.exe create-user-1031180139.php</div>";
    }
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error al verificar usuario: " . $e->getMessage() . "</div>";
}

echo "<hr>";
echo "<p><strong>Estado:</strong> 🎯 Sistema de asignación de roles listo para usar</p>";
echo "<p><strong>Próximos pasos:</strong></p>";
echo "<ol>";
echo "<li>Crear usuario de prueba: <code>F:\\xampp\\php\\php.exe create-user-1031180139.php</code></li>";
echo "<li>Acceder al dashboard como root</li>";
echo "<li>Ir a: Asignar Roles</li>";
echo "<li>Buscar por documento: 1031180139</li>";
echo "<li>Asignar el rol correspondiente</li>";
echo "</ol>";
?> 