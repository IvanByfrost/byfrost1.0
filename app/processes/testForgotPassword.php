<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/app/scripts/connection.php';

try {
    $dbConn = getConnection();
    echo "✅ Conexión a BD exitosa<br>";
    
    // Verificar si la tabla password_resets existe
    $query = "SHOW TABLES LIKE 'password_resets'";
    $stmt = $dbConn->query($query);
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "✅ Tabla password_resets existe<br>";
    } else {
        echo "❌ Tabla password_resets NO existe<br>";
        echo "Ejecuta el SQL: app/scripts/password_resets_table.sql<br>";
    }
    
    // Verificar si la tabla users existe
    $query = "SHOW TABLES LIKE 'users'";
    $stmt = $dbConn->query($query);
    $usersTableExists = $stmt->rowCount() > 0;
    
    if ($usersTableExists) {
        echo "✅ Tabla users existe<br>";
        
        // Contar usuarios
        $query = "SELECT COUNT(*) FROM users";
        $stmt = $dbConn->query($query);
        $userCount = $stmt->fetchColumn();
        echo "📊 Usuarios en BD: $userCount<br>";
    } else {
        echo "❌ Tabla users NO existe<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?> 