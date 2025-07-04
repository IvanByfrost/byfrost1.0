<?php
// Diagnóstico URGENTE de nómina
echo "<h1>🚨 DIAGNÓSTICO URGENTE DE NÓMINA</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once 'config.php';

echo "<h2>🔍 PASO 1: Verificar archivos críticos</h2>";
$criticalFiles = [
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
foreach ($criticalFiles as $file) {
    $fullPath = ROOT . '/' . $file;
    $exists = file_exists($fullPath);
    echo "<li><strong>$file:</strong> " . ($exists ? "✅ EXISTE" : "❌ NO EXISTE") . "</li>";
}
echo "</ul>";

echo "<h2>🔍 PASO 2: Verificar router</h2>";
$routerPath = ROOT . '/app/scripts/routerView.php';
if (file_exists($routerPath)) {
    $routerContent = file_get_contents($routerPath);
    $hasPayrollMapping = strpos($routerContent, "'payroll' => 'PayrollController'") !== false;
    echo "<p><strong>Router:</strong> " . ($hasPayrollMapping ? "✅ TIENE mapeo de payroll" : "❌ NO TIENE mapeo de payroll") . "</p>";
} else {
    echo "<p><strong>Router:</strong> ❌ NO EXISTE</p>";
}

echo "<h2>🔍 PASO 3: URLs de prueba DIRECTA</h2>";
echo "<p><strong>Prueba estas URLs AHORA MISMO:</strong></p>";

echo "<div style='background: #fff3cd; padding: 15px; border: 2px solid #ffc107; margin: 10px 0;'>";
echo "<h3>🚀 TEST 1: Dashboard de Nómina</h3>";
echo "<p><a href='?view=payroll&action=dashboard' target='_blank' style='font-size: 18px; color: blue; text-decoration: underline;'>CLIC AQUÍ: ?view=payroll&action=dashboard</a></p>";
echo "<p><em>¿Qué pasa cuando haces clic? ¿Aparece algo o error?</em></p>";
echo "</div>";

echo "<div style='background: #d1ecf1; padding: 15px; border: 2px solid #17a2b8; margin: 10px 0;'>";
echo "<h3>🚀 TEST 2: Empleados</h3>";
echo "<p><a href='?view=payroll&action=employees' target='_blank' style='font-size: 18px; color: blue; text-decoration: underline;'>CLIC AQUÍ: ?view=payroll&action=employees</a></p>";
echo "<p><em>¿Qué pasa cuando haces clic? ¿Aparece algo o error?</em></p>";
echo "</div>";

echo "<div style='background: #d4edda; padding: 15px; border: 2px solid #28a745; margin: 10px 0;'>";
echo "<h3>🚀 TEST 3: Períodos</h3>";
echo "<p><a href='?view=payroll&action=periods' target='_blank' style='font-size: 18px; color: blue; text-decoration: underline;'>CLIC AQUÍ: ?view=payroll&action=periods</a></p>";
echo "<p><em>¿Qué pasa cuando haces clic? ¿Aparece algo o error?</em></p>";
echo "</div>";

echo "<h2>🔍 PASO 4: Test desde Dashboard</h2>";
echo "<p><strong>Prueba desde un dashboard:</strong></p>";

echo "<div style='background: #f8d7da; padding: 15px; border: 2px solid #dc3545; margin: 10px 0;'>";
echo "<h3>👑 Dashboard Root</h3>";
echo "<p><a href='?view=root&action=dashboard' target='_blank' style='font-size: 18px; color: blue; text-decoration: underline;'>CLIC AQUÍ: ?view=root&action=dashboard</a></p>";
echo "<p><em>1. Accede al dashboard</em></p>";
echo "<p><em>2. Busca el menú de nómina en la barra lateral</em></p>";
echo "<p><em>3. Haz clic en cualquier opción de nómina</em></p>";
echo "<p><em>4. ¿Qué pasa?</em></p>";
echo "</div>";

echo "<h2>🔍 PASO 5: Verificar PayrollController</h2>";
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
    echo "<p style='color: red; font-size: 18px;'>❌ PayrollController NO EXISTE</p>";
}

echo "<h2>🔍 PASO 6: Instrucciones CRÍTICAS</h2>";
echo "<div style='background: #ffeaa7; padding: 15px; border: 2px solid #fdcb6e; margin: 10px 0;'>";
echo "<p><strong>POR FAVOR:</strong></p>";
echo "<ol>";
echo "<li>🔄 <strong>Prueba las URLs directas</strong> de arriba</li>";
echo "<li>🔄 <strong>Dime exactamente qué pasa</strong> cuando haces clic</li>";
echo "<li>🔄 <strong>Si hay error</strong>, copia el mensaje exacto</li>";
echo "<li>🔄 <strong>Abre F12</strong> y revisa la consola</li>";
echo "<li>🔄 <strong>Comparte cualquier error</strong> que veas</li>";
echo "</ol>";
echo "</div>";

echo "<h2>🔍 PASO 7: Preguntas CRÍTICAS</h2>";
echo "<div style='background: #a29bfe; padding: 15px; border: 2px solid #6c5ce7; margin: 10px 0; color: white;'>";
echo "<p><strong>RESPONDE ESTAS PREGUNTAS:</strong></p>";
echo "<ul>";
echo "<li>❓ ¿Las URLs directas funcionan?</li>";
echo "<li>❓ ¿Las URLs desde dashboard funcionan?</li>";
echo "<li>❓ ¿Hay errores en la consola?</li>";
echo "<li>❓ ¿Qué usuario/rol estás usando?</li>";
echo "<li>❓ ¿Puedes ver los menús de nómina?</li>";
echo "</ul>";
echo "</div>";

echo "<p style='background: #00b894; padding: 15px; border: 2px solid #00a085; margin: 10px 0; color: white; font-size: 18px;'>";
echo "<strong>💡 CONSEJO:</strong> Mientras más específica sea la información, más rápido te ayudo a resolverlo.";
echo "</p>";
?> 