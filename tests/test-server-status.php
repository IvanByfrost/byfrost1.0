<?php
// Script para verificar el estado del servidor web
echo "<h1>🌐 Diagnóstico del Servidor Web</h1>";

// 1. Información del servidor
echo "<h2>1. Información del Servidor</h2>";
echo "✅ Servidor web funcionando<br>";
echo "Software del servidor: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'No disponible') . "<br>";
echo "Puerto: " . ($_SERVER['SERVER_PORT'] ?? 'No disponible') . "<br>";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'No disponible') . "<br>";
echo "Script Name: " . ($_SERVER['SCRIPT_NAME'] ?? 'No disponible') . "<br>";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'No disponible') . "<br>";
echo "HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'No disponible') . "<br>";
echo "<br>";

// 2. Verificar si Apache está ejecutándose
echo "<h2>2. Estado de Apache</h2>";
$apacheStatus = false;

// Intentar diferentes métodos para verificar Apache
if (function_exists('apache_get_modules')) {
    echo "✅ Módulo Apache detectado<br>";
    $apacheStatus = true;
}

if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false) {
    echo "✅ Apache detectado en SERVER_SOFTWARE<br>";
    $apacheStatus = true;
}

if (!$apacheStatus) {
    echo "⚠️ No se pudo detectar Apache directamente<br>";
    echo "💡 Esto es normal si estás usando XAMPP o similar<br>";
}

echo "<br>";

// 3. Verificar archivos críticos
echo "<h2>3. Verificación de Archivos Críticos</h2>";
$criticalFiles = [
    'index.php' => 'Archivo principal',
    'config.php' => 'Configuración',
    'app/scripts/connection.php' => 'Conexión a BD',
    'app/scripts/routerView.php' => 'Router',
    '.htaccess' => 'Configuración Apache'
];

foreach ($criticalFiles as $file => $description) {
    if (file_exists($file)) {
        echo "✅ $description ($file) existe<br>";
    } else {
        echo "❌ $description ($file) NO existe<br>";
    }
}

echo "<br>";

// 4. Verificar permisos
echo "<h2>4. Verificación de Permisos</h2>";
$testDir = __DIR__;
echo "Directorio actual: $testDir<br>";
echo "Permisos del directorio: " . substr(sprintf('%o', fileperms($testDir)), -4) . "<br>";
echo "Legible: " . (is_readable($testDir) ? '✅ Sí' : '❌ No') . "<br>";
echo "Ejecutable: " . (is_executable($testDir) ? '✅ Sí' : '❌ No') . "<br>";

if (file_exists('index.php')) {
    echo "Permisos de index.php: " . substr(sprintf('%o', fileperms('index.php')), -4) . "<br>";
    echo "Legible: " . (is_readable('index.php') ? '✅ Sí' : '❌ No') . "<br>";
    echo "Ejecutable: " . (is_executable('index.php') ? '✅ Sí' : '❌ No') . "<br>";
}

echo "<br>";

// 5. Verificar configuración PHP
echo "<h2>5. Configuración PHP</h2>";
echo "display_errors: " . (ini_get('display_errors') ? '✅ Activado' : '❌ Desactivado') . "<br>";
echo "error_reporting: " . ini_get('error_reporting') . "<br>";
echo "log_errors: " . (ini_get('log_errors') ? '✅ Activado' : '❌ Desactivado') . "<br>";
echo "error_log: " . (ini_get('error_log') ?: 'No configurado') . "<br>";
echo "max_execution_time: " . ini_get('max_execution_time') . " segundos<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";

echo "<br>";

// 6. Probar rutas de la aplicación
echo "<h2>6. Prueba de Rutas</h2>";
$testUrls = [
    '/' => 'Página principal',
    '/index.php' => 'Index.php directo',
    '/?view=index' => 'Index con parámetro',
    '/?view=login' => 'Página de login'
];

echo "<h3>URLs para probar:</h3>";
$baseUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT']);
echo "URL base: $baseUrl<br><br>";

foreach ($testUrls as $url => $description) {
    $fullUrl = $baseUrl . $url;
    echo "<a href='$fullUrl' target='_blank'>🔗 $description</a> ($fullUrl)<br>";
}

echo "<br>";

// 7. Instrucciones de solución
echo "<h2>7. Instrucciones de Solución</h2>";
echo "<h3>Si Apache no está ejecutándose:</h3>";
echo "1. Abre XAMPP Control Panel<br>";
echo "2. Inicia Apache<br>";
echo "3. Verifica que no haya conflictos de puerto<br>";
echo "<br>";

echo "<h3>Si hay errores de permisos:</h3>";
echo "1. Verifica que los archivos tengan permisos de lectura<br>";
echo "2. En Windows, asegúrate de que el usuario del servidor tenga acceso<br>";
echo "<br>";

echo "<h3>Si hay errores de configuración:</h3>";
echo "1. Revisa el archivo .htaccess<br>";
echo "2. Verifica que mod_rewrite esté habilitado<br>";
echo "3. Comprueba la configuración de PHP<br>";
echo "<br>";

echo "<h3>Para activar errores de PHP:</h3>";
echo "Agrega esto al inicio de index.php:<br>";
echo "<code>ini_set('display_errors', 1);<br>";
echo "ini_set('error_reporting', E_ALL);</code><br>";

echo "<br><hr>";
echo "<p><strong>💡 Después de verificar estos puntos, intenta acceder a tu aplicación nuevamente.</strong></p>";
?> 