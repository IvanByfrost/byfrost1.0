<?php
// Test del MainController y SessionManager
echo "<h1>Test del MainController</h1>";

// Incluir archivos necesarios
require_once 'config.php';
require_once 'app/scripts/connection.php';
require_once 'app/controllers/MainController.php';

// Crear conexión a BD
$dbConn = getConnection();

echo "<h2>1. Creando MainController</h2>";
try {
    $mainController = new MainController($dbConn);
    echo "✅ MainController creado exitosamente<br>";
    
    echo "<h2>2. Verificando SessionManager usando Reflection</h2>";
    $reflection = new ReflectionClass($mainController);
    $sessionManagerProperty = $reflection->getProperty('sessionManager');
    $sessionManagerProperty->setAccessible(true);
    $sessionManager = $sessionManagerProperty->getValue($mainController);
    
    if ($sessionManager) {
        echo "✅ SessionManager está inicializado<br>";
        
        echo "<h2>3. Probando métodos del SessionManager</h2>";
        echo "¿Está logueado? " . ($sessionManager->isLoggedIn() ? "SÍ" : "NO") . "<br>";
        
        echo "<h2>4. Probando login</h2>";
        $userData = [
            'id' => 1,
            'email' => 'test@byfrost.com',
            'role' => 'coordinator',
            'first_name' => 'Juan',
            'last_name' => 'Pérez'
        ];
        
        $loginSuccess = $sessionManager->login($userData);
        echo "Login exitoso: " . ($loginSuccess ? "SÍ" : "NO") . "<br>";
        
        if ($loginSuccess) {
            echo "Usuario logueado: " . $sessionManager->getUserFullName() . "<br>";
            echo "Rol: " . $sessionManager->getUserRole() . "<br>";
        }
        
    } else {
        echo "❌ SessionManager NO está inicializado<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
}

echo "<h2>✅ Test completado</h2>";
?> 