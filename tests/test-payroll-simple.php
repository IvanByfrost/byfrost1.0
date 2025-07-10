<?php
// Test simple para verificar el dashboard de nómina sin sidebar
echo "<h1>🔧 Test Simple - Dashboard Nómina</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once '../config.php';

echo "<h2>1. Verificación Básica:</h2>";
echo "<p><strong>ROOT:</strong> " . ROOT . "</p>";
echo "<p><strong>URL:</strong> " . url . "</p>";

echo "<h2>2. Test de Archivos:</h2>";
$files = [
    '../config.php',
    '../app/controllers/payrollController.php',
    '../app/models/payrollModel.php',
    '../app/views/payroll/dashboard.php'
];

echo "<ul>";
foreach ($files as $file) {
    $fullPath = ROOT . '/' . $file;
    $exists = file_exists($fullPath);
    echo "<li><strong>$file:</strong> " . ($exists ? "✅ EXISTE" : "❌ NO EXISTE") . "</li>";
}
echo "</ul>";

echo "<h2>3. Test de URLs Directas:</h2>";
echo "<p><a href='../?view=payroll&action=dashboard' target='_blank'>Dashboard de Nómina</a></p>";
echo "<p><a href='../?view=payroll&action=employees' target='_blank'>Empleados</a></p>";
echo "<p><a href='../?view=payroll&action=periods' target='_blank'>Períodos</a></p>";

echo "<h2>4. Test de Base de Datos:</h2>";
try {
    require_once ROOT . '/app/scripts/connection.php';
    $conn = getConnection();
    echo "<p>✅ Conexión a base de datos exitosa</p>";
    
    // Verificar tablas básicas
    $tables = ['employees', 'payroll_periods'];
    foreach ($tables as $table) {
        try {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM $table");
            $stmt->execute();
            $count = $stmt->fetchColumn();
            echo "<p>✅ Tabla <strong>$table</strong>: $count registros</p>";
        } catch (Exception $e) {
            echo "<p>❌ Tabla <strong>$table</strong>: " . $e->getMessage() . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error de conexión: " . $e->getMessage() . "</p>";
}

echo "<h2>5. Test de Controlador:</h2>";
try {
    require_once ROOT . '/app/controllers/PayrollController.php';
    if (class_exists('PayrollController')) {
        echo "<p>✅ PayrollController existe</p>";
        
        // Verificar métodos
        $methods = ['dashboard', 'employees', 'periods'];
        foreach ($methods as $method) {
            if (method_exists('PayrollController', $method)) {
                echo "<p>✅ Método <strong>$method()</strong> existe</p>";
            } else {
                echo "<p>❌ Método <strong>$method()</strong> NO existe</p>";
            }
        }
    } else {
        echo "<p>❌ PayrollController NO existe</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Error cargando PayrollController: " . $e->getMessage() . "</p>";
}

echo "<h2>6. Test de Vista:</h2>";
$viewPath = ROOT . '/app/views/payroll/dashboard.php';
if (file_exists($viewPath)) {
    echo "<p>✅ Vista dashboard existe</p>";
    
    // Intentar cargar la vista directamente
    try {
        ob_start();
        $testData = [
            'total_employees' => 5,
            'monthly_payroll' => 15000000,
            'active_periods' => 1,
            'total_absences' => 2,
            'page_title' => 'Test Dashboard'
        ];
        extract($testData);
        require $viewPath;
        $content = ob_get_clean();
        
        if (!empty($content)) {
            echo "<p>✅ Vista dashboard se puede cargar directamente</p>";
            echo "<p><strong>Tamaño del contenido:</strong> " . strlen($content) . " caracteres</p>";
        } else {
            echo "<p>❌ Vista dashboard cargada pero sin contenido</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error cargando vista dashboard: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p>❌ Vista dashboard NO existe</p>";
}

echo "<h2>7. Instrucciones:</h2>";
echo "<ol>";
echo "<li>Si las URLs directas funcionan, el problema estaba en el sidebar</li>";
echo "<li>Si las URLs directas NO funcionan, ejecuta <a href='execute-payroll-sql.php'>execute-payroll-sql.php</a></li>";
echo "<li>Si hay errores de permisos, verifica que el usuario tenga rol root, director, coordinator o treasurer</li>";
echo "</ol>";

echo "<h2>8. Próximos Pasos:</h2>";
echo "<ul>";
echo "<li><a href='test-payroll-database.php'>Test Completo de Base de Datos</a></li>";
echo "<li><a href='test-payroll-urgent.php'>Test Urgente Completo</a></li>";
echo "<li><a href='../?view=payroll&action=dashboard'>Probar Dashboard Final</a></li>";
echo "</ul>";
?> 