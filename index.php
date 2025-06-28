<?php
define('ROOT', __DIR__);

// Validación de seguridad - prevenir acceso directo a archivos sensibles
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($requestUri, PHP_URL_PATH);

// BLOQUEAR ACCESO DIRECTO A ARCHIVOS Y DIRECTORIOS SENSIBLES
$blockedPatterns = [
    '/\/app\//',
    '/\/config\.php/',
    '/\.env/',
    '/\/vendor\//',
    '/\/node_modules\//',
    '/\.git/',
    '/\.htaccess/',
    '/\.htpasswd/',
    '/\.sql/',
    '/\.log/',
    '/\.bak/',
    '/\.backup/',
    '/\.tmp/',
    '/\.temp/',
    '/\.php$/',  // Bloquear acceso directo a cualquier archivo PHP
    '/\/views\//',  // Bloquear acceso directo a directorio views
    '/\/controllers\//',  // Bloquear acceso directo a directorio controllers
    '/\/models\//',  // Bloquear acceso directo a directorio models
    '/\/library\//',  // Bloquear acceso directo a directorio library
    '/\/scripts\//',  // Bloquear acceso directo a directorio scripts
    '/\/resources\//'  // Bloquear acceso directo a directorio resources
];

// Verificar si la URL contiene patrones bloqueados
foreach ($blockedPatterns as $pattern) {
    if (preg_match($pattern, $path)) {
        http_response_code(403);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html>
<html>
<head>
    <title>Acceso Denegado</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .error { color: #d32f2f; font-size: 24px; margin-bottom: 20px; }
        .message { color: #666; font-size: 16px; }
    </style>
</head>
<body>
    <div class="error">🔒 Acceso Denegado</div>
    <div class="message">
        <p>No tienes permiso para acceder a este recurso.</p>
        <p>URL bloqueada: <code>' . htmlspecialchars($path) . '</code></p>
        <p><a href="/">Volver al inicio</a></p>
    </div>
</body>
</html>';
        exit;
    }
}

// Si la URL no es la raíz y no tiene parámetros GET, redirigir
if ($path !== '/' && empty($_GET)) {
    // Verificar si es un archivo que existe físicamente
    $filePath = ROOT . $path;
    if (file_exists($filePath) && is_file($filePath)) {
        http_response_code(403);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html>
<html>
<head>
    <title>Acceso Denegado</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .error { color: #d32f2f; font-size: 24px; margin-bottom: 20px; }
        .message { color: #666; font-size: 16px; }
    </style>
</head>
<body>
    <div class="error">🔒 Acceso Denegado</div>
    <div class="message">
        <p>No tienes permiso para acceder directamente a archivos.</p>
        <p>Archivo bloqueado: <code>' . htmlspecialchars($path) . '</code></p>
        <p><a href="/">Volver al inicio</a></p>
    </div>
</body>
</html>';
        exit;
    }
}

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

// Incluir el router principal
require_once ROOT . '/app/scripts/routerView.php';