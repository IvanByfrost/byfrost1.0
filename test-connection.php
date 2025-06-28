<?php
// Test básico de configuración
echo "<h1>Test de Configuración</h1>";

// Verificar config.php
echo "<h2>1. Verificando config.php</h2>";
require_once 'config.php';
echo "BASE_URL: " . BASE_URL . "<br>";
echo "url: " . url . "<br>";

// Verificar conexión a BD
echo "<h2>2. Verificando conexión a BD</h2>";
try {
    require_once 'app/scripts/connection.php';
    $db = getConnection();
    echo "✅ Conexión a BD exitosa<br>";
} catch (Exception $e) {
    echo "❌ Error de BD: " . $e->getMessage() . "<br>";
}

// Verificar archivos principales
echo "<h2>3. Verificando archivos principales</h2>";
$files = ['index.php', '.htaccess', 'app/library/Router.php'];
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file existe<br>";
    } else {
        echo "❌ $file NO existe<br>";
    }
}

// Verificar variables de servidor
echo "<h2>4. Variables de servidor</h2>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'No definido') . "<br>";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'No definido') . "<br>";
echo "SERVER_PORT: " . ($_SERVER['SERVER_PORT'] ?? 'No definido') . "<br>";
?> 