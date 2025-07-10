<?php
// Script de prueba para verificar el dashboard del director
require_once '../config.php';

echo "<h1>🔧 Prueba del Dashboard del Director</h1>";

echo "<h2>1. Verificación de Archivos</h2>";

$files = [
    'config.php' => ROOT . '/config.php',
    'directorDashboardController.php' => ROOT . '/app/controllers/directorDashboardController.php',
    'dashboard.php' => ROOT . '/app/views/director/dashboard.php',
    'dashFooter.php' => ROOT . '/app/views/layouts/dashFooter.php',
    'dashHeader.php' => ROOT . '/app/views/layouts/dashHeader.php'
];

foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "<p style='color: green;'>✅ $name existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $name NO existe en: $path</p>";
    }
}

echo "<h2>2. Verificación de Constantes</h2>";

if (defined('ROOT')) {
    echo "<p style='color: green;'>✅ ROOT = " . ROOT . "</p>";
} else {
    echo "<p style='color: red;'>❌ ROOT NO definida</p>";
}

if (defined('url')) {
    echo "<p style='color: green;'>✅ url = " . url . "</p>";
} else {
    echo "<p style='color: red;'>❌ url NO definida</p>";
}

echo "<h2>3. URLs de Prueba</h2>";
echo "<p><strong>URL Correcta (con footer):</strong></p>";
echo "<p><a href='http://localhost:8000/?view=directorDashboard' target='_blank' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Dashboard Director (CORRECTO)</a></p>";

echo "<p><strong>URL Incorrecta (sin footer):</strong></p>";
echo "<p><a href='http://localhost:8000/?view=director&action=dashboard' target='_blank' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>⚠️ Dashboard Director (INCORRECTO)</a></p>";

echo "<h2>4. Solución al Error de Chart.js</h2>";
echo "<p>El error <code>Cannot read properties of null (reading 'getContext')</code> se debe a que:</p>";
echo "<ul>";
echo "<li>Los elementos canvas no están disponibles cuando se ejecuta el JavaScript</li>";
echo "<li>Chart.js no se ha cargado correctamente</li>";
echo "<li>El dashboard se está cargando de forma incorrecta</li>";
echo "</ul>";

echo "<h3>✅ Soluciones Aplicadas:</h3>";
echo "<ul>";
echo "<li>✅ Agregado manejo de errores en las funciones de gráficos</li>";
echo "<li>✅ Agregado verificación de que Chart.js esté disponible</li>";
echo "<li>✅ Agregado timeout para asegurar que los canvas estén listos</li>";
echo "<li>✅ Agregado logs de consola para debugging</li>";
echo "</ul>";

echo "<h2>5. Pasos para Probar</h2>";
echo "<ol>";
echo "<li>Limpia el caché del navegador (Ctrl+F5)</li>";
echo "<li>Usa la URL correcta: <code>http://localhost:8000/?view=directorDashboard</code></li>";
echo "<li>Verifica que aparece el footer con el copyright</li>";
echo "<li>Verifica que los gráficos se cargan sin errores</li>";
echo "<li>Revisa la consola del navegador para ver los logs</li>";
echo "</ol>";

echo "<h2>6. Verificación de Consola</h2>";
echo "<p>En la consola del navegador deberías ver:</p>";
echo "<ul>";
echo "<li><code>Chart.js cargado correctamente</code></li>";
echo "<li><code>DOM cargado, inicializando dashboard...</code></li>";
echo "<li><code>Creando gráficos...</code></li>";
echo "</ul>";

echo "<h2>7. Si el Problema Persiste</h2>";
echo "<p>Si aún ves errores:</p>";
echo "<ul>";
echo "<li>Verifica que Chart.js se carga desde CDN</li>";
echo "<li>Verifica que no hay conflictos con otros scripts</li>";
echo "<li>Verifica que los elementos canvas tienen los IDs correctos</li>";
echo "<li>Usa la URL correcta del dashboard</li>";
echo "</ul>";

echo "<h2>8. Código JavaScript Mejorado</h2>";
echo "<p>El código JavaScript ahora incluye:</p>";
echo "<pre>";
echo "// Verificación de Chart.js
if (typeof Chart === 'undefined') {
    console.error('Chart.js no está cargado');
} else {
    console.log('Chart.js cargado correctamente');
}

// Manejo de errores en gráficos
function createAttendanceChart() {
    const ctx = document.getElementById('attendanceChart');
    if (!ctx) {
        console.log('Canvas attendanceChart no encontrado');
        return;
    }
    
    try {
        new Chart(ctx, { /* configuración */ });
    } catch (error) {
        console.error('Error creando gráfico:', error);
    }
}

// Inicialización con timeout
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        createAttendanceChart();
        createStudentsChart();
        createPerformanceChart();
    }, 100);
});";
echo "</pre>";

echo "<h2>🎯 Resumen</h2>";
echo "<p><strong>Para que el footer funcione:</strong></p>";
echo "<ul>";
echo "<li>✅ Usa la URL: <code>http://localhost:8000/?view=directorDashboard</code></li>";
echo "<li>✅ El controlador DirectorDashboardController usa loadDashboardView()</li>";
echo "<li>✅ loadDashboardView() incluye automáticamente dashFooter.php</li>";
echo "<li>✅ Los errores de Chart.js están manejados</li>";
echo "</ul>";
?> 