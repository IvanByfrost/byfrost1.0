<?php
/**
 * Test para verificar el sistema de asignación de roles
 */

echo "<h1>Test: Sistema de Asignación de Roles</h1>";

// Incluir las dependencias necesarias
require_once 'config.php';
require_once 'app/library/SessionManager.php';
require_once 'app/scripts/connection.php';

// Obtener conexión a la base de datos
$dbConn = getConnection();

// Crear instancia del SessionManager
$sessionManager = new SessionManager();

echo "<h2>1. Verificar estado de la sesión:</h2>";

if (!$sessionManager->isLoggedIn()) {
    echo "<div class='alert alert-warning'>⚠️ No estás logueado</div>";
    echo "<p>Ve a <a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a> primero</p>";
} else {
    $user = $sessionManager->getCurrentUser();
    echo "<div class='alert alert-success'>✅ Logueado como: " . $user['email'] . " (Rol: " . $user['role'] . ")</div>";
    
    if (!$sessionManager->hasRole('root')) {
        echo "<div class='alert alert-danger'>❌ Tu rol no tiene permisos para asignar roles</div>";
        echo "<p>Necesitas rol de <strong>root</strong> para acceder a esta funcionalidad</p>";
    } else {
        echo "<div class='alert alert-success'>✅ Tienes permisos para asignar roles</div>";
    }
}

echo "<h2>2. Verificar archivos del sistema:</h2>";
echo "<ul>";
echo "<li><strong>UserController.php:</strong> " . (file_exists('app/controllers/UserController.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "<li><strong>assignRole.php:</strong> " . (file_exists('app/views/user/assignRole.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "<li><strong>assignRole.js:</strong> " . (file_exists('app/resources/js/assignRole.js') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "<li><strong>UserModel.php:</strong> " . (file_exists('app/models/UserModel.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "</ul>";

echo "<h2>3. Verificar métodos en UserModel:</h2>";
if (file_exists('app/models/UserModel.php')) {
    $content = file_get_contents('app/models/UserModel.php');
    echo "<ul>";
    echo "<li><strong>searchUsersByDocument():</strong> " . (strpos($content, 'searchUsersByDocument') !== false ? '✅ Existe' : '❌ No existe') . "</li>";
    echo "<li><strong>getUsersWithoutRole():</strong> " . (strpos($content, 'getUsersWithoutRole') !== false ? '✅ Existe' : '❌ No existe') . "</li>";
    echo "<li><strong>getUsersByRole():</strong> " . (strpos($content, 'getUsersByRole') !== false ? '✅ Existe' : '❌ No existe') . "</li>";
    echo "<li><strong>assignRole():</strong> " . (strpos($content, 'assignRole') !== false ? '✅ Existe' : '❌ No existe') . "</li>";
    echo "</ul>";
}

echo "<h2>4. Verificar métodos en UserController:</h2>";
if (file_exists('app/controllers/UserController.php')) {
    $content = file_get_contents('app/controllers/UserController.php');
    echo "<ul>";
    echo "<li><strong>assignRole():</strong> " . (strpos($content, 'assignRole') !== false ? '✅ Existe' : '❌ No existe') . "</li>";
    echo "<li><strong>processAssignRole():</strong> " . (strpos($content, 'processAssignRole') !== false ? '✅ Existe' : '❌ No existe') . "</li>";
    echo "<li><strong>getUsersWithoutRole():</strong> " . (strpos($content, 'getUsersWithoutRole') !== false ? '✅ Existe' : '❌ No existe') . "</li>";
    echo "</ul>";
}

echo "<h2>5. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=consultUser' target='_blank'>Consultar Usuarios</a></li>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "</ul>";

echo "<h2>6. Crear usuarios de prueba:</h2>";
echo "<ul>";
echo "<li><a href='create-test-director.php' target='_blank'>Crear Director</a></li>";
echo "<li><a href='create-test-coordinator.php' target='_blank'>Crear Coordinador</a></li>";
echo "<li><a href='create-test-treasurer.php' target='_blank'>Crear Tesorero</a></li>";
echo "</ul>";

echo "<h2>7. Flujo de trabajo:</h2>";
echo "<ol>";
echo "<li>Ve al <a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li>Haz clic en 'Asignar rol' en el sidebar</li>";
echo "<li>Busca un usuario por documento</li>";
echo "<li>Asigna el rol correspondiente</li>";
echo "<li>Verifica que el usuario aparezca en la lista de coordinadores/directores</li>";
echo "<li>Ahora puedes crear escuelas y asignar estos usuarios</li>";
echo "</ol>";

echo "<h2>8. Roles disponibles:</h2>";
echo "<ul>";
echo "<li><strong>student:</strong> Estudiante</li>";
echo "<li><strong>parent:</strong> Padre/Acudiente</li>";
echo "<li><strong>professor:</strong> Profesor</li>";
echo "<li><strong>coordinator:</strong> Coordinador</li>";
echo "<li><strong>director:</strong> Director/Rector</li>";
echo "<li><strong>treasurer:</strong> Tesorero</li>";
echo "<li><strong>root:</strong> Administrador</li>";
echo "</ul>";

echo "<h2>9. Verificar base de datos:</h2>";

try {
    // Verificar usuarios sin rol
    $stmt = $dbConn->prepare("
        SELECT COUNT(*) as count 
        FROM users u 
        LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1 
        WHERE ur.user_id IS NULL AND u.is_active = 1
    ");
    $stmt->execute();
    $usersWithoutRole = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Verificar usuarios por rol
    $stmt = $dbConn->prepare("
        SELECT ur.role_type, COUNT(*) as count 
        FROM user_roles ur 
        WHERE ur.is_active = 1 
        GROUP BY ur.role_type
    ");
    $stmt->execute();
    $usersByRole = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<ul>";
    echo "<li><strong>Usuarios sin rol:</strong> " . $usersWithoutRole . "</li>";
    echo "<li><strong>Usuarios por rol:</strong></li>";
    echo "<ul>";
    foreach ($usersByRole as $role) {
        echo "<li>" . ucfirst($role['role_type']) . ": " . $role['count'] . "</li>";
    }
    echo "</ul>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error al consultar base de datos: " . $e->getMessage() . "</div>";
}

echo "<h2>10. Problemas comunes:</h2>";
echo "<ul>";
echo "<li><strong>No tienes permisos:</strong> Necesitas rol de root</li>";
echo "<li><strong>Usuario no encontrado:</strong> Verifica el tipo y número de documento</li>";
echo "<li><strong>Error al asignar rol:</strong> Verifica que el usuario exista</li>";
echo "<li><strong>JavaScript no funciona:</strong> Verifica la consola del navegador</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Verificando sistema de asignación de roles...</p>";
?> 