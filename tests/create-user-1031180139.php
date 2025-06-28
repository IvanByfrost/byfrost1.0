<?php
/**
 * Script para crear un usuario específico con documento 1031180139
 */

echo "<h1>Crear Usuario Específico - 1031180139</h1>";

// Incluir las dependencias necesarias
require_once 'config.php';
require_once 'app/scripts/connection.php';

// Obtener conexión a la base de datos
$dbConn = getConnection();

// Datos del usuario específico
$testUser = [
    'credential_type' => 'CC',
    'credential_number' => '1031180139',
    'first_name' => 'Juan',
    'last_name' => 'Pérez',
    'date_of_birth' => '1990-03-15',
    'email' => 'juan.perez@test.com',
    'phone' => '3001234567',
    'address' => 'Calle Principal 123',
    'password' => '123456'
];

try {
    // Verificar si el usuario ya existe
    $stmt = $dbConn->prepare("SELECT user_id FROM users WHERE credential_type = ? AND credential_number = ?");
    $stmt->execute([$testUser['credential_type'], $testUser['credential_number']]);
    
    if ($stmt->fetch()) {
        echo "<div class='alert alert-warning'>⚠️ El usuario ya existe</div>";
        echo "<p><strong>Documento:</strong> " . $testUser['credential_type'] . " " . $testUser['credential_number'] . "</p>";
        echo "<p><strong>Contraseña:</strong> " . $testUser['password'] . "</p>";
        echo "<p><strong>Rol:</strong> Sin asignar</p>";
    } else {
        // Crear el usuario
        $hashedPassword = password_hash($testUser['password'], PASSWORD_DEFAULT);
        
        $stmt = $dbConn->prepare("
            INSERT INTO users (
                credential_type, 
                credential_number, 
                first_name, 
                last_name, 
                date_of_birth, 
                email, 
                phone, 
                address, 
                password_hash, 
                salt_password
            ) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $testUser['credential_type'],
            $testUser['credential_number'],
            $testUser['first_name'],
            $testUser['last_name'],
            $testUser['date_of_birth'],
            $testUser['email'],
            $testUser['phone'],
            $testUser['address'],
            $hashedPassword,
            '' // salt_password vacío ya que usamos password_hash
        ]);
        
        if ($result) {
            $userId = $dbConn->lastInsertId();
            echo "<div class='alert alert-success'>✅ Usuario creado exitosamente</div>";
            echo "<p><strong>Documento:</strong> " . $testUser['credential_type'] . " " . $testUser['credential_number'] . "</p>";
            echo "<p><strong>Nombre:</strong> " . $testUser['first_name'] . " " . $testUser['last_name'] . "</p>";
            echo "<p><strong>Contraseña:</strong> " . $testUser['password'] . "</p>";
            echo "<p><strong>Rol:</strong> Sin asignar (puedes asignarlo después)</p>";
            echo "<p><strong>ID:</strong> " . $userId . "</p>";
        } else {
            echo "<div class='alert alert-danger'>❌ Error al crear el usuario</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
}

echo "<h2>Ahora puedes probar:</h2>";
echo "<ol>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139' target='_blank'>Asignar rol a este usuario</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Sistema de asignación de roles</a></li>";
echo "<li><a href='http://localhost:8000/debug-assign-role.php' target='_blank'>Diagnóstico del sistema</a></li>";
echo "</ol>";

echo "<h2>Pasos para asignar rol:</h2>";
echo "<ol>";
echo "<li>Ve al sistema de asignación de roles</li>";
echo "<li>Busca por documento: CC 1031180139</li>";
echo "<li>Asigna el rol que necesites (director, coordinator, etc.)</li>";
echo "<li>Luego podrás usar este usuario para crear escuelas</li>";
echo "</ol>";

echo "<h2>Crear otros usuarios de prueba:</h2>";
echo "<ul>";
echo "<li><a href='create-test-director.php' target='_blank'>Crear Director</a></li>";
echo "<li><a href='create-test-coordinator.php' target='_blank'>Crear Coordinador</a></li>";
echo "<li><a href='create-test-treasurer.php' target='_blank'>Crear Tesorero</a></li>";
echo "</ul>";
?> 