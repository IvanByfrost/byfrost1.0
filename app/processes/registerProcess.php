<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/controllers/RegisterController.php';

// Debug: mostrar datos recibidos
error_log("DEBUG registerProcess - POST data: " . print_r($_POST, true));
error_log("DEBUG registerProcess - REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);

// Asegurar que no se envíen headers antes de la respuesta JSON
if (headers_sent($file, $line)) {
    error_log("DEBUG registerProcess - Headers ya enviados en $file:$line");
}

$dbConn = getConnection();

$controller = new RegisterController($dbConn);

// Verificar método y subject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['subject']) && $_POST['subject'] === 'register') {
        error_log("DEBUG registerProcess - Subject válido, llamando registerUser()");
        
        // Capturar cualquier salida antes de la respuesta JSON
        ob_start();
        
        try {
            $controller->registerUser();
            $output = ob_get_clean();
            
            error_log("DEBUG registerProcess - Salida del controlador: " . $output);
            
            // Verificar si la salida es JSON válido
            $decoded = json_decode($output, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("DEBUG registerProcess - Error en JSON: " . json_last_error_msg());
                error_log("DEBUG registerProcess - Salida cruda: " . $output);
                
                // Si no es JSON válido, devolver error
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error interno del servidor: respuesta inválida',
                    'debug' => [
                        'raw_output' => $output,
                        'json_error' => json_last_error_msg()
                    ]
                ]);
            } else {
                // La salida es JSON válido, enviarla tal como está
                echo $output;
            }
            
        } catch (Exception $e) {
            ob_end_clean(); // Limpiar cualquier salida
            error_log("DEBUG registerProcess - Excepción capturada: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'msg' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
        
    } else {
        error_log("DEBUG registerProcess - Subject inválido o faltante. Subject recibido: " . ($_POST['subject'] ?? 'NO DEFINIDO'));
        echo json_encode([
            'status' => 'error',
            'msg' => 'Subject inválido o faltante. Se requiere subject=register',
            'debug' => [
                'received_subject' => $_POST['subject'] ?? 'NO DEFINIDO',
                'expected_subject' => 'register',
                'post_data' => $_POST
            ]
        ]);
    }
} else {
    error_log("DEBUG registerProcess - Método no permitido: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido. Se requiere POST',
        'debug' => [
            'method' => $_SERVER['REQUEST_METHOD'],
            'expected_method' => 'POST'
        ]
    ]);
}