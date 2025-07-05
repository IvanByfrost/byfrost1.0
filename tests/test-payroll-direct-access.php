<?php
// Test de acceso directo a nómina para diagnosticar problemas
echo "<h1>🔍 Test de Acceso Directo a Nómina</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once '../config.php';

echo "<h2>1. Verificación de Archivos:</h2>";
$files = [
    'app/controllers/payrollController.php',
    'app/models/payrollModel.php',
    'app/views/payroll/dashboard.php',
    'app/views/payroll/employees.php',
    'app/views/payroll/periods.php',
    'app/views/payroll/absences.php',
    'app/views/payroll/overtime.php',
    'app/views/payroll/bonuses.php',
    'app/views/payroll/reports.php'
];

echo "<ul>";
foreach ($files as $file) {
    $fullPath = ROOT . '/' . $file;
    $exists = file_exists($fullPath);
    $size = $exists ? filesize($fullPath) : 0;
    echo "<li><strong>$file:</strong> " . ($exists ? "✅ EXISTE ($size bytes)" : "❌ NO EXISTE") . "</li>";
}
echo "</ul>";

echo "<h2>2. URLs de Acceso Directo:</h2>";
echo "<div style='font-family: Arial, sans-serif;'>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Dashboard de Nómina</h3>";
echo "<p><strong>URL:</strong> <a href='../?view=payroll&action=dashboard' target='_blank'>?view=payroll&action=dashboard</a></p>";
echo "<p><strong>Debug:</strong> <a href='../?view=payroll&action=dashboard&debug=1' target='_blank'>Con debug</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Empleados</h3>";
echo "<p><strong>URL:</strong> <a href='../?view=payroll&action=employees' target='_blank'>?view=payroll&action=employees</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Períodos</h3>";
echo "<p><strong>URL:</strong> <a href='../?view=payroll&action=periods' target='_blank'>?view=payroll&action=periods</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Ausencias</h3>";
echo "<p><strong>URL:</strong> <a href='../?view=payroll&action=absences' target='_blank'>?view=payroll&action=absences</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Horas Extras</h3>";
echo "<p><strong>URL:</strong> <a href='../?view=payroll&action=overtime' target='_blank'>?view=payroll&action=overtime</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Bonificaciones</h3>";
echo "<p><strong>URL:</strong> <a href='../?view=payroll&action=bonuses' target='_blank'>?view=payroll&action=bonuses</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Reportes</h3>";
echo "<p><strong>URL:</strong> <a href='../?view=payroll&action=reports' target='_blank'>?view=payroll&action=reports</a></p>";
echo "</div>";

echo "</div>";

echo "<h2>3. Test de Controlador:</h2>";
echo "<p><a href='../?view=payroll' target='_blank'>Test Payroll sin acción (debería ir a dashboard)</a></p>";

echo "<h2>4. Test de JavaScript:</h2>";
echo "<p><a href='test-payroll-sidebar.php' target='_blank'>Test Sidebar con JavaScript</a></p>";

echo "<h2>5. Test de Debug:</h2>";
echo "<p><a href='test-payroll-debug.php' target='_blank'>Test de Debug Completo</a></p>";

echo "<h2>6. Verificación de Base de Datos:</h2>";
try {
    require_once ROOT . '/app/scripts/connection.php';
    $conn = getConnection();
    echo "<p>✅ Conexión a base de datos exitosa</p>";
    
    // Verificar tablas de nómina
    $tables = ['employees', 'payroll_periods', 'payroll_records', 'payroll_concepts'];
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

echo "<h2>7. Instrucciones de Diagnóstico:</h2>";
echo "<ol>";
echo "<li>Haz clic en las URLs de acceso directo para verificar si el backend funciona</li>";
echo "<li>Si las URLs directas funcionan, el problema está en el JavaScript del sidebar</li>";
echo "<li>Si las URLs directas no funcionan, el problema está en el backend</li>";
echo "<li>Usa el test de sidebar para verificar el JavaScript</li>";
echo "<li>Revisa la consola del navegador para errores de JavaScript</li>";
echo "</ol>";

echo "<h2>8. Posibles Soluciones:</h2>";
echo "<ul>";
echo "<li><strong>Si el backend funciona pero el sidebar no:</strong> Verificar carga de loadView.js</li>";
echo "<li><strong>Si el backend no funciona:</strong> Verificar permisos de usuario y routing</li>";
echo "<li><strong>Si hay errores de JavaScript:</strong> Verificar que BASE_URL esté configurada correctamente</li>";
echo "<li><strong>Si hay errores de base de datos:</strong> Verificar que las tablas de nómina existan</li>";
echo "</ul>";
?> 