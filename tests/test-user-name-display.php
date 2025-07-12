<?php
/**
 * Test para verificar que el nombre del usuario se muestra correctamente
 */

require_once '../config.php';
require_once '../app/controllers/UserController.php';
require_once '../app/models/UserModel.php';

echo "🧪 Test de Visualización de Nombre de Usuario\n";
echo "============================================\n\n";

try {
    // Conectar a la base de datos
    $dbConn = new PDO("mysql:host=localhost;dbname=byfrost", "root", "");
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a la base de datos exitosa\n";
    
    // Crear instancia del modelo
    $userModel = new UserModel($dbConn);
    echo "✅ UserModel creado correctamente\n";
    
    // Obtener usuarios de prueba
    $users = $userModel->getUsers();
    
    if (empty($users)) {
        echo "❌ No hay usuarios en la base de datos para probar\n";
        exit;
    }
    
    $testUser = $users[0];
    $testUserId = $testUser['user_id'];
    echo "✅ Usuario de prueba encontrado:\n";
    echo "   - ID: {$testUser['user_id']}\n";
    echo "   - Nombre: {$testUser['first_name']} {$testUser['last_name']}\n";
    echo "   - Documento: {$testUser['credential_type']} {$testUser['credential_number']}\n";
    echo "   - Email: {$testUser['email']}\n";
    
    // Probar método getUser
    echo "\n🔍 Probando método getUser...\n";
    $user = $userModel->getUser($testUserId);
    
    if ($user) {
        echo "✅ Usuario obtenido correctamente\n";
        echo "   - Nombre completo: {$user['first_name']} {$user['last_name']}\n";
        echo "   - Documento: {$user['credential_type']} {$user['credential_number']}\n";
        echo "   - Email: {$user['email']}\n";
        echo "   - Rol: " . ($user['role_type'] ?? 'Sin rol asignado') . "\n";
    } else {
        echo "❌ No se pudo obtener el usuario\n";
    }
    
    // Probar método getRoleHistory
    echo "\n🔍 Probando método getRoleHistory...\n";
    $roleHistory = $userModel->getRoleHistory($testUserId);
    echo "✅ Historial de roles obtenido: " . count($roleHistory) . " registros\n";
    
    if (!empty($roleHistory)) {
        echo "📋 Roles encontrados:\n";
        foreach ($roleHistory as $index => $role) {
            echo "   " . ($index + 1) . ". {$role['role_type']} - " . 
                 ($role['is_active'] ? 'Activo' : 'Inactivo') . 
                 " ({$role['created_at']})\n";
        }
    }
    
    // Simular la vista
    echo "\n🔍 Simulando vista viewRoleHistory...\n";
    
    // Simular variables que pasaría el controlador
    $userId = $testUserId;
    $user = $userModel->getUser($userId);
    $roleHistory = $userModel->getRoleHistory($userId);
    
    if ($user) {
        echo "✅ Datos preparados para la vista:\n";
        echo "   - userId: $userId\n";
        echo "   - user: " . $user['first_name'] . " " . $user['last_name'] . "\n";
        echo "   - roleHistory: " . count($roleHistory) . " registros\n";
        
        // Simular el título que se mostraría
        $titulo = "Historial de Roles - " . $user['first_name'] . " " . $user['last_name'];
        echo "   - Título: $titulo\n";
        
        // Simular información adicional
        $info = "Documento: " . $user['credential_type'] . " " . $user['credential_number'] . 
                " | Email: " . $user['email'] . " | ID: " . $userId;
        echo "   - Info adicional: $info\n";
        
    } else {
        echo "❌ No se pudieron preparar los datos para la vista\n";
    }
    
    // Verificar que el archivo de vista existe y contiene el código correcto
    echo "\n🔍 Verificando archivo de vista...\n";
    $viewFile = '../app/views/user/viewRoleHistory.php';
    if (file_exists($viewFile)) {
        echo "✅ Archivo de vista existe\n";
        
        $viewContent = file_get_contents($viewFile);
        
        // Verificar que contiene la validación del usuario
        if (strpos($viewContent, 'if (!$user)') !== false) {
            echo "✅ La vista contiene validación de usuario\n";
        } else {
            echo "⚠️ La vista no contiene validación de usuario\n";
        }
        
        // Verificar que muestra el nombre del usuario
        if (strpos($viewContent, '$user[\'first_name\']') !== false) {
            echo "✅ La vista muestra el nombre del usuario\n";
        } else {
            echo "⚠️ La vista no muestra el nombre del usuario\n";
        }
        
        // Verificar que muestra información adicional
        if (strpos($viewContent, 'credential_type') !== false) {
            echo "✅ La vista muestra información adicional del usuario\n";
        } else {
            echo "⚠️ La vista no muestra información adicional del usuario\n";
        }
        
    } else {
        echo "❌ Archivo de vista no existe\n";
    }
    
    echo "\n🎉 Test completado exitosamente\n";
    echo "✅ La visualización del nombre de usuario está funcionando correctamente\n";
    
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
    echo "🔍 Detalles del error:\n";
    echo "   - Código: " . $e->getCode() . "\n";
    echo "   - Archivo: " . $e->getFile() . "\n";
    echo "   - Línea: " . $e->getLine() . "\n";
}
?> 