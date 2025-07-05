<?php
/**
 * Test para verificar que los usuarios nuevos NO se asignan automáticamente como estudiantes
 * 
 * Este script prueba:
 * 1. Creación de usuario sin asignación automática de rol
 * 2. Verificación de que el usuario aparece sin rol (NULL)
 * 3. Asignación manual de rol
 */

require_once '../app/scripts/connection.php';
require_once '../app/models/userModel.php';

$dbConn = getConnection();
$userModel = new UserModel($dbConn);

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Test - Usuarios Sin Rol Automático</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        .warning { background-color: #fff3cd; border-color: #ffeaa7; color: #856404; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .null-role { background-color: #ffe6e6; font-style: italic; }
    </style>
</head>
<body>";

echo "<h1>🧪 Test - Usuarios Sin Rol Automático</h1>";

// 1. Verificar estado actual de usuarios
echo "<div class='test-section info'>
    <h3>1. Estado Actual de Usuarios</h3>";

try {
    $users = $userModel->getUsers();
    
    if (empty($users)) {
        echo "<p class='error'>❌ No hay usuarios en el sistema.</p>";
    } else {
        echo "<table>
            <tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Tipo Doc</th><th>Número Doc</th><th>Rol</th></tr>";
        
        $usersWithRole = 0;
        $usersWithoutRole = 0;
        
        foreach ($users as $user) {
            $roleClass = ($user['role_type'] === null) ? 'class="null-role"' : '';
            $roleDisplay = ($user['role_type'] === null) ? '<em>Sin rol</em>' : $user['role_type'];
            
            echo "<tr {$roleClass}>
                <td>{$user['user_id']}</td>
                <td>{$user['first_name']}</td>
                <td>{$user['last_name']}</td>
                <td>{$user['email']}</td>
                <td>{$user['credential_type']}</td>
                <td>{$user['credential_number']}</td>
                <td>{$roleDisplay}</td>
            </tr>";
            
            if ($user['role_type'] === null) {
                $usersWithoutRole++;
            } else {
                $usersWithRole++;
            }
        }
        
        echo "</table>";
        echo "<p><strong>Resumen:</strong></p>";
        echo "<ul>
            <li>Usuarios con rol: {$usersWithRole}</li>
            <li>Usuarios sin rol: {$usersWithoutRole}</li>
            <li>Total: " . count($users) . "</li>
        </ul>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error al obtener usuarios: " . $e->getMessage() . "</p>";
}

echo "</div>";

// 2. Crear un usuario de prueba
echo "<div class='test-section info'>
    <h3>2. Crear Usuario de Prueba</h3>";

try {
    $testUserData = [
        'credential_type' => 'CC',
        'credential_number' => 'TEST' . time(), // Número único
        'first_name' => 'Usuario',
        'last_name' => 'Prueba',
        'email' => 'test' . time() . '@example.com', // Email único
        'password' => 'password123',
        'date_of_birth' => null,
        'phone' => null,
        'address' => null
    ];
    
    echo "<p><strong>Datos del usuario de prueba:</strong></p>";
    echo "<ul>
        <li>Nombre: {$testUserData['first_name']} {$testUserData['last_name']}</li>
        <li>Documento: {$testUserData['credential_type']} {$testUserData['credential_number']}</li>
        <li>Email: {$testUserData['email']}</li>
    </ul>";
    
    $result = $userModel->createUser($testUserData);
    
    if ($result) {
        echo "<p class='success'>✅ Usuario creado exitosamente.</p>";
        
        // Verificar que el usuario se creó sin rol
        $newUser = $userModel->searchUsersByDocument($testUserData['credential_type'], $testUserData['credential_number']);
        
        if (!empty($newUser)) {
            $user = $newUser[0];
            echo "<p><strong>Usuario creado:</strong></p>";
            echo "<ul>
                <li>ID: {$user['user_id']}</li>
                <li>Nombre: {$user['first_name']} {$user['last_name']}</li>
                <li>Email: {$user['email']}</li>
                <li>Rol: " . ($user['role_type'] === null ? '<em>Sin rol</em>' : $user['role_type']) . "</li>
            </ul>";
            
            if ($user['role_type'] === null) {
                echo "<p class='success'>✅ El usuario se creó correctamente sin rol asignado.</p>";
            } else {
                echo "<p class='error'>❌ El usuario se creó con rol asignado automáticamente.</p>";
            }
            
            // Limpiar: eliminar el usuario de prueba
            $userModel->deleteUserPermanently($user['user_id']);
            echo "<p class='info'>🔄 Usuario de prueba eliminado.</p>";
            
        } else {
            echo "<p class='error'>❌ No se pudo encontrar el usuario creado.</p>";
        }
        
    } else {
        echo "<p class='error'>❌ Error al crear el usuario.</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error en la creación: " . $e->getMessage() . "</p>";
}

echo "</div>";

// 3. Probar asignación manual de rol
echo "<div class='test-section info'>
    <h3>3. Prueba de Asignación Manual de Rol</h3>";

try {
    // Crear otro usuario de prueba
    $testUserData2 = [
        'credential_type' => 'TI',
        'credential_number' => 'TEST2' . time(),
        'first_name' => 'Usuario',
        'last_name' => 'ConRol',
        'email' => 'test2' . time() . '@example.com',
        'password' => 'password123',
        'date_of_birth' => null,
        'phone' => null,
        'address' => null
    ];
    
    $result = $userModel->createUser($testUserData2);
    
    if ($result) {
        $newUser = $userModel->searchUsersByDocument($testUserData2['credential_type'], $testUserData2['credential_number']);
        
        if (!empty($newUser)) {
            $user = $newUser[0];
            echo "<p><strong>Usuario creado:</strong> {$user['first_name']} {$user['last_name']} (ID: {$user['user_id']})</p>";
            echo "<p>Rol inicial: " . ($user['role_type'] === null ? '<em>Sin rol</em>' : $user['role_type']) . "</p>";
            
            // Asignar rol manualmente
            $assignResult = $userModel->assignRole($user['user_id'], 'student');
            
            if ($assignResult) {
                echo "<p class='success'>✅ Rol 'student' asignado manualmente.</p>";
                
                // Verificar el cambio
                $updatedUser = $userModel->getUser($user['user_id']);
                echo "<p>Rol después de asignación: " . ($updatedUser['role_type'] ?? '<em>Sin rol</em>') . "</p>";
                
                if ($updatedUser['role_type'] === 'student') {
                    echo "<p class='success'>✅ La asignación manual de rol funciona correctamente.</p>";
                } else {
                    echo "<p class='error'>❌ La asignación manual de rol no funcionó.</p>";
                }
                
            } else {
                echo "<p class='error'>❌ Error al asignar rol manualmente.</p>";
            }
            
            // Limpiar
            $userModel->deleteUserPermanently($user['user_id']);
            echo "<p class='info'>🔄 Usuario de prueba eliminado.</p>";
            
        } else {
            echo "<p class='error'>❌ No se pudo encontrar el usuario creado.</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error en la prueba de asignación: " . $e->getMessage() . "</p>";
}

echo "</div>";

// 4. Verificar método getUsersWithoutRole
echo "<div class='test-section info'>
    <h3>4. Verificar Método getUsersWithoutRole</h3>";

try {
    $usersWithoutRole = $userModel->getUsersWithoutRole();
    
    echo "<p><strong>Usuarios sin rol asignado:</strong> " . count($usersWithoutRole) . "</p>";
    
    if (!empty($usersWithoutRole)) {
        echo "<table>
            <tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Documento</th></tr>";
        
        foreach ($usersWithoutRole as $user) {
            echo "<tr>
                <td>{$user['user_id']}</td>
                <td>{$user['first_name']}</td>
                <td>{$user['last_name']}</td>
                <td>{$user['email']}</td>
                <td>{$user['credential_type']} {$user['credential_number']}</td>
            </tr>";
        }
        
        echo "</table>";
        echo "<p class='success'>✅ El método getUsersWithoutRole funciona correctamente.</p>";
    } else {
        echo "<p class='warning'>⚠️ No hay usuarios sin rol asignado en el sistema.</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error al obtener usuarios sin rol: " . $e->getMessage() . "</p>";
}

echo "</div>";

// 5. Resumen y recomendaciones
echo "<div class='test-section success'>
    <h3>✅ Resumen de Cambios</h3>
    <p>Se han realizado los siguientes cambios para que los usuarios nuevos NO se asignen automáticamente como estudiantes:</p>
    <ul>
        <li>✅ <strong>createUser:</strong> Comentada la asignación automática de rol 'student'</li>
        <li>✅ <strong>getUsers:</strong> Modificada la consulta para mostrar todos los usuarios activos</li>
        <li>✅ <strong>getUser:</strong> Actualizada la consulta para ser consistente</li>
    </ul>
    
    <h4>Comportamiento Esperado:</h4>
    <ul>
        <li>🔹 Los usuarios nuevos aparecerán con rol <em>NULL</em> (Sin rol)</li>
        <li>🔹 Los administradores deberán asignar roles manualmente</li>
        <li>🔹 Se pueden usar los métodos existentes para asignar roles</li>
        <li>🔹 El método <code>getUsersWithoutRole()</code> mostrará usuarios pendientes de asignación</li>
    </ul>
    
    <h4>Próximos Pasos:</h4>
    <ul>
        <li>🔸 Revisar la interfaz de administración para mostrar usuarios sin rol</li>
        <li>🔸 Implementar notificaciones para usuarios sin rol asignado</li>
        <li>🔸 Considerar un flujo de asignación de roles más eficiente</li>
    </ul>
</div>";

echo "</body></html>";
?> 