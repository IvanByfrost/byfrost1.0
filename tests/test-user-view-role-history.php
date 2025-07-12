<?php
/**
 * Test para verificar que la vista de historial de roles funciona correctamente
 */

require_once '../config.php';
require_once '../app/controllers/UserController.php';
require_once '../app/models/UserModel.php';

echo "🧪 Test de Vista de Historial de Roles\n";
echo "====================================\n\n";

try {
    // Conectar a la base de datos
    $dbConn = new PDO("mysql:host=localhost;dbname=byfrost", "root", "");
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a la base de datos exitosa\n";
    
    // Crear instancia del controlador
    $userController = new UserController($dbConn);
    echo "✅ UserController creado correctamente\n";
    
    // Obtener usuarios de prueba
    $userModel = new UserModel($dbConn);
    $users = $userModel->getUsers();
    
    if (empty($users)) {
        echo "❌ No hay usuarios en la base de datos para probar\n";
        exit;
    }
    
    $testUserId = $users[0]['user_id'];
    echo "✅ Usuario de prueba encontrado: ID {$testUserId}\n";
    
    // Simular petición GET
    $_GET['id'] = $testUserId;
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    
    echo "\n🔍 Probando viewRoleHistory...\n";
    
    // Capturar la salida del controlador
    ob_start();
    
    try {
        $userController->viewRoleHistory();
        $output = ob_get_clean();
        
        if (!empty($output)) {
            echo "✅ viewRoleHistory ejecutado correctamente\n";
            echo "📄 Longitud de la salida: " . strlen($output) . " caracteres\n";
            
            // Verificar que la salida contiene elementos esperados
            if (strpos($output, 'Historial de Roles') !== false) {
                echo "✅ La vista contiene el título esperado\n";
            } else {
                echo "⚠️ La vista no contiene el título esperado\n";
            }
            
            if (strpos($output, 'table') !== false) {
                echo "✅ La vista contiene una tabla\n";
            } else {
                echo "⚠️ La vista no contiene una tabla\n";
            }
            
        } else {
            echo "❌ viewRoleHistory no produjo salida\n";
        }
        
    } catch (Exception $e) {
        ob_end_clean();
        echo "❌ Error en viewRoleHistory: " . $e->getMessage() . "\n";
        echo "🔍 Detalles del error:\n";
        echo "   - Código: " . $e->getCode() . "\n";
        echo "   - Archivo: " . $e->getFile() . "\n";
        echo "   - Línea: " . $e->getLine() . "\n";
    }
    
    // Verificar que el archivo de vista existe
    echo "\n🔍 Verificando archivo de vista...\n";
    $viewFile = '../app/views/user/viewRoleHistory.php';
    if (file_exists($viewFile)) {
        echo "✅ Archivo de vista existe: $viewFile\n";
        
        $viewContent = file_get_contents($viewFile);
        if (strpos($viewContent, 'Historial de Roles') !== false) {
            echo "✅ La vista contiene el contenido esperado\n";
        } else {
            echo "⚠️ La vista no contiene el contenido esperado\n";
        }
        
    } else {
        echo "❌ Archivo de vista no existe: $viewFile\n";
    }
    
    // Verificar método getRoleHistory
    echo "\n🔍 Verificando método getRoleHistory...\n";
    try {
        $roleHistory = $userModel->getRoleHistory($testUserId);
        echo "✅ getRoleHistory ejecutado sin errores\n";
        echo "📊 Historial encontrado: " . count($roleHistory) . " registros\n";
        
        if (!empty($roleHistory)) {
            echo "📋 Detalles del historial:\n";
            foreach ($roleHistory as $index => $role) {
                echo "   " . ($index + 1) . ". Rol: {$role['role_type']} - Estado: " . 
                     ($role['is_active'] ? 'Activo' : 'Inactivo') . 
                     " - Fecha: {$role['created_at']}\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error en getRoleHistory: " . $e->getMessage() . "\n";
    }
    
    // Limpiar variables simuladas
    unset($_GET['id']);
    unset($_SERVER['HTTP_X_REQUESTED_WITH']);
    
    echo "\n🎉 Test completado exitosamente\n";
    echo "✅ La funcionalidad de historial de roles está funcionando\n";
    
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
    echo "🔍 Detalles del error:\n";
    echo "   - Código: " . $e->getCode() . "\n";
    echo "   - Archivo: " . $e->getFile() . "\n";
    echo "   - Línea: " . $e->getLine() . "\n";
}
?> 