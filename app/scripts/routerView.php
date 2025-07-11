<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/config.php';

$view = htmlspecialchars($_GET['view'] ?? 'index');
$action = htmlspecialchars($_GET['action'] ?? '');

// Debug router inicio
error_log('DEBUG ROUTER: view=' . $view . ', action=' . $action);

// Validación básica de seguridad (menos restrictiva)
if (empty($view) || preg_match('/\.\.|\.env|config|\.htaccess/i', $view)) {
    http_response_code(403);
    require_once ROOT . '/app/controllers/ErrorController.php';
    $error = new ErrorController($GLOBALS['dbConn']);
    $error->Error('403');
    exit;
}

// Router - Sistema unificado e inteligente
require_once ROOT . '/app/library/Router.php';

// Obtener conexión a base de datos
require_once ROOT . '/app/scripts/connection.php';
$dbConn = getConnection();

// Usar Router para procesar rutas automáticamente
$router = new Router($dbConn);

// Procesar la ruta usando Router
$router->processRoute($view, $action);
