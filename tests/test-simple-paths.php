<?php
echo "=== Test Simple de Rutas ===\n";

// 1. Verificar directorio actual
echo "Directorio actual: " . __DIR__ . "\n";

// 2. Simular routerView.php
$routerDir = __DIR__ . '/app/scripts/';
echo "Directorio del router (simulado): " . $routerDir . "\n";

// 3. Calcular ROOT como lo hace routerView.php
$root = dirname(dirname(dirname($routerDir))) . '/';
echo "ROOT calculado: " . $root . "\n";

// 4. Verificar archivos
$configFile = $root . 'config.php';
echo "config.php: " . $configFile . " - " . (file_exists($configFile) ? "EXISTE" : "NO EXISTE") . "\n";

$connectionFile = $root . 'app/scripts/connection.php';
echo "connection.php: " . $connectionFile . " - " . (file_exists($connectionFile) ? "EXISTE" : "NO EXISTE") . "\n";

$userControllerFile = $root . 'app/controllers/UserController.php';
echo "UserController.php: " . $userControllerFile . " - " . (file_exists($userControllerFile) ? "EXISTE" : "NO EXISTE") . "\n";

echo "=== Fin del Test ===\n";
?> 