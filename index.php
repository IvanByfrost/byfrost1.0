<?php
define('ROOT', __DIR__);

// Validación de seguridad - prevenir acceso directo a archivos sensibles
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($requestUri, PHP_URL_PATH);

// Debug: mostrar información de la ruta
error_log("DEBUG - Request URI: " . $requestUri);
error_log("DEBUG - Path extraído: " . $path);

// BLOQUEAR ACCESO DIRECTO A ARCHIVOS Y DIRECTORIOS SENSIBLES
$blockedPatterns = [
    '/\/config\.php/',  // Bloquear acceso directo a config.php
    '/\.env/',  // Bloquear archivos .env
    '/\/vendor\//',  // Bloquear directorio vendor
    '/\/node_modules\//',  // Bloquear directorio node_modules
    '/\.git/',  // Bloquear archivos .git
    '/\.htaccess/',  // Bloquear archivos .htaccess
    '/\.htpasswd/',  // Bloquear archivos .htpasswd
    '/\.sql/',  // Bloquear archivos .sql
    '/\.log/',  // Bloquear archivos .log
    '/\.bak/',  // Bloquear archivos .bak
    '/\.backup/',  // Bloquear archivos .backup
    '/\.tmp/',  // Bloquear archivos .tmp
    '/\.temp/',  // Bloquear archivos .temp
    '/\/app\/controllers\//',  // Bloquear acceso directo a controllers
    '/\/app\/models\//',  // Bloquear acceso directo a models
    '/\/app\/library\//',  // Bloquear acceso directo a library
    '/\/app\/scripts\//',  // Bloquear acceso directo a scripts
    '/\/app\/processes\//',  // Bloquear acceso directo a processes
    '/\/app\/views\/.*\.php$/',  // Bloquear acceso directo a archivos PHP en views
    '/\/app\/resources\/.*\.php$/'  // Bloquear acceso directo a archivos PHP en resources
];

// Verificar si la URL contiene patrones bloqueados (solo si no es la raíz)
if ($path !== '/' && $path !== '') {
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
}

// Si la URL no es la raíz y no tiene parámetros GET, verificar acceso directo a archivos
if ($path !== '/' && $path !== '' && empty($_GET)) {
    // Verificar si es un archivo que existe físicamente y no es index.php
    $filePath = ROOT . $path;
    if (file_exists($filePath) && is_file($filePath) && basename($path) !== 'index.php') {
        // Permitir acceso a recursos estáticos (CSS, JS, imágenes)
        $allowedExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        if (!in_array($extension, $allowedExtensions)) {
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

// Preparar el path para el router (remover barras iniciales y finales)
$routerPath = trim($path, '/');

// Si alguien intenta acceder directamente a archivos PHP específicos, redirigir a 404
if (strpos($routerPath, 'app/views/') === 0 || strpos($routerPath, 'app/controllers/') === 0 || strpos($routerPath, 'app/models/') === 0 || strpos($routerPath, 'app/library/') === 0 || strpos($routerPath, 'app/scripts/') === 0 || strpos($routerPath, 'app/processes/') === 0) {
    error_log("DEBUG - Intento de acceso directo a archivo o directorio protegido: " . $routerPath);
    http_response_code(404);
    require_once ROOT . '/app/controllers/errorController.php';
    $error = new ErrorController($dbConn, $view);
    $error->Error('404');
    exit;
}

// Si no hay parámetro 'view' en $_GET, establecerlo manualmente
if (!isset($_GET['view'])) {
    // Si es la raíz, usar 'index' como vista por defecto
    if ($routerPath === '') {
        $_GET['view'] = 'index';
        error_log("DEBUG - Establecido _GET['view'] = index (raíz)");
    } else {
        $_GET['view'] = $routerPath;
        error_log("DEBUG - Establecido _GET['view'] = " . $routerPath);
    }
}

// Incluir el router principal
require_once ROOT . '/app/scripts/routerView.php';