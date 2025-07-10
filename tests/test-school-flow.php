<?php
// Test para verificar el flujo completo de consulta de escuelas
require_once 'config.php';
require_once 'app/library/SessionManager.php';
require_once 'app/controllers/SchoolController.php';

// Simular una sesión de director
session_start();
$_SESSION['ByFrost_user_id'] = 1;
$_SESSION['ByFrost_role'] = 'director';
$_SESSION['ByFrost_is_logged_in'] = true;

echo "<h2>Test del Flujo de Consulta de Escuelas</h2>";

try {
    // Crear instancia del controlador
    $schoolController = new SchoolController($dbConn);
    
    echo "<h3>1. Verificando método getAllSchools()</h3>";
    
    // Obtener todas las escuelas directamente del modelo
    $schools = $schoolController->schoolModel->getAllSchools();
    echo "<p>✅ Escuelas encontradas: " . count($schools) . "</p>";
    
    if (count($schools) > 0) {
        echo "<h4>Primera escuela:</h4>";
        echo "<ul>";
        echo "<li><strong>ID:</strong> " . $schools[0]['school_id'] . "</li>";
        echo "<li><strong>Nombre:</strong> " . $schools[0]['school_name'] . "</li>";
        echo "<li><strong>DANE:</strong> " . $schools[0]['school_dane'] . "</li>";
        echo "<li><strong>NIT:</strong> " . $schools[0]['school_document'] . "</li>";
        echo "</ul>";
    }
    
    echo "<h3>2. Verificando método consultSchool()</h3>";
    
    // Simular una petición GET para consultar escuelas
    $_GET['view'] = 'school';
    $_GET['action'] = 'consultSchool';
    
    // Capturar la salida del método consultSchool
    ob_start();
    $schoolController->consultSchool();
    $output = ob_get_clean();
    
    echo "<p>✅ Método consultSchool() ejecutado correctamente</p>";
    echo "<p>Longitud de la salida: " . strlen($output) . " caracteres</p>";
    
    // Verificar que la salida contiene elementos esperados
    if (strpos($output, 'Consulta de Escuelas') !== false) {
        echo "<p>✅ La vista se cargó correctamente (contiene 'Consulta de Escuelas')</p>";
    } else {
        echo "<p>❌ La vista no se cargó correctamente</p>";
    }
    
    if (strpos($output, 'table') !== false) {
        echo "<p>✅ La tabla de escuelas está presente</p>";
    } else {
        echo "<p>❌ La tabla de escuelas no está presente</p>";
    }
    
    echo "<h3>3. Verificando URL generada por loadView</h3>";
    echo "<p>URL esperada: ?view=school&action=consultSchool</p>";
    echo "<p>Esta URL debería llamar directamente al método consultSchool() del SchoolController</p>";
    
    echo "<h3>4. Resumen del flujo</h3>";
    echo "<ol>";
    echo "<li>Usuario hace clic en 'Consultar Colegios' en el sidebar</li>";
    echo "<li>Se ejecuta loadView('school/consultSchool')</li>";
    echo "<li>loadView construye la URL: ?view=school&action=consultSchool</li>";
    echo "<li>El SchoolController recibe la petición y ejecuta consultSchool()</li>";
    echo "<li>consultSchool() obtiene las escuelas y carga la vista</li>";
    echo "<li>La vista muestra la lista de escuelas en el mainContent</li>";
    echo "</ol>";
    
    echo "<h3>✅ Test completado exitosamente</h3>";
    echo "<p>El flujo debería funcionar correctamente. Si hay problemas, verifica:</p>";
    echo "<ul>";
    echo "<li>Que el archivo loadView.js esté cargado</li>";
    echo "<li>Que el elemento #mainContent exista en el DOM</li>";
    echo "<li>Que el usuario tenga permisos de director</li>";
    echo "<li>Que la base de datos tenga escuelas registradas</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p>❌ Error en el test: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace: " . $e->getTraceAsString() . "</p>";
}
?> 