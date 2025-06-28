<?php
/**
 * Test Completo: Sistema de Asignación de Roles
 * Diagnóstico completo de todos los problemas
 */

echo "<h1>🔍 Diagnóstico Completo: Sistema AssignRole</h1>";

// Incluir dependencias
require_once '../config.php';
require_once '../app/library/SessionManager.php';
require_once '../app/scripts/connection.php';

// Obtener conexión
$dbConn = getConnection();
$sessionManager = new SessionManager();

echo "<h2>1. Estado de la Sesión</h2>";
if (!$sessionManager->isLoggedIn()) {
    echo "<div class='alert alert-danger'>❌ No estás logueado</div>";
    echo "<p><a href='http://localhost:8000/?view=index&action=login' target='_blank'>Ir al Login</a></p>";
    exit;
} else {
    $user = $sessionManager->getCurrentUser();
    echo "<div class='alert alert-success'>✅ Logueado como: " . $user['email'] . "</div>";
    
    if (!$sessionManager->hasRole('root')) {
        echo "<div class='alert alert-danger'>❌ No tienes rol root (actual: " . $user['role'] . ")</div>";
        echo "<p>Necesitas rol 'root' para acceder a AssignRole</p>";
        exit;
    } else {
        echo "<div class='alert alert-success'>✅ Tienes rol root - puedes acceder a AssignRole</div>";
    }
}

echo "<h2>2. Verificación de Archivos Críticos</h2>";
$files = [
    '../app/controllers/UserController.php' => 'Controlador principal',
    '../app/models/UserModel.php' => 'Modelo de usuarios',
    '../app/views/user/assignRole.php' => 'Vista de asignación',
    '../app/resources/js/assignRole.js' => 'JavaScript AJAX',
    '../app/controllers/MainController.php' => 'Controlador base'
];

foreach ($files as $file => $description) {
    $exists = file_exists($file);
    $size = $exists ? filesize($file) : 0;
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$file</strong> ($description): " . ($exists ? "OK ($size bytes)" : "NO EXISTE") . "</div>";
}

echo "<h2>3. Verificación de Base de Datos</h2>";
try {
    // Verificar tabla users
    $stmt = $dbConn->query("SELECT COUNT(*) FROM users WHERE is_active = 1");
    $userCount = $stmt->fetchColumn();
    echo "<div>✅ Tabla users: $userCount usuarios activos</div>";
    
    // Verificar tabla user_roles
    $stmt = $dbConn->query("SELECT COUNT(*) FROM user_roles WHERE is_active = 1");
    $roleCount = $stmt->fetchColumn();
    echo "<div>✅ Tabla user_roles: $roleCount roles activos</div>";
    
    // Verificar usuarios sin rol
    $stmt = $dbConn->query("
        SELECT COUNT(*) FROM users u 
        LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1 
        WHERE ur.user_id IS NULL AND u.is_active = 1
    ");
    $usersWithoutRole = $stmt->fetchColumn();
    echo "<div>✅ Usuarios sin rol: $usersWithoutRole</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error en BD: " . $e->getMessage() . "</div>";
}

echo "<h2>4. Test de UserController</h2>";
try {
    require_once '../app/controllers/UserController.php';
    $userController = new UserController($dbConn);
    echo "<div class='alert alert-success'>✅ UserController cargado correctamente</div>";
    
    // Verificar métodos
    $methods = get_class_methods($userController);
    $requiredMethods = ['assignRole', 'processAssignRole', 'getUsersWithoutRole'];
    
    foreach ($requiredMethods as $method) {
        if (in_array($method, $methods)) {
            echo "<div>✅ Método $method existe</div>";
        } else {
            echo "<div class='alert alert-danger'>❌ Método $method NO existe</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error en UserController: " . $e->getMessage() . "</div>";
}

echo "<h2>5. Test de UserModel</h2>";
try {
    require_once '../app/models/UserModel.php';
    $userModel = new UserModel($dbConn);
    echo "<div class='alert alert-success'>✅ UserModel cargado correctamente</div>";
    
    // Test de búsqueda de usuarios
    $users = $userModel->searchUsersByDocument('CC', '1031180139');
    echo "<div>✅ Búsqueda de usuarios: " . count($users) . " encontrados</div>";
    
    // Test de usuarios sin rol
    $usersWithoutRole = $userModel->getUsersWithoutRole();
    echo "<div>✅ Usuarios sin rol: " . count($usersWithoutRole) . " encontrados</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error en UserModel: " . $e->getMessage() . "</div>";
}

echo "<h2>6. Test de URLs</h2>";
echo "<div>URLs para probar:</div>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>AssignRole (sin parámetros)</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139' target='_blank'>AssignRole (con parámetros)</a></li>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "</ul>";

echo "<h2>7. Problemas Identificados</h2>";
echo "<div class='alert alert-warning'>⚠️ Posibles problemas:</div>";
echo "<ul>";
echo "<li><strong>SessionManager:</strong> Verificar que esté inicializado en MainController</li>";
echo "<li><strong>AJAX:</strong> Verificar que las rutas AJAX funcionen correctamente</li>";
echo "<li><strong>Permisos:</strong> Verificar que el rol 'root' esté correctamente configurado</li>";
echo "<li><strong>Base de datos:</strong> Verificar que las tablas tengan datos de prueba</li>";
echo "</ul>";

echo "<h2>8. Soluciones Recomendadas</h2>";
echo "<ol>";
echo "<li><strong>Crear usuario de prueba:</strong> Si no hay usuarios, crear uno para probar</li>";
echo "<li><strong>Verificar SessionManager:</strong> Asegurar que esté en MainController</li>";
echo "<li><strong>Testear AJAX:</strong> Verificar que las peticiones AJAX lleguen al servidor</li>";
echo "<li><strong>Debug JavaScript:</strong> Revisar la consola del navegador para errores JS</li>";
echo "</ol>";

echo "<h2>9. Acciones Inmediatas</h2>";
echo "<div class='d-grid gap-2'>";
echo "<a href='create-test-user.php' class='btn btn-primary'>Crear Usuario de Prueba</a>";
echo "<a href='test-ajax-debug.php' class='btn btn-secondary'>Test AJAX</a>";
echo "<a href='test-session-status.php' class='btn btn-info'>Verificar Sesión</a>";
echo "</div>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Diagnóstico completado - Revisa los resultados arriba</p>";
?> 