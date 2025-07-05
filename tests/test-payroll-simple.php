<?php
// Test simple para verificar vistas de nómina
echo "<h1>🔍 Test Simple de Vistas de Nómina</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once '../config.php';

echo "<h2>1. Verificación de Archivos:</h2>";
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

echo "<h2>2. Test de Carga Directa:</h2>";
foreach ($views as $view) {
    echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
    echo "<h3>$view</h3>";
    
    $fullPath = ROOT . "/app/views/{$view}.php";
    if (file_exists($fullPath)) {
        echo "<p>✅ Archivo existe</p>";
        
        // Intentar cargar la vista directamente
        try {
            ob_start();
            $testData = ['test' => true];
            extract($testData);
            require $fullPath;
            $content = ob_get_clean();
            
            if (!empty($content)) {
                echo "<p>✅ Vista cargada correctamente</p>";
                echo "<p><strong>Contenido (primeros 100 caracteres):</strong></p>";
                echo "<div style='background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px;'>";
                echo htmlspecialchars(substr($content, 0, 100)) . "...";
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

echo "<h2>3. Test de URLs:</h2>";
echo "<p><a href='../?view=payroll&action=dashboard' target='_blank'>Dashboard de Nómina</a></p>";
echo "<p><a href='../?view=payroll&action=employees' target='_blank'>Empleados</a></p>";
echo "<p><a href='../?view=payroll&action=periods' target='_blank'>Períodos</a></p>";
echo "<p><a href='../?view=payroll&action=absences' target='_blank'>Ausencias</a></p>";
echo "<p><a href='../?view=payroll&action=overtime' target='_blank'>Horas Extras</a></p>";
echo "<p><a href='../?view=payroll&action=bonuses' target='_blank'>Bonificaciones</a></p>";
echo "<p><a href='../?view=payroll&action=reports' target='_blank'>Reportes</a></p>";

echo "<h2>4. Test de loadPartial:</h2>";
echo "<p><a href='../?view=index&action=loadPartial&view=payroll&action=dashboard&force=1' target='_blank'>Dashboard via loadPartial</a></p>";
echo "<p><a href='../?view=index&action=loadPartial&view=payroll&action=employees&force=1' target='_blank'>Empleados via loadPartial</a></p>";

echo "<h2>5. Diagnóstico:</h2>";
echo "<ul>";
echo "<li>Si los archivos existen pero las URLs no funcionan, el problema está en el routing</li>";
echo "<li>Si las URLs funcionan pero el sidebar no, el problema está en el JavaScript</li>";
echo "<li>Si loadPartial funciona, el problema está en el método loadPartialView</li>";
echo "</ul>";
?> 