<?php
// Test para verificar el flujo de consulta de escuelas
require_once 'config.php';
require_once 'app/library/SessionManager.php';
require_once 'app/controllers/SchoolController.php';

// Simular una sesión de director
session_start();
$_SESSION['ByFrost_user_id'] = 1;
$_SESSION['ByFrost_role'] = 'director';
$_SESSION['ByFrost_is_logged_in'] = true;

try {
    // Crear instancia del controlador
    $schoolController = new SchoolController($dbConn);
    
    // Simular una petición GET para consultar escuelas
    $_GET['view'] = 'school';
    $_GET['action'] = 'consultSchool';
    
    echo "<h2>Test de Consulta de Escuelas</h2>";
    echo "<p>Verificando flujo de consulta...</p>";
    
    // Llamar al método consultSchool
    $schoolController->consultSchool();
    
    echo "<p>✅ Test completado exitosamente</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error en el test: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace: " . $e->getTraceAsString() . "</p>";
}
?> 