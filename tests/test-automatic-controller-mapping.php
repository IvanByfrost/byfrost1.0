<?php
// Test del sistema automático de mapeo de controladores
require_once '../config.php';

echo "<h2>🧪 Test: Sistema Automático de Mapeo de Controladores</h2>";

// Simular la función getControllerMapping
function getControllerMapping() {
    $controllersDir = ROOT . '/app/controllers/';
    $mapping = [];
    
    // Mapeo especial para vistas que no siguen la convención
    $specialMapping = [
        'login' => 'IndexController',
        'register' => 'IndexController', 
        'contact' => 'IndexController',
        'about' => 'IndexController',
        'plans' => 'IndexController',
        'faq' => 'IndexController',
        'forgotPassword' => 'IndexController',
        'resetPassword' => 'IndexController',
        'completeProf' => 'IndexController',
        'unauthorized' => 'ErrorController',
        'Error' => 'ErrorController'
    ];
    
    // Escanear directorio de controladores automáticamente
    if (is_dir($controllersDir)) {
        $files = scandir($controllersDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $controllerName = pathinfo($file, PATHINFO_FILENAME);
                $viewName = strtolower(str_replace('Controller', '', $controllerName));
                
                // Solo agregar si no está en el mapeo especial
                if (!isset($specialMapping[$viewName])) {
                    $mapping[$viewName] = $controllerName;
                }
            }
        }
    }
    
    // Combinar mapeo automático con mapeo especial
    return array_merge($mapping, $specialMapping);
}

$controllerMapping = getControllerMapping();

echo "<h3>📋 Mapeo Automático Generado:</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Vista</th><th>Controlador</th><th>Archivo Existe</th></tr>";

foreach ($controllerMapping as $view => $controller) {
    $controllerPath = ROOT . "/app/controllers/{$controller}.php";
    $fileExists = file_exists($controllerPath) ? "✅ Sí" : "❌ No";
    
    echo "<tr>";
    echo "<td><code>{$view}</code></td>";
    echo "<td><code>{$controller}</code></td>";
    echo "<td>{$fileExists}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>🔍 Controladores Encontrados:</h3>";
$controllersDir = ROOT . '/app/controllers/';
if (is_dir($controllersDir)) {
    $files = scandir($controllersDir);
    $controllers = array_filter($files, function($file) {
        return $file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php';
    });
    
    echo "<ul>";
    foreach ($controllers as $controller) {
        echo "<li><code>{$controller}</code></li>";
    }
    echo "</ul>";
}

echo "<h3>✅ Ventajas del Sistema Automático:</h3>";
echo "<ul>";
echo "<li>🚀 <strong>Automático:</strong> No necesitas agregar manualmente cada controlador</li>";
echo "<li>🔧 <strong>Mantenible:</strong> Se actualiza automáticamente cuando agregas/eliminas controladores</li>";
echo "<li>📏 <strong>Convención:</strong> Sigue la convención 'NombreController.php' → 'nombre'</li>";
echo "<li>🎯 <strong>Flexible:</strong> Permite mapeos especiales para casos específicos</li>";
echo "</ul>";

echo "<h3>🎯 Prueba de URLs:</h3>";
echo "<p>Ahora puedes probar estas URLs que deberían funcionar automáticamente:</p>";
echo "<ul>";
echo "<li><a href='../index.php?view=payroll' target='_blank'>Payroll Dashboard</a></li>";
echo "<li><a href='../index.php?view=user' target='_blank'>User Management</a></li>";
echo "<li><a href='../index.php?view=role' target='_blank'>Role Management</a></li>";
echo "<li><a href='../index.php?view=activity' target='_blank'>Activity Management</a></li>";
echo "</ul>";

echo "<p><strong>🎉 ¡El sistema ahora es mucho más mantenible y escalable!</strong></p>";
?> 