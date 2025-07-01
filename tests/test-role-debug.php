<?php
// Test para debuggear el problema con RoleController
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test de Debug - RoleController</h1>";

// 1. Verificar configuración
echo "<h2>1. Verificación de Configuración</h2>";
if (defined('ROOT')) {
    echo "<div style='color: green;'>✅ ROOT está definido: " . ROOT . "</div>";
} else {
    echo "<div style='color: red;'>❌ ROOT NO está definido</div>";
    define('ROOT', __DIR__ . '/..');
}

// 2. Verificar conexión a base de datos
echo "<h2>2. Verificación de Conexión a BD</h2>";
try {
    require_once ROOT . '/app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<div style='color: green;'>✅ Conexión a BD exitosa</div>";
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</div>";
    exit;
}

// 3. Verificar tablas necesarias
echo "<h2>3. Verificación de Tablas</h2>";
$tables = ['user_roles', 'role_permissions'];
foreach ($tables as $table) {
    try {
        $stmt = $dbConn->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<div style='color: green;'>✅ Tabla $table existe</div>";
        } else {
            echo "<div style='color: red;'>❌ Tabla $table NO existe</div>";
        }
    } catch (Exception $e) {
        echo "<div style='color: red;'>❌ Error verificando tabla $table: " . $e->getMessage() . "</div>";
    }
}

// 4. Verificar datos en user_roles
echo "<h2>4. Verificación de Datos en user_roles</h2>";
try {
    $stmt = $dbConn->query("SELECT DISTINCT role_type FROM user_roles ORDER BY role_type ASC");
    $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($roles)) {
        echo "<div style='color: green;'>✅ Se encontraron roles: " . implode(', ', $roles) . "</div>";
    } else {
        echo "<div style='color: orange;'>⚠️ No hay roles en user_roles</div>";
    }
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error consultando user_roles: " . $e->getMessage() . "</div>";
}

// 5. Verificar datos en role_permissions
echo "<h2>5. Verificación de Datos en role_permissions</h2>";
try {
    $stmt = $dbConn->query("SELECT * FROM role_permissions ORDER BY role_type");
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($permissions)) {
        echo "<div style='color: green;'>✅ Se encontraron " . count($permissions) . " registros en role_permissions</div>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Rol</th><th>Crear</th><th>Leer</th><th>Actualizar</th><th>Eliminar</th></tr>";
        foreach ($permissions as $perm) {
            echo "<tr>";
            echo "<td>" . $perm['role_type'] . "</td>";
            echo "<td>" . ($perm['can_create'] ? 'Sí' : 'No') . "</td>";
            echo "<td>" . ($perm['can_read'] ? 'Sí' : 'No') . "</td>";
            echo "<td>" . ($perm['can_update'] ? 'Sí' : 'No') . "</td>";
            echo "<td>" . ($perm['can_delete'] ? 'Sí' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div style='color: orange;'>⚠️ No hay datos en role_permissions</div>";
    }
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error consultando role_permissions: " . $e->getMessage() . "</div>";
}

// 6. Test del RootModel
echo "<h2>6. Test del RootModel</h2>";
try {
    require_once ROOT . '/app/models/rootModel.php';
    $rootModel = new RootModel();
    echo "<div style='color: green;'>✅ RootModel cargado correctamente</div>";
    
    // Test getAllRoleTypes
    $roles = $rootModel->getAllRoleTypes();
    if (is_array($roles)) {
        echo "<div style='color: green;'>✅ getAllRoleTypes() funcionó: " . implode(', ', $roles) . "</div>";
    } else {
        echo "<div style='color: red;'>❌ getAllRoleTypes() falló</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en RootModel: " . $e->getMessage() . "</div>";
    echo "<div style='color: gray;'>Stack trace: " . $e->getTraceAsString() . "</div>";
}

// 7. Test del RoleController
echo "<h2>7. Test del RoleController</h2>";
try {
    require_once ROOT . '/app/controllers/roleController.php';
    $roleController = new RoleController($dbConn);
    echo "<div style='color: green;'>✅ RoleController cargado correctamente</div>";
    
    // Verificar métodos
    $methods = get_class_methods($roleController);
    $requiredMethods = ['index', 'edit', 'update'];
    
    foreach ($requiredMethods as $method) {
        if (in_array($method, $methods)) {
            echo "<div style='color: green;'>✅ Método $method existe</div>";
        } else {
            echo "<div style='color: red;'>❌ Método $method NO existe</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en RoleController: " . $e->getMessage() . "</div>";
    echo "<div style='color: gray;'>Stack trace: " . $e->getTraceAsString() . "</div>";
}

// 8. Verificar archivos de vistas
echo "<h2>8. Verificación de Archivos de Vistas</h2>";
$viewFiles = [
    ROOT . '/app/views/role/indexPartial.php' => 'Vista parcial de índice',
    ROOT . '/app/views/role/editRolePartial.php' => 'Vista parcial de edición',
    ROOT . '/app/views/role/index.php' => 'Vista completa de índice',
    ROOT . '/app/views/role/editRole.php' => 'Vista completa de edición'
];

foreach ($viewFiles as $file => $description) {
    if (file_exists($file)) {
        echo "<div style='color: green;'>✅ $description existe</div>";
    } else {
        echo "<div style='color: red;'>❌ $description NO existe: $file</div>";
    }
}

echo "<h2>🎉 Test Completado</h2>";
echo "<div style='color: blue;'>Si todos los tests pasaron, el problema podría estar en la lógica del controlador.</div>";
?> 