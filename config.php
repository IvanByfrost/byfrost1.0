<?php
// Definir ROOT solo si no está definido
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/'); // Sin prefijo para desarrollo local
}

// Detectar automáticamente la URL base
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Si el host ya incluye el puerto, no agregar otro
    if (strpos($host, ':') !== false) {
        return $protocol . '://' . $host . '/';
    }
    
    $port = $_SERVER['SERVER_PORT'] ?? '';
    
    // Si el puerto es 80 (HTTP) o 443 (HTTPS), no lo incluimos
    if ($port == '80' || $port == '443') {
        $port = '';
    } else {
        $port = ':' . $port;
    }
    
    return $protocol . '://' . $host . $port . '/';
}

const lbs = 'library/';
const views = 'views/';
define ('app', 'app/');
define ('models', 'models/');
define ('controllers', 'controllers/');
define ('dft', 'views/layouts/');
define ('rq', 'resources/');
define ('url', getBaseUrl());

?>