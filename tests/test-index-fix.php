<?php
// Test para verificar que la página principal funciona correctamente
echo "<h1>Test de la página principal</h1>";

// Simular acceso a index.php sin parámetros
$_GET = [];
$_SERVER['REQUEST_URI'] = '/';

echo "<h2>Probando acceso a la página principal...</h2>";
echo "<p>URL: <a href='index.php' target='_blank'>index.php</a></p>";

// Verificar que las variables están definidas
echo "<h2>Verificando variables de configuración:</h2>";
echo "<ul>";
echo "<li>ROOT: " . (defined('ROOT') ? ROOT : 'NO DEFINIDA') . "</li>";
echo "<li>url: " . (defined('url') ? url : 'NO DEFINIDA') . "</li>";
echo "<li>app: " . (defined('app') ? app : 'NO DEFINIDA') . "</li>";
echo "<li>rq: " . (defined('rq') ? rq : 'NO DEFINIDA') . "</li>";
echo "</ul>";

// Verificar que los archivos existen
echo "<h2>Verificando archivos:</h2>";
echo "<ul>";
echo "<li>IndexController: " . (file_exists('app/controllers/indexController.php') ? 'EXISTE' : 'NO EXISTE') . "</li>";
echo "<li>Vista index/index.php: " . (file_exists('app/views/index/index.php') ? 'EXISTE' : 'NO EXISTE') . "</li>";
echo "<li>MainController: " . (file_exists('app/controllers/MainController.php') ? 'EXISTE' : 'NO EXISTE') . "</li>";
echo "</ul>";

echo "<h2>Instrucciones:</h2>";
echo "<p>1. Haz clic en el enlace 'index.php' para probar la página principal</p>";
echo "<p>2. Si funciona, deberías ver la página de inicio con el slider</p>";
echo "<p>3. Si hay errores, revisa la consola del navegador y los logs del servidor</p>";
?> 