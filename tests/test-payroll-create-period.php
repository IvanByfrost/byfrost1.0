<?php
// Test específico para payroll/createPeriod
require_once '../config.php';

echo "<h2>🧪 Test: Payroll Create Period</h2>";

// Simular la llamada directa
$view = 'payroll';
$action = 'createPeriod';

echo "<h3>📋 Parámetros de Test:</h3>";
echo "<ul>";
echo "<li><strong>view:</strong> {$view}</li>";
echo "<li><strong>action:</strong> {$action}</li>";
echo "</ul>";

// Verificar el mapeo automático
function getControllerMapping() {
    $controllersDir = ROOT . '/app/controllers/';
    $mapping = [];
    
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
    
    if (is_dir($controllersDir)) {
        $files = scandir($controllersDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $controllerName = pathinfo($file, PATHINFO_FILENAME);
                $viewName = strtolower(str_replace('Controller', '', $controllerName));
                
                if (!isset($specialMapping[$viewName])) {
                    $mapping[$viewName] = $controllerName;
                }
            }
        }
    }
    
    return array_merge($mapping, $specialMapping);
}

$controllerMapping = getControllerMapping();

echo "<h3>🔍 Verificación del Mapeo:</h3>";
if (isset($controllerMapping[$view])) {
    $controllerName = $controllerMapping[$view];
    $controllerPath = ROOT . "/app/controllers/{$controllerName}.php";
    
    echo "<p>✅ <strong>Controlador encontrado:</strong> {$controllerName}</p>";
    echo "<p>📁 <strong>Ruta del archivo:</strong> {$controllerPath}</p>";
    
    if (file_exists($controllerPath)) {
        echo "<p>✅ <strong>Archivo existe:</strong> Sí</p>";
        
        // Verificar si el método existe
        require_once $controllerPath;
        require_once ROOT . '/app/scripts/connection.php';
        $dbConn = getConnection();
        
        $controller = new $controllerName($dbConn, null);
        
        if (method_exists($controller, $action)) {
            echo "<p>✅ <strong>Método {$action} existe:</strong> Sí</p>";
            
            // Verificar permisos simulando sesión
            echo "<h3>🔐 Verificación de Permisos:</h3>";
            echo "<p>Para probar createPeriod necesitas tener rol: root, director</p>";
            
            // Intentar cargar la vista directamente
            echo "<h3>🎯 Test de Carga Directa:</h3>";
            echo "<p><a href='../index.php?view=payroll&action=createPeriod' target='_blank'>Probar URL Directa</a></p>";
            
        } else {
            echo "<p>❌ <strong>Método {$action} existe:</strong> No</p>";
            echo "<p>Métodos disponibles:</p>";
            $methods = get_class_methods($controller);
            echo "<ul>";
            foreach ($methods as $method) {
                if ($method !== '__construct' && !str_starts_with($method, '_')) {
                    echo "<li><code>{$method}</code></li>";
                }
            }
            echo "</ul>";
        }
        
    } else {
        echo "<p>❌ <strong>Archivo existe:</strong> No</p>";
    }
} else {
    echo "<p>❌ <strong>Controlador encontrado:</strong> No</p>";
    echo "<p>Vistas disponibles:</p>";
    echo "<ul>";
    foreach (array_keys($controllerMapping) as $availableView) {
        echo "<li><code>{$availableView}</code></li>";
    }
    echo "</ul>";
}

echo "<h3>🔧 Debug de JavaScript:</h3>";
echo "<p>El problema puede estar en cómo safeLoadView construye la URL:</p>";
echo "<ul>";
echo "<li>safeLoadView('payroll/createPeriod') debería generar: ?view=payroll&action=createPeriod</li>";
echo "<li>Verifica en la consola del navegador si hay errores</li>";
echo "</ul>";

echo "<h3>🎯 URLs de Prueba:</h3>";
echo "<ul>";
echo "<li><a href='../index.php?view=payroll&action=createPeriod' target='_blank'>URL Directa</a></li>";
echo "<li><a href='../index.php?view=payroll' target='_blank'>Dashboard Payroll</a></li>";
echo "<li><a href='../index.php?view=payroll&action=periods' target='_blank'>Lista de Períodos</a></li>";
echo "</ul>";

echo "<h3>📝 Instrucciones:</h3>";
echo "<ol>";
echo "<li>Abre la consola del navegador (F12)</li>";
echo "<li>Ve a la página de períodos</li>";
echo "<li>Haz clic en 'Nuevo Período'</li>";
echo "<li>Revisa los logs en la consola para ver qué URL se está generando</li>";
echo "</ol>";
?> 