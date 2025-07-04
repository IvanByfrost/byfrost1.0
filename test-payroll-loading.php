<?php
// Test específico para diagnosticar problemas de carga de vistas de nómina
echo "<h1>🔍 Diagnóstico de Carga de Vistas de Nómina</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once 'config.php';

echo "<h2>1. Verificación de Archivos de Nómina:</h2>";
$payrollFiles = [
    'app/controllers/PayrollController.php',
    'app/models/PayrollModel.php',
    'app/views/payroll/dashboard.php',
    'app/views/payroll/employees.php',
    'app/views/payroll/periods.php',
    'app/views/payroll/absences.php',
    'app/views/payroll/overtime.php',
    'app/views/payroll/bonuses.php',
    'app/views/payroll/reports.php'
];

echo "<ul>";
foreach ($payrollFiles as $file) {
    $fullPath = ROOT . '/' . $file;
    $exists = file_exists($fullPath);
    $size = $exists ? filesize($fullPath) : 0;
    echo "<li><strong>$file:</strong> " . ($exists ? "✅ EXISTE ($size bytes)" : "❌ NO EXISTE") . "</li>";
}
echo "</ul>";

echo "<h2>2. Verificación del Router:</h2>";
$routerPath = ROOT . '/app/scripts/routerView.php';
if (file_exists($routerPath)) {
    $routerContent = file_get_contents($routerPath);
    $hasPayrollMapping = strpos($routerContent, "'payroll' => 'PayrollController'") !== false;
    echo "<ul>";
    echo "<li><strong>Router existe:</strong> ✅</li>";
    echo "<li><strong>Mapeo de payroll:</strong> " . ($hasPayrollMapping ? "✅" : "❌") . "</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ Router no existe</p>";
}

echo "<h2>3. Verificación de PayrollController:</h2>";
$controllerPath = ROOT . '/app/controllers/PayrollController.php';
if (file_exists($controllerPath)) {
    $controllerContent = file_get_contents($controllerPath);
    $hasDashboard = strpos($controllerContent, 'public function dashboard()') !== false;
    $hasEmployees = strpos($controllerContent, 'public function employees()') !== false;
    $hasPeriods = strpos($controllerContent, 'public function periods()') !== false;
    
    echo "<ul>";
    echo "<li><strong>Método dashboard:</strong> " . ($hasDashboard ? "✅" : "❌") . "</li>";
    echo "<li><strong>Método employees:</strong> " . ($hasEmployees ? "✅" : "❌") . "</li>";
    echo "<li><strong>Método periods:</strong> " . ($hasPeriods ? "✅" : "❌") . "</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ PayrollController no existe</p>";
}

echo "<h2>4. URLs de Prueba Directa:</h2>";
echo "<div style='font-family: Arial, sans-serif;'>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Dashboard de Nómina</h3>";
echo "<p><strong>URL:</strong> <a href='?view=payroll&action=dashboard' target='_blank'>?view=payroll&action=dashboard</a></p>";
echo "<p><strong>Debug:</strong> <a href='?view=payroll&action=dashboard&debug=1' target='_blank'>Con debug</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Empleados</h3>";
echo "<p><strong>URL:</strong> <a href='?view=payroll&action=employees' target='_blank'>?view=payroll&action=employees</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Períodos</h3>";
echo "<p><strong>URL:</strong> <a href='?view=payroll&action=periods' target='_blank'>?view=payroll&action=periods</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Ausencias</h3>";
echo "<p><strong>URL:</strong> <a href='?view=payroll&action=absences' target='_blank'>?view=payroll&action=absences</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Horas Extra</h3>";
echo "<p><strong>URL:</strong> <a href='?view=payroll&action=overtime' target='_blank'>?view=payroll&action=overtime</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Bonificaciones</h3>";
echo "<p><strong>URL:</strong> <a href='?view=payroll&action=bonuses' target='_blank'>?view=payroll&action=bonuses</a></p>";
echo "</div>";

echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>Reportes</h3>";
echo "<p><strong>URL:</strong> <a href='?view=payroll&action=reports' target='_blank'>?view=payroll&action=reports</a></p>";
echo "</div>";

echo "</div>";

echo "<h2>5. Test de Carga desde Dashboard:</h2>";
echo "<p>Si las URLs directas funcionan pero no desde el dashboard, el problema puede ser:</p>";
echo "<ul>";
echo "<li>❌ JavaScript loadView.js no funciona</li>";
echo "<li>❌ Enlaces en la barra lateral mal configurados</li>";
echo "<li>❌ Problemas de permisos de usuario</li>";
echo "<li>❌ Errores en la consola del navegador</li>";
echo "</ul>";

echo "<h2>6. Instrucciones de Diagnóstico:</h2>";
echo "<ol>";
echo "<li><strong>Prueba las URLs directas</strong> - Haz clic en cada enlace de arriba</li>";
echo "<li><strong>Si las URLs directas funcionan:</strong> El problema está en la navegación del dashboard</li>";
echo "<li><strong>Si las URLs directas NO funcionan:</strong> El problema está en el controlador o vistas</li>";
echo "<li><strong>Abre las herramientas de desarrollador</strong> (F12) y revisa la consola</li>";
echo "<li><strong>Comparte cualquier error</strong> que veas en la consola o en la página</li>";
echo "</ol>";

echo "<h2>7. Verificación de Menús:</h2>";
echo "<p>Los menús de nómina deberían aparecer en las barras laterales de:</p>";
echo "<ul>";
echo "<li>✅ Root</li>";
echo "<li>✅ Director</li>";
echo "<li>✅ Coordinator</li>";
echo "<li>✅ Treasurer</li>";
echo "</ul>";

echo "<p><strong>¿Puedes ver los menús de nómina en tu barra lateral?</strong></p>";
?> 