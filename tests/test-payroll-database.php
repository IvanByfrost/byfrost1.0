<?php
// Test simple para verificar la base de datos de nómina
echo "<h1>🔍 Test Base de Datos - Nómina</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once '../config.php';
require_once ROOT . '/app/scripts/connection.php';

echo "<h2>1. Verificación de Conexión:</h2>";
try {
    $conn = getConnection();
    echo "<p>✅ Conexión a base de datos exitosa</p>";
} catch (Exception $e) {
    echo "<p>❌ Error de conexión: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>2. Verificación de Tablas:</h2>";
$tables = [
    'employees',
    'payroll_concepts', 
    'payroll_periods',
    'payroll_records',
    'payroll_concept_details',
    'employee_absences',
    'employee_overtime',
    'employee_bonuses'
];

echo "<ul>";
foreach ($tables as $table) {
    try {
        $stmt = $conn->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        $exists = $stmt->rowCount() > 0;
        
        if ($exists) {
            // Verificar registros
            $stmt = $conn->prepare("SELECT COUNT(*) FROM $table");
            $stmt->execute();
            $count = $stmt->fetchColumn();
            echo "<li>✅ Tabla <strong>$table</strong>: EXISTE ($count registros)</li>";
        } else {
            echo "<li>❌ Tabla <strong>$table</strong>: NO EXISTE</li>";
        }
    } catch (Exception $e) {
        echo "<li>❌ Error verificando tabla <strong>$table</strong>: " . $e->getMessage() . "</li>";
    }
}
echo "</ul>";

echo "<h2>3. Test del Modelo de Nómina:</h2>";
try {
    require_once ROOT . '/app/models/payrollModel.php';
    $payrollModel = new PayrollModel();
    echo "<p>✅ PayrollModel creado exitosamente</p>";
    
    // Test métodos básicos
    echo "<h3>Test de Métodos:</h3>";
    
    // Test getAllEmployees
    try {
        $employees = $payrollModel->getAllEmployees();
        echo "<p>✅ getAllEmployees(): " . count($employees) . " empleados encontrados</p>";
    } catch (Exception $e) {
        echo "<p>❌ getAllEmployees(): " . $e->getMessage() . "</p>";
    }
    
    // Test getAllPeriods
    try {
        $periods = $payrollModel->getAllPeriods();
        echo "<p>✅ getAllPeriods(): " . count($periods) . " períodos encontrados</p>";
    } catch (Exception $e) {
        echo "<p>❌ getAllPeriods(): " . $e->getMessage() . "</p>";
    }
    
    // Test getAllAbsences
    try {
        $absences = $payrollModel->getAllAbsences();
        echo "<p>✅ getAllAbsences(): " . count($absences) . " ausencias encontradas</p>";
    } catch (Exception $e) {
        echo "<p>❌ getAllAbsences(): " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error creando PayrollModel: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Test del Controlador de Nómina:</h2>";
try {
    require_once ROOT . '/app/controllers/payrollController.php';
    require_once ROOT . '/app/library/SessionManager.php';
    
    $sessionManager = new SessionManager();
    $payrollController = new PayrollController();
    echo "<p>✅ PayrollController creado exitosamente</p>";
    
    // Simular datos de sesión para testing
    if (!$sessionManager->isLoggedIn()) {
        echo "<p>⚠️ Usuario no logueado - algunos tests pueden fallar</p>";
    } else {
        echo "<p>✅ Usuario logueado</p>";
        echo "<p><strong>Rol:</strong> " . $sessionManager->getUserRole() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error creando PayrollController: " . $e->getMessage() . "</p>";
}

echo "<h2>5. Test de Vista Directa:</h2>";
$viewPath = ROOT . '/app/views/payroll/dashboard.php';
if (file_exists($viewPath)) {
    echo "<p>✅ Vista dashboard existe</p>";
    
    // Intentar cargar la vista con datos de prueba
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
            echo "<p>✅ Vista dashboard se puede cargar con datos de prueba</p>";
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

echo "<h2>6. Instrucciones:</h2>";
echo "<ol>";
echo "<li><strong>Si faltan tablas:</strong> Ejecuta <a href='execute-payroll-sql.php'>execute-payroll-sql.php</a></li>";
echo "<li><strong>Si las tablas existen pero hay errores:</strong> El problema está en el código</li>";
echo "<li><strong>Si todo funciona:</strong> El problema está en el JavaScript o routing</li>";
echo "</ol>";

echo "<h2>7. Próximos Pasos:</h2>";
echo "<ul>";
echo "<li><a href='test-payroll-urgent.php'>Test Urgente Completo</a></li>";
echo "<li><a href='test-payroll-sidebar.php'>Test JavaScript del Sidebar</a></li>";
echo "<li><a href='../?view=payroll&action=dashboard'>Probar Dashboard Directo</a></li>";
echo "</ul>";
?> 