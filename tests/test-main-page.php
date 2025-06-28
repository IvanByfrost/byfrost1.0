<?php
// Script para simular el acceso a la página principal
echo "<h1>🏠 Simulación de Página Principal</h1>";

// Activar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>📋 Paso 1: Simular parámetros vacíos</h2>";

// Simular acceso a la página principal (sin parámetros)
$_GET = [];
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['QUERY_STRING'] = '';

echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>QUERY_STRING:</strong> " . $_SERVER['QUERY_STRING'] . "</p>";
echo "<p><strong>Parámetros GET:</strong> " . (empty($_GET) ? 'Vacío' : 'Tiene parámetros') . "</p>";

echo "<h2>📋 Paso 2: Probar configuración</h2>";

try {
    // Definir ROOT
    if (!defined('ROOT')) {
        define('ROOT', __DIR__);
    }
    
    require_once 'config.php';
    echo "<p style='color: green;'>✅ Configuración cargada</p>";
    echo "<p>ROOT: " . ROOT . "</p>";
    echo "<p>URL: " . url . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en configuración: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>📋 Paso 3: Probar SecurityMiddleware</h2>";

try {
    require_once 'app/library/SecurityMiddleware.php';
    
    // Probar validación de parámetros vacíos
    $validation = SecurityMiddleware::validateGetParams($_GET);
    echo "<p style='color: green;'>✅ Validación de parámetros: " . ($validation ? 'PASÓ' : 'FALLÓ') . "</p>";
    
    // Probar validación de vista vacía
    $viewValidation = SecurityMiddleware::validatePath('');
    echo "<p style='color: green;'>✅ Validación de vista vacía: " . ($viewValidation['valid'] ? 'PASÓ' : 'FALLÓ') . "</p>";
    
    if (!$viewValidation['valid']) {
        echo "<p style='color: red;'>❌ Error: " . $viewValidation['error'] . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en SecurityMiddleware: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>📋 Paso 4: Simular router</h2>";

try {
    // Simular parámetros que deberían funcionar
    $_GET['view'] = '';
    $_GET['action'] = '';
    
    echo "<p><strong>Parámetros simulados:</strong></p>";
    echo "<ul>";
    foreach ($_GET as $key => $value) {
        echo "<li><strong>$key:</strong> " . htmlspecialchars($value) . "</li>";
    }
    echo "</ul>";
    
    // Probar validación nuevamente
    $validation = SecurityMiddleware::validateGetParams($_GET);
    echo "<p style='color: green;'>✅ Validación con parámetros vacíos: " . ($validation ? 'PASÓ' : 'FALLÓ') . "</p>";
    
    $viewValidation = SecurityMiddleware::validatePath($_GET['view']);
    echo "<p style='color: green;'>✅ Validación de vista vacía: " . ($viewValidation['valid'] ? 'PASÓ' : 'FALLÓ') . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error simulando router: " . $e->getMessage() . "</p>";
}

echo "<h2>📋 Paso 5: Probar URLs específicas</h2>";

$testCases = [
    'Página principal (sin parámetros)' => ['view' => '', 'action' => ''],
    'Login' => ['view' => 'index', 'action' => 'login'],
    'Dashboard' => ['view' => 'root', 'action' => 'dashboard'],
    'Asignar Roles' => ['view' => 'user', 'action' => 'assignRole']
];

foreach ($testCases as $name => $params) {
    echo "<h3>$name</h3>";
    
    try {
        $viewValidation = SecurityMiddleware::validatePath($params['view']);
        $actionValidation = SecurityMiddleware::validatePath($params['action']);
        
        echo "<p>Vista: " . ($viewValidation['valid'] ? '✅ Válida' : '❌ Inválida') . "</p>";
        echo "<p>Acción: " . ($actionValidation['valid'] ? '✅ Válida' : '❌ Inválida') . "</p>";
        
        if (!$viewValidation['valid']) {
            echo "<p style='color: red;'>Error vista: " . $viewValidation['error'] . "</p>";
        }
        if (!$actionValidation['valid']) {
            echo "<p style='color: red;'>Error acción: " . $actionValidation['error'] . "</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>🚀 URLs de Prueba</h2>";
echo "<p>Prueba estas URLs específicas:</p>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/' target='_blank'>http://localhost:8000/</a> (Página principal)</li>";
echo "<li><a href='http://localhost:8000/?view=index&action=login' target='_blank'>http://localhost:8000/?view=index&action=login</a> (Login)</li>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>http://localhost:8000/?view=root&action=dashboard</a> (Dashboard)</li>";
echo "</ul>";

echo "<h2>🔧 Solución si sigue fallando</h2>";
echo "<p>Si sigues viendo el error 403, el problema puede ser:</p>";
echo "<ol>";
echo "<li><strong>El servidor no está corriendo</strong> - Ejecuta: <code>F:\\xampp\\php\\php.exe -S localhost:8000</code></li>";
echo "<li><strong>Conflicto de puerto</strong> - Cambia el puerto: <code>F:\\xampp\\php\\php.exe -S localhost:8080</code></li>";
echo "<li><strong>Archivos corruptos</strong> - Verifica que todos los archivos estén intactos</li>";
echo "<li><strong>Permisos</strong> - Asegúrate de que PHP pueda leer todos los archivos</li>";
echo "</ol>";

echo "<h2>📞 Información de Debug</h2>";
echo "<div style='background: #e9ecef; padding: 15px; border-radius: 5px;'>";
echo "<p><strong>Directorio actual:</strong> " . __DIR__ . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Archivo actual:</strong> " . __FILE__ . "</p>";
echo "</div>";
?> 