<?php
define('ROOT', dirname(__DIR__));
require_once ROOT . '/config.php';
//echo "Ruta ROOT: " . ROOT;

// Autocarga
spl_autoload_register(function ($class) {
    $paths = ['Library', 'Controllers', 'Models'];
    foreach ($paths as $folder) {
        $file = __DIR__ . "/$folder/$class.php";
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Conexión a la base de datos
require_once 'connection.php';
$dbConn = getConnection();

// Instancia de la clase de vistas
$view = new Views();

// Iniciar enrutador
$router = new Router($dbConn, $view);