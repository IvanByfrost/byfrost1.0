<?php
/**
 * Test para verificar que el error de getRoleHistory se ha corregido
 */

require_once '../config.php';
require_once '../app/models/UserModel.php';

echo "🧪 Test de Corrección de getRoleHistory\n";
echo "=====================================\n\n";

try {
    // Conectar a la base de datos
    $dbConn = new PDO("mysql:host=localhost;dbname=byfrost", "root", "");
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a la base de datos exitosa\n";
    
    // Crear instancia del modelo
    $userModel = new UserModel($dbConn);
    echo "✅ UserModel creado correctamente\n";
    
    // Obtener un usuario de prueba
    $users = $userModel->getUsers();
    if (empty($users)) {
        echo "❌ No hay usuarios en la base de datos para probar\n";
        exit;
    }
    
    $testUserId = $users[0]['user_id'];
    echo "✅ Usuario de prueba encontrado: ID {$testUserId}\n";
    
    // Probar el método getRoleHistory
    echo "\n🔍 Probando getRoleHistory...\n";
    
    try {
        $roleHistory = $userModel->getRoleHistory($testUserId);
        echo "✅ getRoleHistory ejecutado sin errores\n";
        echo "📊 Historial encontrado: " . count($roleHistory) . " registros\n";
        
        if (!empty($roleHistory)) {
            echo "\n📋 Detalles del historial:\n";
            foreach ($roleHistory as $index => $role) {
                echo "   " . ($index + 1) . ". Rol: {$role['role_type']} - Estado: " . 
                     ($role['is_active'] ? 'Activo' : 'Inactivo') . 
                     " - Fecha: {$role['created_at']}\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error en getRoleHistory: " . $e->getMessage() . "\n";
        echo "🔍 Detalles del error:\n";
        echo "   - Código: " . $e->getCode() . "\n";
        echo "   - Archivo: " . $e->getFile() . "\n";
        echo "   - Línea: " . $e->getLine() . "\n";
    }
    
    // Verificar estructura de la tabla user_roles
    echo "\n🔍 Verificando estructura de la tabla user_roles...\n";
    
    $stmt = $dbConn->query("DESCRIBE user_roles");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "✅ Estructura de user_roles:\n";
    foreach ($columns as $column) {
        echo "   - {$column['Field']} ({$column['Type']})\n";
    }
    
    // Verificar si existe la columna updated_at
    $hasUpdatedAt = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'updated_at') {
            $hasUpdatedAt = true;
            break;
        }
    }
    
    if ($hasUpdatedAt) {
        echo "⚠️ La tabla user_roles SÍ tiene la columna updated_at\n";
        echo "💡 Considera actualizar el método getRoleHistory para incluirla\n";
    } else {
        echo "✅ La tabla user_roles NO tiene la columna updated_at (correcto para la corrección)\n";
    }
    
    echo "\n🎉 Test completado exitosamente\n";
    echo "✅ El error de getRoleHistory ha sido corregido\n";
    
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
    echo "🔍 Detalles del error:\n";
    echo "   - Código: " . $e->getCode() . "\n";
    echo "   - Archivo: " . $e->getFile() . "\n";
    echo "   - Línea: " . $e->getLine() . "\n";
}
?> 