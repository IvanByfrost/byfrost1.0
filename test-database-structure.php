<?php
/**
 * Test para verificar la estructura de la base de datos
 */

echo "<h1>Test: Estructura de Base de Datos</h1>";

// 1. Definir ROOT y cargar conexión
define('ROOT', __DIR__ . '/');

try {
    require_once ROOT . 'app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<div style='color: green;'>✅ Conexión exitosa</div>";
    
    // 2. Verificar tablas
    echo "<h2>1. Verificar tablas:</h2>";
    $stmt = $dbConn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<div>Tablas encontradas: " . implode(', ', $tables) . "</div>";
    
    // 3. Verificar estructura de tabla users
    echo "<h2>2. Estructura de tabla users:</h2>";
    $stmt = $dbConn->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 4. Verificar estructura de tabla user_roles
    echo "<h2>3. Estructura de tabla user_roles:</h2>";
    $stmt = $dbConn->query("DESCRIBE user_roles");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 5. Verificar datos en tabla users
    echo "<h2>4. Datos en tabla users:</h2>";
    $stmt = $dbConn->query("SELECT user_id, credential_type, credential_number, first_name, last_name, is_active FROM users LIMIT 10");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Tipo Doc</th><th>Número</th><th>Nombre</th><th>Apellido</th><th>Activo</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['user_id'] . "</td>";
        echo "<td>" . $user['credential_type'] . "</td>";
        echo "<td>" . $user['credential_number'] . "</td>";
        echo "<td>" . $user['first_name'] . "</td>";
        echo "<td>" . $user['last_name'] . "</td>";
        echo "<td>" . $user['is_active'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 6. Verificar datos en tabla user_roles
    echo "<h2>5. Datos en tabla user_roles:</h2>";
    $stmt = $dbConn->query("SELECT user_id, role_type, is_active FROM user_roles LIMIT 10");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>User ID</th><th>Rol</th><th>Activo</th></tr>";
    foreach ($roles as $role) {
        echo "<tr>";
        echo "<td>" . $role['user_id'] . "</td>";
        echo "<td>" . $role['role_type'] . "</td>";
        echo "<td>" . $role['is_active'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 7. Probar consulta específica
    echo "<h2>6. Probar consulta específica:</h2>";
    try {
        $query = "SELECT 
                    u.user_id,
                    u.credential_type,
                    u.credential_number,
                    u.first_name,
                    u.last_name,
                    u.email,
                    u.phone,
                    u.address,
                    ur.role_type as current_role
                  FROM users u
                  LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1
                  WHERE u.credential_type = 'CC' 
                  AND u.credential_number = '1031180139'
                  AND u.is_active = 1
                  ORDER BY u.first_name, u.last_name";
        
        echo "<div>Query: " . htmlspecialchars($query) . "</div>";
        
        $stmt = $dbConn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div style='color: green;'>✅ Consulta ejecutada exitosamente</div>";
        echo "<div>Resultados encontrados: " . count($results) . "</div>";
        
        if (!empty($results)) {
            echo "<div style='color: blue;'>📋 Usuario encontrado: " . $results[0]['first_name'] . " " . $results[0]['last_name'] . "</div>";
        } else {
            echo "<div style='color: orange;'>⚠️ No se encontró el usuario 1031180139</div>";
        }
        
    } catch (Exception $e) {
        echo "<div style='color: red;'>❌ Error en consulta: " . $e->getMessage() . "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error: " . $e->getMessage() . "</div>";
}

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Verificación de estructura completada</p>";
?> 