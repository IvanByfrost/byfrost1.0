<?php
define('ROOT', dirname(__DIR__));
require_once 'config.php'; // Asegúrate que define constantes como DB_HOST, DB_NAME, etc.

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
try {
    $dbConn = new PDO("mysql:host=localhost;dbname=baldur-test", 'root', '');
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Instancia de la clase de vistas
$view = new Views();

// Iniciar enrutador
$router = new Router($dbConn, $view);