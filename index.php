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
require_once app .'/scripts/connection.php';
$dbConn = getConnection();

// Instancia de la clase de vistas
$view = new Views();

// Iniciar enrutador
$router = new Router($dbConn, $view);