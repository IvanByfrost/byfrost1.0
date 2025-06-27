<?php
// Archivo de prueba para verificar la ruta del login
require_once 'config.php';

echo "<h1>Test de Ruta de Login</h1>";

echo "<h2>Constantes:</h2>";
echo "<p><strong>url:</strong> " . url . "</p>";
echo "<p><strong>app:</strong> " . app . "</p>";
echo "<p><strong>views:</strong> " . views . "</p>";

echo "<h2>Rutas de prueba:</h2>";

// Ruta 1: url . "app/views/index/login.php"
$route1 = url . "app/views/index/login.php";
echo "<p><strong>Ruta 1:</strong> <a href='$route1' target='_blank'>$route1</a></p>";

// Ruta 2: url . app . views . "index/login.php"
$route2 = url . app . views . "index/login.php";
echo "<p><strong>Ruta 2:</strong> <a href='$route2' target='_blank'>$route2</a></p>";

// Ruta 3: url . app . views . "/index/login.php"
$route3 = url . app . views . "/index/login.php";
echo "<p><strong>Ruta 3:</strong> <a href='$route3' target='_blank'>$route3</a></p>";

echo "<h2>Verificación de archivo:</h2>";
$filePath = __DIR__ . "/app/views/index/login.php";
echo "<p><strong>Ruta del archivo:</strong> $filePath</p>";
echo "<p><strong>¿Existe el archivo?</strong> " . (file_exists($filePath) ? 'SÍ' : 'NO') . "</p>";

echo "<h2>Enlaces de prueba:</h2>";
echo "<p><a href='app/views/index/login.php'>Ruta relativa directa</a></p>";
echo "<p><a href='app/processes/outProcess.php'>Probar logout</a></p>";
?> 