<?php
/**
 * Script para verificar acceso al archivo de proceso
 */

// Configuración
define('ROOT', __DIR__);
require_once 'config.php';

echo "<h1>Prueba de Acceso al Proceso</h1>";

// Verificar si el archivo existe
$processFile = ROOT . '/app/processes/schoolProcess.php';
if (file_exists($processFile)) {
    echo "<p style='color: green;'>✓ El archivo schoolProcess.php existe</p>";
    echo "<p><strong>Ruta:</strong> $processFile</p>";
    
    // Mostrar contenido del archivo
    echo "<h3>Contenido del archivo:</h3>";
    echo "<pre style='background-color: #f8f9fa; padding: 10px; border-radius: 5px; max-height: 300px; overflow-y: auto;'>";
    echo htmlspecialchars(file_get_contents($processFile));
    echo "</pre>";
} else {
    echo "<p style='color: red;'>✗ El archivo schoolProcess.php NO existe</p>";
}

// Verificar URL de acceso
$processUrl = url . 'app/processes/schoolProcess.php';
echo "<h3>URL de Acceso:</h3>";
echo "<p><strong>URL:</strong> <a href='$processUrl' target='_blank'>$processUrl</a></p>";

// Probar acceso directo
echo "<h3>Prueba de Acceso Directo:</h3>";
echo "<p>Haciendo petición POST al archivo...</p>";

// Crear contexto para petición POST
$postData = http_build_query([
    'school_name' => 'Escuela de Prueba',
    'school_dane' => '11100123456',
    'school_document' => '900123456-7'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($postData)
        ],
        'content' => $postData
    ]
]);

try {
    $response = file_get_contents($processUrl, false, $context);
    echo "<p style='color: green;'>✓ Respuesta recibida:</p>";
    echo "<pre style='background-color: #e8f5e8; padding: 10px; border-radius: 5px;'>";
    echo htmlspecialchars($response);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error al acceder: " . $e->getMessage() . "</p>";
}

// Verificar logs de error
echo "<h3>Logs de Error:</h3>";
$errorLog = ini_get('error_log');
if ($errorLog && file_exists($errorLog)) {
    echo "<p><strong>Log de errores:</strong> $errorLog</p>";
    $logContent = file_get_contents($errorLog);
    if (strlen($logContent) > 1000) {
        $logContent = substr($logContent, -1000); // Últimos 1000 caracteres
    }
    echo "<pre style='background-color: #fff3cd; padding: 10px; border-radius: 5px; max-height: 200px; overflow-y: auto;'>";
    echo htmlspecialchars($logContent);
    echo "</pre>";
} else {
    echo "<p>No se encontró log de errores</p>";
}
?> 