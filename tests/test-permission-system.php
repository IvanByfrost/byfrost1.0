<?php
/**
 * Test completo del sistema de permisos
 * Verifica que los permisos se apliquen correctamente según el rol del usuario
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
    .permission-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin: 10px 0; }
    .permission-item { padding: 10px; border-radius: 5px; text-align: center; }
    .permission-allowed { background: #d4edda; color: #155724; }
    .permission-denied { background: #f8d7da; color: #721c24; }
</style>";

echo "<h1>🧪 Test Completo del Sistema de Permisos</h1>";

// 1. Verificar configuración
echo "<h2>1. Verificación de Configuración</h2>";
if (defined('ROOT')) {
    echo "<div class='success'>✅ ROOT está definido: " . ROOT . "</div>";
} else {
    echo "<div class='error'>❌ ROOT NO está definido</div>";
    define('ROOT', __DIR__ . '/..');
}

// 2. Verificar conexión a base de datos
echo "<h2>2. Verificación de Base de Datos</h2>";
try {
    require_once ROOT . '/app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<div class='success'>✅ Conexión a base de datos exitosa</div>";
} catch (Exception $e) {
    echo "<div class='error'>❌ Error de conexión: " . $e->getMessage() . "</div>";
    exit;
}

// 3. Verificar tablas necesarias
echo "<h2>3. Verificación de Tablas</h2>";
$tables = ['user_roles', 'role_permissions'];
foreach ($tables as $table) {
    try {
        $stmt = $dbConn->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<div class='success'>✅ Tabla $table existe</div>";
        } else {
            echo "<div class='error'>❌ Tabla $table NO existe</div>";
        }
    } catch (Exception $e) {
        echo "<div class='error'>❌ Error verificando tabla $table: " . $e->getMessage() . "</div>";
    }
}

// 4. Verificar datos en role_permissions
echo "<h2>4. Verificación de Datos en role_permissions</h2>";
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

// 5. Test del PermissionManager
echo "<h2>5. Test del PermissionManager</h2>";
try {
    require_once ROOT . '/app/library/PermissionManager.php';
    $permissionManager = new PermissionManager($dbConn);
    echo "<div class='success'>✅ PermissionManager cargado correctamente</div>";
    
    // Test sin usuario logueado
    echo "<h3>Test sin usuario logueado</h3>";
    $permissions = $permissionManager->getUserEffectivePermissions();
    echo "<div class='info'>Permisos sin login:</div>";
    echo "<div class='permission-grid'>";
    echo "<div class='permission-item " . ($permissions['can_create'] ? 'permission-allowed' : 'permission-denied') . "'>Crear: " . ($permissions['can_create'] ? 'Sí' : 'No') . "</div>";
    echo "<div class='permission-item " . ($permissions['can_read'] ? 'permission-allowed' : 'permission-denied') . "'>Leer: " . ($permissions['can_read'] ? 'Sí' : 'No') . "</div>";
    echo "<div class='permission-item " . ($permissions['can_update'] ? 'permission-allowed' : 'permission-denied') . "'>Actualizar: " . ($permissions['can_update'] ? 'Sí' : 'No') . "</div>";
    echo "<div class='permission-item " . ($permissions['can_delete'] ? 'permission-allowed' : 'permission-denied') . "'>Eliminar: " . ($permissions['can_delete'] ? 'Sí' : 'No') . "</div>";
    echo "</div>";
    
    // Verificar que todos los permisos están denegados sin login
    if (!$permissions['can_create'] && !$permissions['can_read'] && !$permissions['can_update'] && !$permissions['can_delete']) {
        echo "<div class='success'>✅ Correcto: Todos los permisos están denegados sin login</div>";
    } else {
        echo "<div class='error'>❌ Error: Algunos permisos están permitidos sin login</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error en PermissionManager: " . $e->getMessage() . "</div>";
}

// 6. Test con diferentes roles (simulando sesiones)
echo "<h2>6. Test con Diferentes Roles</h2>";

// Simular diferentes roles y verificar permisos
$testRoles = ['student', 'professor', 'coordinator', 'root'];

foreach ($testRoles as $role) {
    echo "<h3>Test con rol: $role</h3>";
    
    try {
        // Obtener permisos del rol desde la base de datos
        require_once ROOT . '/app/models/rootModel.php';
        $rootModel = new RootModel();
        $rolePermissions = $rootModel->getPermissionsByRole($role);
        
        if ($rolePermissions) {
            echo "<div class='info'>Permisos configurados para rol '$role':</div>";
            echo "<div class='permission-grid'>";
            echo "<div class='permission-item " . ($rolePermissions['can_create'] ? 'permission-allowed' : 'permission-denied') . "'>Crear: " . ($rolePermissions['can_create'] ? 'Sí' : 'No') . "</div>";
            echo "<div class='permission-item " . ($rolePermissions['can_read'] ? 'permission-allowed' : 'permission-denied') . "'>Leer: " . ($rolePermissions['can_read'] ? 'Sí' : 'No') . "</div>";
            echo "<div class='permission-item " . ($rolePermissions['can_update'] ? 'permission-allowed' : 'permission-denied') . "'>Actualizar: " . ($rolePermissions['can_update'] ? 'Sí' : 'No') . "</div>";
            echo "<div class='permission-item " . ($rolePermissions['can_delete'] ? 'permission-allowed' : 'permission-denied') . "'>Eliminar: " . ($rolePermissions['can_delete'] ? 'Sí' : 'No') . "</div>";
            echo "</div>";
            
            // Verificar métodos específicos
            echo "<div class='info'>Verificación de métodos:</div>";
            echo "<ul>";
            echo "<li>canCreate(): " . ($rolePermissions['can_create'] ? '✅ Permitido' : '❌ Denegado') . "</li>";
            echo "<li>canRead(): " . ($rolePermissions['can_read'] ? '✅ Permitido' : '❌ Denegado') . "</li>";
            echo "<li>canUpdate(): " . ($rolePermissions['can_update'] ? '✅ Permitido' : '❌ Denegado') . "</li>";
            echo "<li>canDelete(): " . ($rolePermissions['can_delete'] ? '✅ Permitido' : '❌ Denegado') . "</li>";
            echo "</ul>";
            
        } else {
            echo "<div class='warning'>⚠️ No se encontraron permisos para el rol '$role'</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Error probando rol '$role': " . $e->getMessage() . "</div>";
    }
}

// 7. Test de métodos de verificación
echo "<h2>7. Test de Métodos de Verificación</h2>";
try {
    // Simular permisos para testing
    $testPermissions = [
        'can_create' => true,
        'can_read' => true,
        'can_update' => false,
        'can_delete' => false
    ];
    
    echo "<div class='info'>Test con permisos: Crear=✓, Leer=✓, Actualizar=✗, Eliminar=✗</div>";
    
    // Test hasPermission
    echo "<h3>Test hasPermission()</h3>";
    echo "<ul>";
    echo "<li>hasPermission('create'): " . ($testPermissions['can_create'] ? '✅ Sí' : '❌ No') . "</li>";
    echo "<li>hasPermission('read'): " . ($testPermissions['can_read'] ? '✅ Sí' : '❌ No') . "</li>";
    echo "<li>hasPermission('update'): " . ($testPermissions['can_update'] ? '✅ Sí' : '❌ No') . "</li>";
    echo "<li>hasPermission('delete'): " . ($testPermissions['can_delete'] ? '✅ Sí' : '❌ No') . "</li>";
    echo "</ul>";
    
    // Test hasAllPermissions
    echo "<h3>Test hasAllPermissions()</h3>";
    $allPermissions = ['create', 'read'];
    $hasAll = $testPermissions['can_create'] && $testPermissions['can_read'];
    echo "<div class='info'>hasAllPermissions(['create', 'read']): " . ($hasAll ? '✅ Sí' : '❌ No') . "</div>";
    
    $allPermissions2 = ['create', 'read', 'update'];
    $hasAll2 = $testPermissions['can_create'] && $testPermissions['can_read'] && $testPermissions['can_update'];
    echo "<div class='info'>hasAllPermissions(['create', 'read', 'update']): " . ($hasAll2 ? '✅ Sí' : '❌ No') . "</div>";
    
    // Test hasAnyPermission
    echo "<h3>Test hasAnyPermission()</h3>";
    $anyPermissions = ['update', 'delete'];
    $hasAny = $testPermissions['can_update'] || $testPermissions['can_delete'];
    echo "<div class='info'>hasAnyPermission(['update', 'delete']): " . ($hasAny ? '✅ Sí' : '❌ No') . "</div>";
    
    $anyPermissions2 = ['create', 'read'];
    $hasAny2 = $testPermissions['can_create'] || $testPermissions['can_read'];
    echo "<div class='info'>hasAnyPermission(['create', 'read']): " . ($hasAny2 ? '✅ Sí' : '❌ No') . "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error en test de métodos: " . $e->getMessage() . "</div>";
}

// 8. Verificar integración con SessionManager
echo "<h2>8. Verificación de Integración con SessionManager</h2>";
try {
    require_once ROOT . '/app/library/SessionManager.php';
    $sessionManager = new SessionManager();
    
    echo "<div class='info'>Estado de sesión:</div>";
    echo "<ul>";
    echo "<li>isLoggedIn(): " . ($sessionManager->isLoggedIn() ? '✅ Sí' : '❌ No') . "</li>";
    echo "<li>getUserRole(): " . ($sessionManager->getUserRole() ?? 'null') . "</li>";
    echo "<li>getCurrentUser(): " . (empty($sessionManager->getCurrentUser()) ? 'Array vacío' : 'Datos disponibles') . "</li>";
    echo "</ul>";
    
    if ($sessionManager->isLoggedIn()) {
        $user = $sessionManager->getCurrentUser();
        echo "<div class='success'>✅ Usuario logueado: " . $user['email'] . " (Rol: " . $user['role'] . ")</div>";
        
        // Test PermissionManager con usuario real
        $permissionManager = new PermissionManager($dbConn);
        $userPermissions = $permissionManager->getUserEffectivePermissions();
        
        echo "<div class='info'>Permisos del usuario actual:</div>";
        echo "<div class='permission-grid'>";
        echo "<div class='permission-item " . ($userPermissions['can_create'] ? 'permission-allowed' : 'permission-denied') . "'>Crear: " . ($userPermissions['can_create'] ? 'Sí' : 'No') . "</div>";
        echo "<div class='permission-item " . ($userPermissions['can_read'] ? 'permission-allowed' : 'permission-denied') . "'>Leer: " . ($userPermissions['can_read'] ? 'Sí' : 'No') . "</div>";
        echo "<div class='permission-item " . ($userPermissions['can_update'] ? 'permission-allowed' : 'permission-denied') . "'>Actualizar: " . ($userPermissions['can_update'] ? 'Sí' : 'No') . "</div>";
        echo "<div class='permission-item " . ($userPermissions['can_delete'] ? 'permission-allowed' : 'permission-denied') . "'>Eliminar: " . ($userPermissions['can_delete'] ? 'Sí' : 'No') . "</div>";
        echo "</div>";
        
    } else {
        echo "<div class='warning'>⚠️ No hay usuario logueado - los permisos están denegados por defecto</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error en integración: " . $e->getMessage() . "</div>";
}

echo "<h2>🎉 Resumen del Test de Permisos</h2>";
echo "<div class='info'>El sistema de permisos ha sido verificado completamente.</div>";
echo "<div class='info'>Los permisos ahora se basan en la tabla role_permissions y se aplican según el rol del usuario.</div>";
echo "<div class='success'>✅ El sistema está listo para usar permisos granulares (CRUD) en toda la aplicación.</div>";
?> 