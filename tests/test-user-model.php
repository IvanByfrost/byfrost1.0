<?php
/**
 * Test específico para UserModel y conexión
 */

echo "<h1>Test: UserModel y Conexión</h1>";

// 1. Definir ROOT
define('ROOT', __DIR__ . '/');

echo "<h2>1. Verificar archivos:</h2>";
$files = [
    'app/scripts/connection.php' => ROOT . 'app/scripts/connection.php',
    'app/models/mainModel.php' => ROOT . 'app/models/mainModel.php',
    'app/models/UserModel.php' => ROOT . 'app/models/UserModel.php'
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$name</strong>: " . ($exists ? "EXISTE" : "NO EXISTE") . "</div>";
}

// 2. Probar conexión directa
echo "<h2>2. Probar conexión directa:</h2>";
try {
    require_once ROOT . 'app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<div style='color: green;'>✅ Conexión directa exitosa</div>";
    
    // Probar consulta simple
    $stmt = $dbConn->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div style='color: green;'>✅ Consulta simple exitosa. Total usuarios: " . $result['total'] . "</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en conexión directa: " . $e->getMessage() . "</div>";
    exit;
}

// 3. Probar DatabaseConnection
echo "<h2>3. Probar DatabaseConnection:</h2>";
try {
    $dbInstance = DatabaseConnection::getInstance();
    $dbConn2 = $dbInstance->getConnection();
    echo "<div style='color: green;'>✅ DatabaseConnection exitoso</div>";
    
    // Probar consulta
    $stmt = $dbConn2->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div style='color: green;'>✅ Consulta con DatabaseConnection exitosa. Total usuarios: " . $result['total'] . "</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en DatabaseConnection: " . $e->getMessage() . "</div>";
}

// 4. Probar MainModel
echo "<h2>4. Probar MainModel:</h2>";
try {
    require_once ROOT . '/app/models/MainModel.php';
    $mainModel = new MainModel();
    echo "<div style='color: green;'>✅ MainModel creado exitosamente</div>";
    
    // Probar consulta desde MainModel
    $users = $mainModel->getAll('users');
    echo "<div style='color: green;'>✅ Consulta desde MainModel exitosa. Total usuarios: " . count($users) . "</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en MainModel: " . $e->getMessage() . "</div>";
}

// 5. Probar UserModel
echo "<h2>5. Probar UserModel:</h2>";
try {
    require_once ROOT . 'app/models/UserModel.php';
    $userModel = new UserModel($dbConn);
    echo "<div style='color: green;'>✅ UserModel creado exitosamente</div>";
    
    // Probar búsqueda de usuarios sin rol
    $usersWithoutRole = $userModel->getUsersWithoutRole();
    echo "<div style='color: green;'>✅ getUsersWithoutRole exitoso. Usuarios sin rol: " . count($usersWithoutRole) . "</div>";
    
    // Probar búsqueda por documento
    $searchResults = $userModel->searchUsersByDocument('CC', '1031180139');
    echo "<div style='color: green;'>✅ searchUsersByDocument exitoso. Resultados: " . count($searchResults) . "</div>";
    
    if (!empty($searchResults)) {
        echo "<div style='color: blue;'>📋 Usuario encontrado: " . $searchResults[0]['first_name'] . " " . $searchResults[0]['last_name'] . "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en UserModel: " . $e->getMessage() . "</div>";
    echo "<div style='color: red;'>Stack trace: " . $e->getTraceAsString() . "</div>";
}

// 6. Verificar estructura de la BD
echo "<h2>6. Verificar estructura de BD:</h2>";
try {
    // Verificar tabla users
    $stmt = $dbConn->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<div style='color: green;'>✅ Tabla users existe con " . count($columns) . " columnas</div>";
    
    // Verificar tabla user_roles
    $stmt = $dbConn->query("DESCRIBE user_roles");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<div style='color: green;'>✅ Tabla user_roles existe con " . count($columns) . " columnas</div>";
    
    // Mostrar algunos usuarios de ejemplo
    $stmt = $dbConn->query("SELECT user_id, credential_type, credential_number, first_name, last_name FROM users LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<div style='color: blue;'>📋 Usuarios en BD:</div>";
    foreach ($users as $user) {
        echo "<div>- " . $user['first_name'] . " " . $user['last_name'] . " (" . $user['credential_type'] . " " . $user['credential_number'] . ")</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error verificando estructura: " . $e->getMessage() . "</div>";
}

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Test de UserModel completado</p>";
?> 