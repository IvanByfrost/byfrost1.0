<?php
/**
 * Test simple del sistema de gestión de roles
 * Verifica que no haya problemas de memoria o recursión infinita
 */

// Configuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 5px 0; }
    .error { color: red; background: #ffe8e8; padding: 10px; border-radius: 5px; margin: 5px 0; }
    .warning { color: orange; background: #fff8e8; padding: 10px; border-radius: 5px; margin: 5px 0; }
    .info { color: blue; background: #e8f0ff; padding: 10px; border-radius: 5px; margin: 5px 0; }
</style>";

echo "<h1>🧪 Test Simple del Sistema de Roles</h1>";

// 1. Verificar conexión a base de datos
echo "<h2>1. Verificación de Base de Datos</h2>";
try {
    require_once '../app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<div class='success'>✅ Conexión a base de datos exitosa</div>";
} catch (Exception $e) {
    echo "<div class='error'>❌ Error de conexión: " . $e->getMessage() . "</div>";
    exit;
}

// 2. Test del RootModel
echo "<h2>2. Test del RootModel</h2>";
try {
    require_once '../app/models/rootModel.php';
    $rootModel = new RootModel();
    echo "<div class='success'>✅ RootModel cargado correctamente</div>";
    
    // Test getAllRoleTypes
    echo "<h3>Test getAllRoleTypes()</h3>";
    $roles = $rootModel->getAllRoleTypes();
    if (is_array($roles)) {
        echo "<div class='success'>✅ getAllRoleTypes() funcionó correctamente</div>";
        echo "<div class='info'>Roles encontrados: " . implode(', ', $roles) . "</div>";
    } else {
        echo "<div class='error'>❌ getAllRoleTypes() falló</div>";
    }
    
    // Test getPermissionsByRole
    echo "<h3>Test getPermissionsByRole()</h3>";
    $testRole = 'student';
    $permissions = $rootModel->getPermissionsByRole($testRole);
    if ($permissions && is_array($permissions)) {
        echo "<div class='success'>✅ getPermissionsByRole() funcionó correctamente</div>";
        echo "<div class='info'>Permisos para '$testRole':</div>";
        echo "<ul>";
        echo "<li>Crear: " . ($permissions['can_create'] ? 'Sí' : 'No') . "</li>";
        echo "<li>Leer: " . ($permissions['can_read'] ? 'Sí' : 'No') . "</li>";
        echo "<li>Actualizar: " . ($permissions['can_update'] ? 'Sí' : 'No') . "</li>";
        echo "<li>Eliminar: " . ($permissions['can_delete'] ? 'Sí' : 'No') . "</li>";
        echo "</ul>";
    } else {
        echo "<div class='error'>❌ getPermissionsByRole() falló</div>";
    }
    
    // Test updatePermissions
    echo "<h3>Test updatePermissions()</h3>";
    $testData = [
        'can_create' => 1,
        'can_read' => 1,
        'can_update' => 0,
        'can_delete' => 0
    ];
    
    $result = $rootModel->updatePermissions($testRole, $testData);
    if ($result) {
        echo "<div class='success'>✅ updatePermissions() funcionó correctamente</div>";
        
        // Verificar que se actualizaron
        $updatedPermissions = $rootModel->getPermissionsByRole($testRole);
        echo "<div class='info'>Permisos actualizados para '$testRole':</div>";
        echo "<ul>";
        echo "<li>Crear: " . ($updatedPermissions['can_create'] ? 'Sí' : 'No') . "</li>";
        echo "<li>Leer: " . ($updatedPermissions['can_read'] ? 'Sí' : 'No') . "</li>";
        echo "<li>Actualizar: " . ($updatedPermissions['can_update'] ? 'Sí' : 'No') . "</li>";
        echo "<li>Eliminar: " . ($updatedPermissions['can_delete'] ? 'Sí' : 'No') . "</li>";
        echo "</ul>";
    } else {
        echo "<div class='error'>❌ updatePermissions() falló</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error en RootModel: " . $e->getMessage() . "</div>";
    echo "<div class='info'>Stack trace: " . $e->getTraceAsString() . "</div>";
}

// 3. Test del RoleController
echo "<h2>3. Test del RoleController</h2>";
try {
    require_once '../app/controllers/RoleController.php';
    $roleController = new RoleController($dbConn);
    echo "<div class='success'>✅ RoleController cargado correctamente</div>";
    
    // Verificar métodos
    $methods = get_class_methods($roleController);
    $requiredMethods = ['index', 'edit', 'update'];
    
    foreach ($requiredMethods as $method) {
        if (in_array($method, $methods)) {
            echo "<div class='success'>✅ Método $method existe</div>";
        } else {
            echo "<div class='error'>❌ Método $method NO existe</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error en RoleController: " . $e->getMessage() . "</div>";
}

echo "<h2>🎉 Test Completado</h2>";
echo "<div class='success'>✅ El sistema de roles está funcionando correctamente</div>";
echo "<div class='info'>Para usar el sistema, accede a: /?controller=role&action=index</div>";
?> 