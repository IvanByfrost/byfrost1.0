<?php
// Mostrar errores para debugging (en desarrollo, no en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir ROOT si no está definido
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

// Iniciar sesión para usar CSRF
session_start();

// Log de inicio de proceso
error_log("schoolProcess.php iniciado");

try {
    // Incluir archivos necesarios
    require_once ROOT . '/app/scripts/connection.php';
    require_once ROOT . '/app/controllers/SchoolController.php';

    // Configurar cabeceras CORS y JSON
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

    // Manejar preflight OPTIONS
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    // Validar token CSRF
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? null)) {
        error_log("CSRF token inválido o ausente");
        echo json_encode([
            'status' => 'error',
            'msg' => 'Token CSRF inválido.'
        ]);
        exit;
    }

    // Log de método y datos recibidos
    error_log("Método: " . $_SERVER['REQUEST_METHOD']);
    error_log("POST data: " . print_r($_POST, true));

    // Conexión a la base de datos
    $dbConn = getConnection();
    $controller = new SchoolController($dbConn);

    // Procesar petición POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Crear escuela
        if (!empty($_POST['school_name'])) {
            error_log("Procesando creación de escuela");
            $response = $controller->createSchool();
            echo json_encode($response);
            exit;
        }

        // Consultar escuela
        if (
            !empty($_POST['nit']) ||
            !empty($_POST['school_name']) ||
            !empty($_POST['codigoDANE']) ||
            !empty($_POST['search'])
        ) {
            error_log("Procesando consulta de escuela");
            $response = $controller->consultSchool();
            echo json_encode($response);
            exit;
        }
    }

    // Petición no válida
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
