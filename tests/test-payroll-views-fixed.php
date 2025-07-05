<?php
// Test para verificar que todas las vistas de payroll funcionan sin sidebar
require_once '../config.php';

echo "<h2>🧪 Test: Vistas de Payroll Sin Sidebar</h2>";

echo "<h3>✅ Archivos Arreglados:</h3>";
echo "<ul>";
echo "<li>✅ <strong>createPeriod.php</strong> - Layout sin sidebar, botones con safeLoadView</li>";
echo "<li>✅ <strong>createEmployee.php</strong> - Layout sin sidebar, botones con safeLoadView</li>";
echo "</ul>";

echo "<h3>🎯 URLs de Prueba:</h3>";
echo "<div class='row'>";
echo "<div class='col-md-6'>";
echo "<h5>📋 Vistas Principales:</h5>";
echo "<ul>";
echo "<li><a href='../index.php?view=payroll' target='_blank'>Dashboard de Payroll</a></li>";
echo "<li><a href='../index.php?view=payroll&action=employees' target='_blank'>Lista de Empleados</a></li>";
echo "<li><a href='../index.php?view=payroll&action=periods' target='_blank'>Lista de Períodos</a></li>";
echo "<li><a href='../index.php?view=payroll&action=absences' target='_blank'>Ausencias</a></li>";
echo "<li><a href='../index.php?view=payroll&action=overtime' target='_blank'>Horas Extras</a></li>";
echo "<li><a href='../index.php?view=payroll&action=bonuses' target='_blank'>Bonificaciones</a></li>";
echo "<li><a href='../index.php?view=payroll&action=reports' target='_blank'>Reportes</a></li>";
echo "</ul>";
echo "</div>";

echo "<div class='col-md-6'>";
echo "<h5>➕ Vistas de Creación:</h5>";
echo "<ul>";
echo "<li><a href='../index.php?view=payroll&action=createEmployee' target='_blank'>Crear Empleado</a></li>";
echo "<li><a href='../index.php?view=payroll&action=createPeriod' target='_blank'>Crear Período</a></li>";
echo "</ul>";
echo "</div>";
echo "</div>";

echo "<h3>🔧 Cambios Realizados:</h3>";
echo "<div class='alert alert-info'>";
echo "<h6>📐 Layout Sin Sidebar:</h6>";
echo "<ul>";
echo "<li>Cambiado de <code>col-md-9 ms-sm-auto col-lg-10</code> a <code>col-12</code></li>";
echo "<li>Eliminado el sidebar completo</li>";
echo "<li>Agregado verificación de permisos al inicio</li>";
echo "</ul>";

echo "<h6>🔗 Botones con safeLoadView:</h6>";
echo "<ul>";
echo "<li>Cambiado enlaces <code>&lt;a href=...&gt;</code> a <code>&lt;button onclick='safeLoadView(...)'&gt;</code></li>";
echo "<li>Botones 'Volver' y 'Cancelar' ahora usan carga dinámica</li>";
echo "</ul>";
echo "</div>";

echo "<h3>🎯 Funcionalidades a Probar:</h3>";
echo "<ol>";
echo "<li><strong>Navegación:</strong> Ir a la lista de períodos y hacer clic en 'Nuevo Período'</li>";
echo "<li><strong>Formularios:</strong> Llenar y enviar formularios de creación</li>";
echo "<li><strong>Botones:</strong> Probar botones 'Volver' y 'Cancelar'</li>";
echo "<li><strong>Responsive:</strong> Verificar que se ve bien en móviles</li>";
echo "</ol>";

echo "<h3>📱 Ventajas del Nuevo Layout:</h3>";
echo "<ul>";
echo "<li>✅ <strong>Pantalla Completa:</strong> Más espacio para contenido</li>";
echo "<li>✅ <strong>Responsive:</strong> Mejor en dispositivos móviles</li>";
echo "<li>✅ <strong>Navegación Dinámica:</strong> Sin recargar página</li>";
echo "<li>✅ <strong>UX Mejorada:</strong> Transiciones suaves</li>";
echo "</ul>";

echo "<h3>🔍 Verificación de Archivos:</h3>";

// Verificar que los archivos existen y tienen el contenido correcto
$filesToCheck = [
    'app/views/payroll/createPeriod.php',
    'app/views/payroll/createEmployee.php'
];

foreach ($filesToCheck as $file) {
    $filePath = ROOT . '/' . $file;
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        
        // Verificar que no tiene sidebar
        if (strpos($content, 'col-md-9 ms-sm-auto col-lg-10') === false && 
            strpos($content, 'col-12') !== false) {
            echo "<p>✅ <strong>{$file}</strong> - Layout sin sidebar</p>";
        } else {
            echo "<p>❌ <strong>{$file}</strong> - Aún tiene sidebar</p>";
        }
        
        // Verificar que tiene safeLoadView
        if (strpos($content, 'safeLoadView') !== false) {
            echo "<p>✅ <strong>{$file}</strong> - Usa safeLoadView</p>";
        } else {
            echo "<p>❌ <strong>{$file}</strong> - No usa safeLoadView</p>";
        }
        
    } else {
        echo "<p>❌ <strong>{$file}</strong> - Archivo no encontrado</p>";
    }
}

echo "<h3>🎉 ¡Listo para Probar!</h3>";
echo "<p>Ahora puedes probar todas las vistas de payroll con el nuevo layout sin sidebar.</p>";
?> 