<?php
// Test para diagnosticar problemas de sesión y permisos
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Diagnóstico - Sesión y Permisos</h2>";

// 1. Verificar SessionManager
echo "<h3>1. Verificación de SessionManager</h3>";
$sessionManagerFile = 'app/library/SessionManager.php';
if (file_exists($sessionManagerFile)) {
    echo "✓ SessionManager.php existe<br>";
    
    $content = file_get_contents($sessionManagerFile);
    
    // Verificar métodos importantes
    $methods = ['isLoggedIn', 'getUserRole', 'hasRole', 'getCurrentUser'];
    foreach ($methods as $method) {
        if (strpos($content, "public function $method") !== false) {
            echo "✓ Tiene método $method()<br>";
        } else {
            echo "✗ NO tiene método $method()<br>";
        }
    }
    
} else {
    echo "✗ SessionManager.php NO existe<br>";
}

// 2. Verificar UserController
echo "<h3>2. Verificación de UserController</h3>";
$controllerFile = 'app/controllers/userController.php';
if (file_exists($controllerFile)) {
    echo "✓ userController.php existe<br>";
    
    $content = file_get_contents($controllerFile);
    
    // Verificar método protectRoot
    if (strpos($content, 'private function protectRoot()') !== false) {
        echo "✓ Tiene método protectRoot()<br>";
    } else {
        echo "✗ NO tiene método protectRoot()<br>";
    }
    
    // Verificar verificación de rol root
    if (strpos($content, "hasRole('root')") !== false) {
        echo "✓ Verifica rol 'root'<br>";
    } else {
        echo "✗ NO verifica rol 'root'<br>";
    }
    
} else {
    echo "✗ userController.php NO existe<br>";
}

// 3. Simular sesión activa
echo "<h3>3. Simulación de Sesión Activa</h3>";

// Incluir archivos necesarios
require_once 'config.php';
require_once 'app/library/SessionManager.php';

// Simular datos de sesión
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_email'] = 'admin@test.com';
$_SESSION['user_role'] = 'root'; // Cambiar esto según el rol real del usuario
$_SESSION['user_name'] = 'Admin';
$_SESSION['user_lastname'] = 'Test';
$_SESSION['logged_in'] = true;
$_SESSION['login_time'] = time();

echo "Datos de sesión simulados:<br>";
foreach ($_SESSION as $key => $value) {
    echo "- $key: $value<br>";
}

// 4. Probar SessionManager
echo "<h3>4. Prueba de SessionManager</h3>";

try {
    $sessionManager = new SessionManager();
    
    echo "SessionManager instanciado correctamente<br>";
    
    if ($sessionManager->isLoggedIn()) {
        echo "✓ Usuario está logueado<br>";
    } else {
        echo "✗ Usuario NO está logueado<br>";
    }
    
    $userRole = $sessionManager->getUserRole();
    echo "Rol del usuario: " . ($userRole ?: 'sin rol') . "<br>";
    
    if ($sessionManager->hasRole('root')) {
        echo "✓ Usuario tiene rol 'root'<br>";
    } else {
        echo "✗ Usuario NO tiene rol 'root'<br>";
    }
    
    $currentUser = $sessionManager->getCurrentUser();
    echo "Datos del usuario actual:<br>";
    foreach ($currentUser as $key => $value) {
        echo "- $key: $value<br>";
    }
    
} catch (Exception $e) {
    echo "✗ Error al probar SessionManager: " . $e->getMessage() . "<br>";
}

// 5. Probar UserController
echo "<h3>5. Prueba de UserController</h3>";

try {
    require_once 'app/controllers/userController.php';
    require_once 'app/scripts/connection.php';
    
    $dbConn = getConnection();
    $controller = new UserController($dbConn);
    
    echo "UserController instanciado correctamente<br>";
    
    // Verificar si el método protectRoot es accesible
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('protectRoot');
    $method->setAccessible(true);
    
    echo "Método protectRoot es accesible<br>";
    
    // Intentar ejecutar protectRoot
    try {
        $method->invoke($controller);
        echo "✓ protectRoot ejecutado sin errores<br>";
    } catch (Exception $e) {
        echo "✗ Error en protectRoot: " . $e->getMessage() . "<br>";
    }
    
} catch (Exception $e) {
    echo "✗ Error al probar UserController: " . $e->getMessage() . "<br>";
}

// 6. Verificar roles disponibles
echo "<h3>6. Roles Disponibles</h3>";
echo "Roles que deberían tener acceso a asignación de roles:<br>";
echo "- root (administrador)<br>";
echo "- director (director/rector)<br>";
echo "- coordinator (coordinador)<br>";

echo "<h3>7. Solución Propuesta</h3>";
echo "Si el usuario no tiene rol 'root', puedes:<br>";
echo "1. Cambiar el rol del usuario a 'root' en la base de datos<br>";
echo "2. Modificar protectRoot() para permitir otros roles<br>";
echo "3. Verificar que la sesión esté correctamente configurada<br>";

echo "<h3>8. Debug de Sesión</h3>";
echo "Para debuggear la sesión en tiempo real:<br>";
echo "1. Abre la consola del navegador<br>";
echo "2. Ve a la pestaña Application/Storage > Session Storage<br>";
echo "3. Verifica que los datos de sesión estén presentes<br>";
echo "4. Verifica que el rol sea 'root'<br>";

echo "<br><strong>Test completado.</strong>";
?> 