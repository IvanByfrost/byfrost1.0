<?php
/**
 * Crear usuario de prueba para AssignRole
 */

echo "<h1>👤 Crear Usuario de Prueba</h1>";

// Incluir dependencias
require_once 'config.php';
require_once 'app/scripts/connection.php';
require_once 'app/models/UserModel.php';

$dbConn = getConnection();

echo "<h2>1. Verificar conexión a BD</h2>";
try {
    $stmt = $dbConn->query("SELECT 1");
    echo "<div class='alert alert-success'>✅ Conexión a BD exitosa</div>";
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error de conexión: " . $e->getMessage() . "</div>";
    exit;
}

echo "<h2>2. Verificar tablas</h2>";
try {
    // Verificar tabla users
    $stmt = $dbConn->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    echo "<div>✅ Tabla users: $userCount usuarios totales</div>";
    
    // Verificar tabla user_roles
    $stmt = $dbConn->query("SELECT COUNT(*) FROM user_roles");
    $roleCount = $stmt->fetchColumn();
    echo "<div>✅ Tabla user_roles: $roleCount roles totales</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error verificando tablas: " . $e->getMessage() . "</div>";
    exit;
}

echo "<h2>3. Crear usuario de prueba</h2>";

// Datos del usuario de prueba
$testUser = [
    'credential_type' => 'CC',
    'credential_number' => '1031180139',
    'first_name' => 'Juan',
    'last_name' => 'Pérez',
    'date_of_birth' => '1990-01-01',
    'email' => 'juan.perez@test.com',
    'phone' => '3001234567',
    'address' => 'Calle 123 #45-67',
    'password' => '123456'
];

try {
    $userModel = new UserModel($dbConn);
    
    // Verificar si el usuario ya existe
    $existingUsers = $userModel->searchUsersByDocument($testUser['credential_type'], $testUser['credential_number']);
    
    if (!empty($existingUsers)) {
        echo "<div class='alert alert-warning'>⚠️ El usuario ya existe:</div>";
        echo "<pre>" . print_r($existingUsers[0], true) . "</pre>";
    } else {
        // Crear el usuario
        $result = $userModel->createUser($testUser);
        
        if ($result) {
            echo "<div class='alert alert-success'>✅ Usuario creado exitosamente</div>";
            
            // Verificar que se creó
            $newUsers = $userModel->searchUsersByDocument($testUser['credential_type'], $testUser['credential_number']);
            if (!empty($newUsers)) {
                echo "<div>✅ Usuario verificado en BD:</div>";
                echo "<pre>" . print_r($newUsers[0], true) . "</pre>";
            }
        } else {
            echo "<div class='alert alert-danger'>❌ Error al crear usuario</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
}

echo "<h2>4. Verificar usuarios sin rol</h2>";
try {
    $usersWithoutRole = $userModel->getUsersWithoutRole();
    echo "<div>✅ Usuarios sin rol: " . count($usersWithoutRole) . "</div>";
    
    if (!empty($usersWithoutRole)) {
        echo "<div>Primeros 3 usuarios sin rol:</div>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Documento</th><th>Email</th></tr>";
        
        $count = 0;
        foreach ($usersWithoutRole as $user) {
            if ($count >= 3) break;
            echo "<tr>";
            echo "<td>" . $user['user_id'] . "</td>";
            echo "<td>" . $user['first_name'] . " " . $user['last_name'] . "</td>";
            echo "<td>" . $user['credential_type'] . " " . $user['credential_number'] . "</td>";
            echo "<td>" . ($user['email'] ?? 'No especificado') . "</td>";
            echo "</tr>";
            $count++;
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
}

echo "<h2>5. URLs para probar</h2>";
echo "<div class='d-grid gap-2'>";
echo "<a href='http://localhost:8000/?view=user&action=assignRole' class='btn btn-primary' target='_blank'>Ir a AssignRole</a>";
echo "<a href='http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139' class='btn btn-secondary' target='_blank'>AssignRole con búsqueda</a>";
echo "<a href='test-assign-role-complete.php' class='btn btn-info'>Volver al diagnóstico</a>";
echo "</div>";

echo "<h2>6. Información del usuario de prueba</h2>";
echo "<div class='alert alert-info'>";
echo "<strong>Documento:</strong> CC 1031180139<br>";
echo "<strong>Email:</strong> juan.perez@test.com<br>";
echo "<strong>Contraseña:</strong> 123456<br>";
echo "<strong>Nombre:</strong> Juan Pérez";
echo "</div>";

echo "<hr>";
echo "<p><strong>Estado:</strong> ✅ Usuario de prueba listo para AssignRole</p>";
?> 