<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname((__DIR__))));
}

$view = $_GET['view'] ?? '';

// Seguridad extendida
if (
    empty($view) ||
    preg_match('/\.\.|\.env|config|\.htaccess/i', $view)
) {
    http_response_code(403);
    echo "<h2>Error 403</h2><p>Acceso denegado.</p>";
    exit;
}

// Validar carpeta permitida
$allowedDirs = ['root', 'teacher', 'headMaster', 'student', 'coordinator', 'treasurer'];
$parts = explode('/', $view);

// Si no está en carpetas permitidas
if (!in_array($parts[0], $allowedDirs)) {
    http_response_code(403);
    echo "<h2>Error 403</h2><p>No tienes permiso para acceder a esta vista.</p>";
    exit;
}

// Construir la ruta al archivo
$viewPath = ROOT . "views/" . $view . ".php";

// Si existe, mostrarla
if (file_exists($viewPath)) {
    require_once $viewPath;
} else {
    http_response_code(404);
    echo "<h2>Error 404</h2><p>La vista <code>$view</code> no existe.</p>";
}
