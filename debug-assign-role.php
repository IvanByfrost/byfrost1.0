<?php
/**
 * Script de diagnóstico para asignación de roles
 */

echo "<h1>Diagnóstico: Asignación de Roles</h1>";

// Incluir las dependencias necesarias
require_once 'config.php';
require_once 'app/library/SessionManager.php';
require_once 'app/scripts/connection.php';

// Obtener conexión a la base de datos
$dbConn = getConnection();

// Crear instancia del SessionManager
$sessionManager = new SessionManager();

echo "<h2>1. Estado de la sesión:</h2>";
if (!$sessionManager->isLoggedIn()) {
    echo "<div class='alert alert-danger'>❌ No estás logueado</div>";
    exit;
} else {
    $user = $sessionManager->getCurrentUser();
    echo "<div class='alert alert-success'>✅ Logueado como: " . $user['email'] . " (Rol: " . $user['role'] . ")</div>";
    
    if (!$sessionManager->hasRole('root')) {
        echo "<div class='alert alert-danger'>❌ Tu rol no tiene permisos (necesitas 'root')</div>";
        exit;
    }
}

echo "<h2>2. Verificar archivos:</h2>";
$files = [
    'app/controllers/UserController.php',
    'app/views/user/assignRole.php',
    'app/resources/js/assignRole.js',
    'app/models/UserModel.php'
];

foreach ($files as $file) {
    $exists = file_exists($file);
    $size = $exists ? filesize($file) : 0;
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$file</strong> " . ($exists ? "($size bytes)" : "NO EXISTE") . "</div>";
}

echo "<h2>3. Probar UserController:</h2>";
try {
    require_once 'app/controllers/UserController.php';
    $userController = new UserController($dbConn);
    echo "<div class='alert alert-success'>✅ UserController cargado correctamente</div>";
    
    // Verificar métodos
    $methods = get_class_methods($userController);
    echo "<div>Métodos disponibles: " . implode(', ', $methods) . "</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error al cargar UserController: " . $e->getMessage() . "</div>";
}

echo "<h2>4. Probar UserModel:</h2>";
try {
    require_once 'app/models/UserModel.php';
    $userModel = new UserModel();
    echo "<div class='alert alert-success'>✅ UserModel cargado correctamente</div>";
    
    // Probar búsqueda por documento
    $users = $userModel->searchUsersByDocument('CC', '1031180139');
    echo "<div>Usuarios encontrados con CC 1031180139: " . count($users) . "</div>";
    
    if (!empty($users)) {
        echo "<div class='alert alert-info'>📋 Usuario encontrado:</div>";
        echo "<pre>" . print_r($users[0], true) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error en UserModel: " . $e->getMessage() . "</div>";
}

echo "<h2>5. Probar router:</h2>";
try {
    // Simular la petición del router
    $_GET['view'] = 'user';
    $_GET['action'] = 'assignRole';
    $_GET['credential_type'] = 'CC';
    $_GET['credential_number'] = '1031180139';
    
    echo "<div>Parámetros GET: " . print_r($_GET, true) . "</div>";
    
    // Incluir el router
    require_once 'app/scripts/routerView.php';
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error en router: " . $e->getMessage() . "</div>";
}

echo "<h2>6. Verificar base de datos:</h2>";
try {
    // Verificar si existe el usuario
    $stmt = $dbConn->prepare("SELECT * FROM users WHERE credential_type = ? AND credential_number = ?");
    $stmt->execute(['CC', '1031180139']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<div class='alert alert-success'>✅ Usuario encontrado en BD:</div>";
        echo "<pre>" . print_r($user, true) . "</pre>";
        
        // Verificar roles
        $stmt = $dbConn->prepare("SELECT * FROM user_roles WHERE user_id = ?");
        $stmt->execute([$user['user_id']]);
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div>Roles del usuario:</div>";
        echo "<pre>" . print_r($roles, true) . "</pre>";
    } else {
        echo "<div class='alert alert-warning'>⚠️ Usuario no encontrado en BD</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error en BD: " . $e->getMessage() . "</div>";
}

echo "<h2>7. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles (sin parámetros)</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139' target='_blank'>Asignar Roles (con parámetros)</a></li>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "</ul>";

echo "<h2>8. Crear usuario de prueba:</h2>";
echo "<p>Si el usuario no existe, puedes crearlo:</p>";
echo "<ul>";
echo "<li><a href='create-test-director.php' target='_blank'>Crear Director</a></li>";
echo "<li><a href='create-test-coordinator.php' target='_blank'>Crear Coordinador</a></li>";
echo "<li><a href='create-test-treasurer.php' target='_blank'>Crear Tesorero</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Diagnóstico completado</p>";
?> 