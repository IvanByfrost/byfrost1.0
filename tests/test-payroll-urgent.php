<?php
// Test urgente para diagnosticar problemas de nómina
echo "<h1>🚨 Test Urgente - Nómina</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once '../config.php';

echo "<h2>1. Verificación Básica:</h2>";
echo "<p><strong>ROOT:</strong> " . ROOT . "</p>";
echo "<p><strong>URL:</strong> " . url . "</p>";

echo "<h2>2. Verificación de Archivos Críticos:</h2>";
$criticalFiles = [
    '../config.php',
    '../app/controllers/payrollController.php',
    '../app/models/payrollModel.php',
    '../app/views/payroll/dashboard.php',
    '../app/scripts/routerView.php',
    '../app/resources/js/loadView.js'
];

echo "<ul>";
foreach ($criticalFiles as $file) {
    $fullPath = ROOT . '/' . $file;
    $exists = file_exists($fullPath);
    $size = $exists ? filesize($fullPath) : 0;
    echo "<li><strong>$file:</strong> " . ($exists ? "✅ EXISTE ($size bytes)" : "❌ NO EXISTE") . "</li>";
}
echo "</ul>";

echo "<h2>3. Test de Routing:</h2>";
echo "<p><a href='../?view=payroll' target='_blank'>Test Payroll sin acción</a></p>";
echo "<p><a href='../?view=payroll&action=dashboard' target='_blank'>Test Payroll Dashboard</a></p>";
echo "<p><a href='../?view=payroll&action=employees' target='_blank'>Test Payroll Employees</a></p>";

echo "<h2>4. Test de loadPartial:</h2>";
echo "<p><a href='../?view=index&action=loadPartial&view=payroll&action=dashboard&force=1' target='_blank'>Dashboard via loadPartial</a></p>";

echo "<h2>5. Test de JavaScript:</h2>";
echo "<p><a href='test-payroll-sidebar.php' target='_blank'>Test Sidebar JavaScript</a></p>";

echo "<h2>6. Test de Base de Datos:</h2>";
try {
    require_once ROOT . '/app/scripts/connection.php';
    $conn = getConnection();
    echo "<p>✅ Conexión a base de datos exitosa</p>";
    
    // Verificar tablas de nómina
    $tables = ['employees', 'payroll_periods', 'payroll_records'];
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

echo "<h2>7. Test de Controlador:</h2>";
try {
    require_once ROOT . '/app/controllers/payrollController.php';
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

echo "<h2>8. Test de Vista Directa:</h2>";
$viewPath = ROOT . '/app/views/payroll/dashboard.php';
if (file_exists($viewPath)) {
    echo "<p>✅ Vista dashboard existe</p>";
    
    // Intentar cargar la vista directamente
    try {
        ob_start();
        $testData = ['test' => true, 'page_title' => 'Test Dashboard'];
        extract($testData);
        require $viewPath;
        $content = ob_get_clean();
        
        if (!empty($content)) {
            echo "<p>✅ Vista dashboard se puede cargar directamente</p>";
            echo "<p><strong>Contenido (primeros 50 caracteres):</strong></p>";
            echo "<div style='background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px;'>";
            echo htmlspecialchars(substr($content, 0, 50)) . "...";
            echo "</div>";
        } else {
            echo "<p>❌ Vista dashboard cargada pero sin contenido</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error cargando vista dashboard: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p>❌ Vista dashboard NO existe</p>";
}

echo "<h2>9. Test de Permisos:</h2>";
try {
    require_once ROOT . '/app/library/SessionManager.php';
    $sessionManager = new SessionManager();
    
    echo "<p><strong>Usuario logueado:</strong> " . ($sessionManager->isLoggedIn() ? 'Sí' : 'No') . "</p>";
    
    if ($sessionManager->isLoggedIn()) {
        echo "<p><strong>Rol:</strong> " . $sessionManager->getUserRole() . "</p>";
        echo "<p><strong>Tiene rol root:</strong> " . ($sessionManager->hasRole('root') ? 'Sí' : 'No') . "</p>";
        echo "<p><strong>Tiene rol director:</strong> " . ($sessionManager->hasRole('director') ? 'Sí' : 'No') . "</p>";
        echo "<p><strong>Tiene rol coordinator:</strong> " . ($sessionManager->hasRole('coordinator') ? 'Sí' : 'No') . "</p>";
        echo "<p><strong>Tiene rol treasurer:</strong> " . ($sessionManager->hasRole('treasurer') ? 'Sí' : 'No') . "</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Error verificando permisos: " . $e->getMessage() . "</p>";
}

echo "<h2>10. Instrucciones de Emergencia:</h2>";
echo "<ol>";
echo "<li><strong>Si las URLs directas funcionan:</strong> El problema está en el JavaScript del sidebar</li>";
echo "<li><strong>Si las URLs directas NO funcionan:</strong> El problema está en el backend</li>";
echo "<li><strong>Si hay errores de base de datos:</strong> Las tablas de nómina no existen</li>";
echo "<li><strong>Si hay errores de permisos:</strong> El usuario no tiene permisos para nómina</li>";
echo "<li><strong>Si hay errores de routing:</strong> El router no reconoce 'payroll'</li>";
echo "</ol>";

echo "<h2>11. Soluciones Rápidas:</h2>";
echo "<ul>";
echo "<li><strong>Problema de JavaScript:</strong> Verificar que loadView.js se carga correctamente</li>";
echo "<li><strong>Problema de Backend:</strong> Verificar que PayrollController está mapeado correctamente</li>";
echo "<li><strong>Problema de Base de Datos:</strong> Ejecutar los scripts SQL de nómina</li>";
echo "<li><strong>Problema de Permisos:</strong> Verificar que el usuario tiene rol root, director, coordinator o treasurer</li>";
echo "</ul>";
?> 