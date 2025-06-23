<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__, 2)); // Ruta absoluta al proyecto
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/byfrost1.0'); // Ruta para redirecciones
}
const lbs = 'library/';
const views = 'views/';
define ('app', 'app/');
define ('models', 'models/');
define ('controllers', 'controllers/');
define ('dft', 'views/layouts/');
define ('rq', 'resources/');
define ('url', 'http://localhost:8000/app/');

?>