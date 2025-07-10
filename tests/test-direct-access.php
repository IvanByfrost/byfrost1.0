<?php
require_once '../config.php';
require_once '../app/scripts/connection.php';
require_once '../app/library/SessionManager.php';

// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo "Error: Usuario no logueado";
    exit;
}

echo "<h1>Test Acceso Directo</h1>";
echo "<p>Probando acceso directo al controlador director</p>";

// Simular los parámetros que llegarían
$_GET['view'] = 'director';
$_GET['action'] = 'loadPartial';
$_GET['partialView'] = 'dashboardPartial';
$_GET['debug'] = '1';

echo "<h2>Parámetros simulados:</h2>";
echo "<ul>";
echo "<li>view: " . $_GET['view'] . "</li>";
echo "<li>action: " . $_GET['action'] . "</li>";
echo "<li>partialView: " . $_GET['partialView'] . "</li>";
echo "<li>debug: " . $_GET['debug'] . "</li>";
echo "</ul>";

// Verificar si el archivo existe
$viewPath = 'director/dashboardPartial';
$fullPath = ROOT . "/app/views/{$viewPath}.php";

echo "<h2>Verificación de archivo:</h2>";
echo "<ul>";
echo "<li>viewPath: " . $viewPath . "</li>";
echo "<li>fullPath: " . $fullPath . "</li>";
echo "<li>file_exists: " . (file_exists($fullPath) ? 'true' : 'false') . "</li>";
echo "</ul>";

if (file_exists($fullPath)) {
    echo "<h2>Archivo encontrado, cargando contenido:</h2>";
    echo "<div style='border: 1px solid #ccc; padding: 10px; background: #f9f9f9;'>";
    include $fullPath;
    echo "</div>";
} else {
    echo "<h2>Error: Archivo no encontrado</h2>";
    echo "<p>El archivo <code>" . htmlspecialchars($fullPath) . "</code> no existe.</p>";
    
    // Listar archivos en el directorio director
    $directorDir = ROOT . "/app/views/director/";
    if (is_dir($directorDir)) {
        echo "<h3>Archivos disponibles en director/:</h3>";
        echo "<ul>";
        $files = scandir($directorDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "<li>" . htmlspecialchars($file) . "</li>";
            }
        }
        echo "</ul>";
    }
}
?> 