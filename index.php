<?php
define('ROOT', __DIR__);

// Incluye las constantes y configuraciones
require_once __DIR__ . '/config.php';

// Autocarga
spl_autoload_register(function ($class) {
    $paths = ['library', 'controllers', 'models'];
    foreach ($paths as $folder) {
        $file = ROOT . "/app/$folder/$class.php";
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Conexión a la base de datos
require_once ROOT . '/app/scripts/connection.php';
$dbConn = getConnection();

// Instancia de la clase de vistas
$view = new Views();

// Manejo manual de rutas para desarrollo local
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$path = trim($requestUri, '/');

// Debug: mostrar información de la ruta
error_log("DEBUG - Request URI: " . $requestUri);
error_log("DEBUG - Path extraído: " . $path);

// Si alguien intenta acceder directamente a archivos PHP, redirigir a 404
if (strpos($path, '.php') !== false || strpos($path, 'app/views/') === 0 || strpos($path, 'app/controllers/') === 0 || strpos($path, 'app/models/') === 0 || strpos($path, 'app/library/') === 0) {
    error_log("DEBUG - Intento de acceso directo a archivo o directorio protegido: " . $path);
    http_response_code(404);
    require_once ROOT . '/app/controllers/errorController.php';
    $error = new ErrorController($dbConn, $view);
    $error->Error('404');
    exit;
}

// Si no hay parámetro 'url' en $_GET, establecerlo manualmente
if (!isset($_GET['url']) && !empty($path)) {
    $_GET['url'] = $path;
    error_log("DEBUG - Establecido _GET['url'] = " . $path);
}

// Iniciar enrutador
$router = new Router($dbConn, $view);