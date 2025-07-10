<?php
// Script simple para verificar el footer
echo "<h1>Prueba Simple del Footer</h1>";

// Verificar si existe el archivo dashFooter.php
$footerPath = __DIR__ . '/../app/views/layouts/dashFooter.php';
echo "<h2>Verificando archivo dashFooter.php</h2>";

if (file_exists($footerPath)) {
    echo "<p style='color: green;'>✅ dashFooter.php existe en: $footerPath</p>";
    
    // Leer el contenido del footer
    $footerContent = file_get_contents($footerPath);
    
    // Verificar si contiene el copyright
    if (strpos($footerContent, 'Byfrost &copy; 2026') !== false) {
        echo "<p style='color: green;'>✅ Footer contiene el copyright correcto</p>";
    } else {
        echo "<p style='color: red;'>❌ Footer NO contiene el copyright correcto</p>";
    }
    
    // Verificar si tiene las etiquetas de cierre
    if (strpos($footerContent, '</body>') !== false && strpos($footerContent, '</html>') !== false) {
        echo "<p style='color: green;'>✅ Footer tiene las etiquetas de cierre correctas</p>";
    } else {
        echo "<p style='color: red;'>❌ Footer NO tiene las etiquetas de cierre correctas</p>";
    }
    
    // Mostrar las primeras 200 caracteres del footer
    echo "<h3>Primeras 200 caracteres del footer:</h3>";
    echo "<pre>" . htmlspecialchars(substr($footerContent, 0, 200)) . "...</pre>";
    
} else {
    echo "<p style='color: red;'>❌ dashFooter.php NO existe en: $footerPath</p>";
}

// Verificar si existe el dashboard del director
$dashboardPath = __DIR__ . '/../app/views/director/dashboard.php';
echo "<h2>Verificando archivo dashboard.php</h2>";

if (file_exists($dashboardPath)) {
    echo "<p style='color: green;'>✅ dashboard.php existe en: $dashboardPath</p>";
    
    // Leer el final del dashboard para ver si incluye el footer
    $dashboardContent = file_get_contents($dashboardPath);
    
    // Verificar si el dashboard termina correctamente
    if (strpos($dashboardContent, '</div>') !== false) {
        echo "<p style='color: green;'>✅ Dashboard tiene etiquetas de cierre de div</p>";
    } else {
        echo "<p style='color: red;'>❌ Dashboard NO tiene etiquetas de cierre de div</p>";
    }
    
    // Mostrar las últimas 200 caracteres del dashboard
    echo "<h3>Últimas 200 caracteres del dashboard:</h3>";
    echo "<pre>" . htmlspecialchars(substr($dashboardContent, -200)) . "</pre>";
    
} else {
    echo "<p style='color: red;'>❌ dashboard.php NO existe en: $dashboardPath</p>";
}

echo "<h2>Recomendaciones</h2>";
echo "<ul>";
echo "<li>El dashboard del director debe cargarse a través del controlador DirectorDashboardController</li>";
echo "<li>El controlador debe usar el método loadDashboardView() que incluye automáticamente dashFooter.php</li>";
echo "<li>Si el footer no aparece, verifica que no hay errores de PHP que impidan la carga completa</li>";
echo "<li>Limpia el caché del navegador</li>";
echo "</ul>";

echo "<h2>URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=directorDashboard' target='_blank'>Dashboard Director</a></li>";
echo "<li><a href='http://localhost:8000/?view=director&action=dashboard' target='_blank'>Dashboard Director (alternativo)</a></li>";
echo "</ul>";
?> 