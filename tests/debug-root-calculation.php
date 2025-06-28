<?php
/**
 * Debug del cálculo de ROOT
 */

echo "<h1>Debug: Cálculo de ROOT</h1>";

// Estamos en el directorio raíz del proyecto
echo "<h2>1. Información del directorio actual:</h2>";
echo "<p><strong>__DIR__:</strong> " . __DIR__ . "</p>";

// Simular estar en app/scripts/routerView.php
$routerDir = __DIR__ . '/app/scripts/';
echo "<p><strong>Directorio del router (simulado):</strong> " . $routerDir . "</p>";

echo "<h2>2. Cálculo paso a paso:</h2>";
$step1 = dirname($routerDir);
echo "<p><strong>Paso 1 (dirname):</strong> " . $step1 . "</p>";

$step2 = dirname($step1);
echo "<p><strong>Paso 2 (dirname):</strong> " . $step2 . "</p>";

$step3 = dirname($step2);
echo "<p><strong>Paso 3 (dirname):</strong> " . $step3 . "</p>";

$final = $step3 . '/';
echo "<p><strong>Final (con /):</strong> " . $final . "</p>";

echo "<h2>3. Verificar archivos:</h2>";
$configFile = $final . 'config.php';
echo "<p><strong>config.php:</strong> " . $configFile . " - " . (file_exists($configFile) ? "EXISTE" : "NO EXISTE") . "</p>";

$connectionFile = $final . 'app/scripts/connection.php';
echo "<p><strong>connection.php:</strong> " . $connectionFile . " - " . (file_exists($connectionFile) ? "EXISTE" : "NO EXISTE") . "</p>";

echo "<h2>4. Probar inclusión:</h2>";
try {
    require_once $configFile;
    echo "<div style='color: green;'>✅ config.php cargado correctamente</div>";
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error: " . $e->getMessage() . "</div>";
}

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Debug completado</p>";
?> 