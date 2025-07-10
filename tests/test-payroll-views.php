<?php
// Test específico para verificar que las vistas de nómina se cargan correctamente
echo "<h1>🔍 Test de Vistas de Nómina</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once '../config.php';
require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/library/SessionManager.php';
require_once ROOT . '/app/controllers/MainController.php';
require_once ROOT . '/app/controllers/PayrollController.php';

// Inicializar conexión y controlador
$dbConn = getConnection();
$payrollController = new PayrollController($dbConn);

echo "<h2>1. Verificación de Archivos de Vistas:</h2>";
$views = [
    'payroll/dashboard',
    'payroll/employees', 
    'payroll/periods',
    'payroll/absences',
    'payroll/overtime',
    'payroll/bonuses',
    'payroll/reports'
];

echo "<ul>";
foreach ($views as $view) {
    $fullPath = ROOT . "/app/views/{$view}.php";
    $exists = file_exists($fullPath);
    $size = $exists ? filesize($fullPath) : 0;
    echo "<li><strong>$view.php:</strong> " . ($exists ? "✅ EXISTE ($size bytes)" : "❌ NO EXISTE") . "</li>";
}
echo "</ul>";

echo "<h2>2. Test de Carga de Vistas:</h2>";
echo "<div style='font-family: Arial, sans-serif;'>";

foreach ($views as $view) {
    echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
    echo "<h3>$view</h3>";
    
    $fullPath = ROOT . "/app/views/{$view}.php";
    if (file_exists($fullPath)) {
        echo "<p>✅ Archivo existe</p>";
        
        // Intentar cargar la vista
        try {
            ob_start();
            $payrollController->loadPartialView($view, ['test' => true]);
            $content = ob_get_clean();
            
            if (!empty($content)) {
                echo "<p>✅ Vista cargada correctamente</p>";
                echo "<p><strong>Contenido (primeros 200 caracteres):</strong></p>";
                echo "<div style='background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px; max-height: 100px; overflow-y: auto;'>";
                echo htmlspecialchars(substr($content, 0, 200)) . "...";
                echo "</div>";
            } else {
                echo "<p>❌ Vista cargada pero sin contenido</p>";
            }
        } catch (Exception $e) {
            echo "<p>❌ Error cargando vista: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p>❌ Archivo no existe</p>";
    }
    
    echo "</div>";
}

echo "</div>";

echo "<h2>3. Test de Métodos del Controlador:</h2>";
$methods = [
    'dashboard',
    'employees',
    'periods', 
    'absences',
    'overtime',
    'bonuses',
    'reports'
];

echo "<ul>";
foreach ($methods as $method) {
    if (method_exists($payrollController, $method)) {
        echo "<li>✅ Método <strong>$method()</strong> existe</li>";
    } else {
        echo "<li>❌ Método <strong>$method()</strong> NO existe</li>";
    }
}
echo "</ul>";

echo "<h2>4. Test de URLs Directas:</h2>";
echo "<div style='font-family: Arial, sans-serif;'>";

foreach ($methods as $method) {
    echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
    echo "<h3>$method</h3>";
    echo "<p><strong>URL:</strong> <a href='../?view=payroll&action=$method' target='_blank'>?view=payroll&action=$method</a></p>";
    echo "<p><strong>Debug:</strong> <a href='../?view=payroll&action=$method&debug=1' target='_blank'>Con debug</a></p>";
    echo "</div>";
}

echo "</div>";

echo "<h2>5. Test de loadPartialView del MainController:</h2>";
try {
    echo "<p>Probando loadPartialView con 'payroll/dashboard':</p>";
    ob_start();
    $payrollController->loadPartialView('payroll/dashboard', ['test' => true]);
    $content = ob_get_clean();
    
    if (!empty($content)) {
        echo "<p>✅ loadPartialView funciona correctamente</p>";
        echo "<p><strong>Contenido (primeros 100 caracteres):</strong></p>";
        echo "<div style='background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px;'>";
        echo htmlspecialchars(substr($content, 0, 100)) . "...";
        echo "</div>";
    } else {
        echo "<p>❌ loadPartialView no retorna contenido</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Error en loadPartialView: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<h2>6. Instrucciones de Diagnóstico:</h2>";
echo "<ol>";
echo "<li>Si todos los archivos existen pero las vistas no cargan, el problema está en el método loadPartialView</li>";
echo "<li>Si los archivos no existen, necesitas crear las vistas faltantes</li>";
echo "<li>Si los métodos del controlador no existen, necesitas agregarlos</li>";
echo "<li>Si las URLs directas no funcionan, el problema está en el routing</li>";
echo "</ol>";

echo "<h2>7. Posibles Soluciones:</h2>";
echo "<ul>";
echo "<li><strong>Problema de loadPartialView:</strong> Verificar que el método esté implementado correctamente en MainController</li>";
echo "<li><strong>Problema de routing:</strong> Verificar que 'payroll' esté mapeado a 'PayrollController'</li>";
echo "<li><strong>Problema de permisos:</strong> Verificar que el usuario tenga permisos para acceder a nómina</li>";
echo "<li><strong>Problema de base de datos:</strong> Verificar que las tablas de nómina existan</li>";
echo "</ul>";
?> 