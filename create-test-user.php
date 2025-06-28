<?php
/**
 * Script para crear usuario de prueba
 */

echo "<h1>Crear Usuario de Prueba</h1>";

// 1. Definir ROOT y cargar conexión
define('ROOT', __DIR__ . '/');

try {
    require_once ROOT . 'app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<div style='color: green;'>✅ Conexión exitosa</div>";
    
    // 2. Verificar si el usuario ya existe
    echo "<h2>1. Verificar si el usuario existe:</h2>";
    $stmt = $dbConn->prepare("SELECT user_id, first_name, last_name FROM users WHERE credential_type = 'CC' AND credential_number = '1031180139'");
    $stmt->execute();
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingUser) {
        echo "<div style='color: blue;'>📋 Usuario ya existe: " . $existingUser['first_name'] . " " . $existingUser['last_name'] . " (ID: " . $existingUser['user_id'] . ")</div>";
    } else {
        echo "<div style='color: orange;'>⚠️ Usuario no existe, creando...</div>";
        
        // 3. Crear el usuario
        echo "<h2>2. Creando usuario:</h2>";
        $passwordHash = password_hash('123456', PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (credential_type, credential_number, first_name, last_name, 
                                    date_of_birth, email, phone, address, password_hash, salt_password, is_active) 
                  VALUES ('CC', '1031180139', 'Juan', 'Pérez', 
                          '1990-01-01', 'juan.perez@test.com', '3001234567', 'Calle 123 #45-67', 
                          :password_hash, '', 1)";
        
        $stmt = $dbConn->prepare($query);
        $result = $stmt->execute([':password_hash' => $passwordHash]);
        
        if ($result) {
            $userId = $dbConn->lastInsertId();
            echo "<div style='color: green;'>✅ Usuario creado exitosamente (ID: " . $userId . ")</div>";
            
            // 4. Verificar que se creó correctamente
            $stmt = $dbConn->prepare("SELECT user_id, first_name, last_name, credential_type, credential_number FROM users WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $userId]);
            $newUser = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "<div style='color: blue;'>📋 Usuario creado: " . $newUser['first_name'] . " " . $newUser['last_name'] . " (" . $newUser['credential_type'] . " " . $newUser['credential_number'] . ")</div>";
            
        } else {
            echo "<div style='color: red;'>❌ Error al crear usuario</div>";
        }
    }
    
    // 5. Verificar usuarios en la BD
    echo "<h2>3. Usuarios en la base de datos:</h2>";
    $stmt = $dbConn->query("SELECT user_id, credential_type, credential_number, first_name, last_name, is_active FROM users ORDER BY user_id DESC LIMIT 5");
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
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error: " . $e->getMessage() . "</div>";
}

echo "<hr>";
echo "<p><strong>Estado:</strong> 🎯 Usuario de prueba listo</p>";
echo "<p><strong>Próximos pasos:</strong></p>";
echo "<ol>";
echo "<li>Ejecutar: <code>F:\\xampp\\php\\php.exe test-database-structure.php</code></li>";
echo "<li>Ejecutar: <code>F:\\xampp\\php\\php.exe test-simple-user-model.php</code></li>";
echo "<li>Probar el sistema: <code>http://localhost:8000/?view=user&action=assignRole</code></li>";
echo "</ol>";
?> 