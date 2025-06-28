<?php
/**
 * Script para crear un usuario de prueba con rol de director
 * 
 * Este script crea un usuario que puede acceder a createSchool
 */

echo "<h1>Crear Usuario de Prueba - Director</h1>";

// Incluir las dependencias necesarias
require_once 'config.php';
require_once 'app/scripts/connection.php';

// Obtener conexión a la base de datos
$dbConn = getConnection();

// Datos del usuario de prueba
$testUser = [
    'credential_type' => 'CC',
    'credential_number' => '12345678',
    'first_name' => 'Director',
    'last_name' => 'Test',
    'date_of_birth' => '1980-01-01',
    'email' => 'director@test.com',
    'phone' => '3001234567',
    'address' => 'Calle Test 123',
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
        echo "<p><strong>Rol:</strong> director</p>";
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
            
            // Asignar rol de director
            $stmt = $dbConn->prepare("
                INSERT INTO user_roles (user_id, role_type, is_active) 
                VALUES (?, 'director', 1)
            ");
            $roleResult = $stmt->execute([$userId]);
            
            if ($roleResult) {
                echo "<div class='alert alert-success'>✅ Usuario creado exitosamente</div>";
                echo "<p><strong>Documento:</strong> " . $testUser['credential_type'] . " " . $testUser['credential_number'] . "</p>";
                echo "<p><strong>Contraseña:</strong> " . $testUser['password'] . "</p>";
                echo "<p><strong>Rol:</strong> director</p>";
                echo "<p><strong>ID:</strong> " . $userId . "</p>";
            } else {
                echo "<div class='alert alert-warning'>⚠️ Usuario creado pero error al asignar rol</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>❌ Error al crear el usuario</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
}

echo "<h2>Pasos para usar:</h2>";
echo "<ol>";
echo "<li>Ve a <a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a></li>";
echo "<li>Inicia sesión con las credenciales de arriba</li>";
echo "<li>Ve a <a href='http://localhost:8000/?view=director&action=dashboard' target='_blank'>Dashboard Director</a></li>";
echo "</ol>";

echo "<h2>Otros usuarios de prueba:</h2>";
echo "<ul>";
echo "<li><strong>Coordinador:</strong> CC 87654321 / 123456</li>";
echo "<li><strong>Tesorero:</strong> CC 11223344 / 123456</li>";
echo "</ul>";

echo "<h2>Crear otros usuarios:</h2>";
echo "<ul>";
echo "<li><a href='create-test-coordinator.php' target='_blank'>Crear Coordinador</a></li>";
echo "<li><a href='create-test-treasurer.php' target='_blank'>Crear Tesorero</a></li>";
echo "</ul>";
?> 