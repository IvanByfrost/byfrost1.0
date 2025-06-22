<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

$view = $_GET['view'] ?? null;

// Seguridad básica: no permitir rutas peligrosas
if (!$view || strpos($view, '..') !== false || strpos($view, '.env') !== false) {
    http_response_code(403);
    exit('Acceso denegado');
}

$viewPath = ROOT . "/app/views/" . $view . ".php";

if (file_exists($viewPath)) {
    require_once $viewPath;
} else {
    http_response_code(404);
    echo "<h2>Error 404</h2><p>La vista solicitada no existe: <code>$view</code></p>";
}