<?php
// Configurar error reporting para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir ROOT si no está definido
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

// Log de inicio
error_log("schoolProcess.php iniciado");

try {
    // Incluir archivos necesarios
    require_once ROOT . '/app/scripts/connection.php';
    require_once ROOT . '/app/controllers/SchoolController.php';
    
    // Configurar headers para JSON
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    
    // Manejar preflight OPTIONS
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
    
    // Log de método y datos recibidos
    error_log("Método: " . $_SERVER['REQUEST_METHOD']);
    error_log("POST data: " . print_r($_POST, true));
    
    $dbConn = getConnection();
    $controller = new SchoolController($dbConn);

    // Manejar peticiones POST para crear escuela
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset(_POST['school_name']) && htmlspecialchars(_POST['school_name'])) {
        error_log("Procesando creación de escuela");
        $controller->createSchool(); 
        return;
    }

    // Manejar peticiones POST para consultar escuela
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset(_POST['nit']) && htmlspecialchars(_POST['nit']) || isset(_POST['school_name']) && htmlspecialchars(_POST['school_name']) || isset(_POST['codigoDANE']) && htmlspecialchars(_POST['codigoDANE']) || isset(_POST['search']) && htmlspecialchars(_POST['search']))) {
        error_log("Procesando consulta de escuela");
        $controller->consultSchool(); 
        return;
    }

    // Si no es una petición válida
    error_log("Petición no válida");
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido o datos incompletos',
        'method' => $_SERVER['REQUEST_METHOD'],
        'post_data' => $_POST
    ]);

} catch (Exception $e) {
    error_log("Error en schoolProcess.php: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    echo json_encode([
        'status' => 'error',
        'msg' => 'Error interno del servidor: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>