<?php
/**
 * Test completo del sistema de gestión de roles
 * Verifica que el controlador, modelo y vistas funcionen correctamente
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
    table { border-collapse: collapse; width: 100%; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
</style>";

echo "<h1>🧪 Test Completo del Sistema de Gestión de Roles</h1>";

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

// 2. Verificar tabla role_permissions
echo "<h2>2. Verificación de Tabla role_permissions</h2>";
try {
    $stmt = $dbConn->query("SHOW TABLES LIKE 'role_permissions'");
    if ($stmt->rowCount() > 0) {
        echo "<div class='success'>✅ Tabla role_permissions existe</div>";
        
        // Verificar estructura
        $stmt = $dbConn->query("DESCRIBE role_permissions");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<table>";
        echo "<thead><tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Por Defecto</th></tr></thead>";
        echo "<tbody>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . $column['Field'] . "</td>";
            echo "<td>" . $column['Type'] . "</td>";
            echo "<td>" . $column['Null'] . "</td>";
            echo "<td>" . $column['Key'] . "</td>";
            echo "<td>" . $column['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='error'>❌ Tabla role_permissions NO existe</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Error verificando tabla: " . $e->getMessage() . "</div>";
}

// 3. Verificar datos en role_permissions
echo "<h2>3. Verificación de Datos en role_permissions</h2>";
try {
    $stmt = $dbConn->query("SELECT * FROM role_permissions ORDER BY role_type");
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($permissions)) {
        echo "<div class='success'>✅ Se encontraron " . count($permissions) . " roles con permisos</div>";
        echo "<table>";
        echo "<thead><tr><th>Rol</th><th>Crear</th><th>Leer</th><th>Actualizar</th><th>Eliminar</th></tr></thead>";
        echo "<tbody>";
        foreach ($permissions as $perm) {
            echo "<tr>";
            echo "<td><strong>" . $perm['role_type'] . "</strong></td>";
            echo "<td>" . ($perm['can_create'] ? '✓' : '✗') . "</td>";
            echo "<td>" . ($perm['can_read'] ? '✓' : '✗') . "</td>";
            echo "<td>" . ($perm['can_update'] ? '✓' : '✗') . "</td>";
            echo "<td>" . ($perm['can_delete'] ? '✓' : '✗') . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='warning'>⚠️ No hay datos en role_permissions</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Error consultando permisos: " . $e->getMessage() . "</div>";
}

// 4. Test del RootModel
echo "<h2>4. Test del RootModel</h2>";
try {
    require_once '../app/models/rootModel.php';
    $rootModel = new RootModel();
    echo "<div class='success'>✅ RootModel cargado correctamente</div>";
    
    // Test getAllRoleTypes
    $roles = $rootModel->getAllRoleTypes();
    echo "<div class='info'>📋 Roles disponibles: " . implode(', ', $roles) . "</div>";
    
    // Test getPermissionsByRole
    $testRole = 'student';
    $permissions = $rootModel->getPermissionsByRole($testRole);
    if ($permissions) {
        echo "<div class='success'>✅ Permisos obtenidos para rol '$testRole'</div>";
        echo "<div class='info'>Permisos: Crear=" . ($permissions['can_create'] ? 'Sí' : 'No') . 
             ", Leer=" . ($permissions['can_read'] ? 'Sí' : 'No') . 
             ", Actualizar=" . ($permissions['can_update'] ? 'Sí' : 'No') . 
             ", Eliminar=" . ($permissions['can_delete'] ? 'Sí' : 'No') . "</div>";
    } else {
        echo "<div class='warning'>⚠️ No se pudieron obtener permisos para rol '$testRole'</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error en RootModel: " . $e->getMessage() . "</div>";
}

// 5. Test del RoleController
echo "<h2>5. Test del RoleController</h2>";
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

// 6. Test de actualización de permisos
echo "<h2>6. Test de Actualización de Permisos</h2>";
try {
    $testRole = 'professor';
    $testData = [
        'can_create' => 1,
        'can_read' => 1,
        'can_update' => 1,
        'can_delete' => 0
    ];
    
    $result = $rootModel->updatePermissions($testRole, $testData);
    if ($result) {
        echo "<div class='success'>✅ Permisos actualizados correctamente para rol '$testRole'</div>";
        
        // Verificar que se actualizaron
        $updatedPermissions = $rootModel->getPermissionsByRole($testRole);
        echo "<div class='info'>Permisos actualizados: Crear=" . ($updatedPermissions['can_create'] ? 'Sí' : 'No') . 
             ", Leer=" . ($updatedPermissions['can_read'] ? 'Sí' : 'No') . 
             ", Actualizar=" . ($updatedPermissions['can_update'] ? 'Sí' : 'No') . 
             ", Eliminar=" . ($updatedPermissions['can_delete'] ? 'Sí' : 'No') . "</div>";
    } else {
        echo "<div class='error'>❌ Error al actualizar permisos</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error en test de actualización: " . $e->getMessage() . "</div>";
}

// 7. Verificar vistas
echo "<h2>7. Verificación de Vistas</h2>";
$views = [
    '../app/views/role/editRole.php' => 'Vista de edición de roles',
    '../app/views/role/index.php' => 'Vista de índice de roles'
];

foreach ($views as $viewPath => $description) {
    if (file_exists($viewPath)) {
        echo "<div class='success'>✅ $description existe</div>";
    } else {
        echo "<div class='error'>❌ $description NO existe</div>";
    }
}

echo "<h2>🎉 Resumen del Test</h2>";
echo "<div class='info'>El sistema de gestión de roles ha sido verificado completamente.</div>";
echo "<div class='info'>Si todos los tests pasaron, el sistema está listo para usar.</div>";
echo "<div class='info'>Para usar el sistema, accede a: /?controller=role&action=index</div>";
?> 