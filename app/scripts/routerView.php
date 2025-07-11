<?php
if (!defined('ROOT')) {
    // Desde app/scripts/routerView.php necesitamos subir 3 niveles para llegar al directorio raíz
    // __DIR__ = F:\xampp\htdocs\byfrost\app\scripts
    // dirname(__DIR__) = F:\xampp\htdocs\byfrost\app
    // dirname(dirname(__DIR__)) = F:\xampp\htdocs\byfrost
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SecurityMiddleware.php';

$view = htmlspecialchars($_GET['view'] ?? '');
$action = htmlspecialchars($_GET['action'] ?? '');

// Debug router inicio
error_log('DEBUG ROUTER: view=' . $view . ', action=' . $action);
echo '<!-- DEBUG ROUTER: view=' . htmlspecialchars($view) . ', action=' . htmlspecialchars($action) . ' -->';

// Validar y sanitizar parámetros
$validation = SecurityMiddleware::validateGetParams($_GET);
if (!$validation) {
    http_response_code(400);
    die('Parámetros inválidos');
}

// Validar la vista
$viewValidation = SecurityMiddleware::validatePath($view);
if (!$viewValidation['valid']) {
    http_response_code(403);
    die('Vista no válida: ' . $viewValidation['error']);
}

$view = $viewValidation['sanitized'];

// Validar la acción
if (!empty($action)) {
    $actionValidation = SecurityMiddleware::validatePath($action);
    if (!$actionValidation['valid']) {
        http_response_code(403);
        die('Acción no válida: ' . $actionValidation['error']);
    }
    $action = $actionValidation['sanitized'];
}

// Debug: mostrar la ruta que se está construyendo
// echo "<!-- Debug: ROOT = " . ROOT . " -->";
// echo "<!-- Debug: view = " . htmlspecialchars($view) . " -->";
// echo "<!-- Debug: action = " . htmlspecialchars($action) . " -->";

// Seguridad extendida
if (
    empty($view) ||
    preg_match('/\.\.|\.env|config|\.htaccess/i', $view)
) {
    http_response_code(403);
    echo "<h2>Error 403</h2><p>Acceso denegado.</p>";
    echo "<p>Vista solicitada: <code>" . htmlspecialchars($view) . "</code></p>";
    exit;
}

// Router - Sistema unificado e inteligente
require_once ROOT . '/app/library/Router.php';

// Obtener conexión a base de datos
require_once ROOT . '/app/scripts/connection.php';
$dbConn = getConnection();

// Sistema automático de mapeo de controladores usando Router
function getControllerMapping() {
    $router = new Router($GLOBALS['dbConn']);
    return $router->generateControllerMapping();
}

// Usar Router para procesar rutas automáticamente
$router = new Router($dbConn);

// Procesar la ruta usando Router
$router->processRoute($view, $action);
