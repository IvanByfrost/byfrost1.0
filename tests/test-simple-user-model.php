<?php
/**
 * Test simple del UserModel corregido
 */

echo "<h1>Test Simple: UserModel Corregido</h1>";

// 1. Definir ROOT y cargar conexión
define('ROOT', __DIR__ . '/');

try {
    require_once ROOT . 'app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<div style='color: green;'>✅ Conexión exitosa</div>";
    
    // 2. Probar UserModel con conexión
    require_once ROOT . 'app/models/UserModel.php';
    $userModel = new UserModel($dbConn);
    echo "<div style='color: green;'>✅ UserModel creado con conexión</div>";
    
    // 3. Probar búsqueda por documento
    $searchResults = $userModel->searchUsersByDocument('CC', '1031180139');
    echo "<div style='color: green;'>✅ Búsqueda exitosa. Resultados: " . count($searchResults) . "</div>";
    
    if (!empty($searchResults)) {
        echo "<div style='color: blue;'>📋 Usuario encontrado: " . $searchResults[0]['first_name'] . " " . $searchResults[0]['last_name'] . "</div>";
        echo "<div style='color: blue;'>📋 Rol actual: " . ($searchResults[0]['user_role'] ?? 'Sin rol') . "</div>";
    } else {
        echo "<div style='color: orange;'>⚠️ No se encontró el usuario 1031180139</div>";
    }
    
    // 4. Probar usuarios sin rol
    $usersWithoutRole = $userModel->getUsersWithoutRole();
    echo "<div style='color: green;'>✅ Usuarios sin rol: " . count($usersWithoutRole) . "</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error: " . $e->getMessage() . "</div>";
    echo "<div style='color: red;'>Stack trace: " . $e->getTraceAsString() . "</div>";
}

echo "<hr>";
echo "<p><strong>Estado:</strong> 🎯 Test completado</p>";
?> 