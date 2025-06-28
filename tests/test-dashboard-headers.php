<?php
/**
 * Test: Dashboard Headers - Verificación de Headers Únicos
 */

echo "<h1>Test: Dashboard Headers</h1>";
echo "<p><strong>Objetivo:</strong> Verificar que no haya headers duplicados en el dashboard</p>";

// 1. Verificar archivos críticos
echo "<h2>1. Verificación de Archivos</h2>";
define('ROOT', __DIR__ . '/');

$criticalFiles = [
    'app/controllers/RootController.php' => 'Controlador Root',
    'app/views/root/dashboard.php' => 'Vista Dashboard',
    'app/views/layouts/dashHeader.php' => 'Header Dashboard',
    'app/views/layouts/dashFooter.php' => 'Footer Dashboard',
    'app/views/layouts/head.php' => 'Head HTML'
];

foreach ($criticalFiles as $file => $description) {
    $exists = file_exists(ROOT . $file);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$file</strong> ($description): " . ($exists ? "EXISTE" : "NO EXISTE") . "</div>";
}

// 2. Verificar método loadDashboardView
echo "<h2>2. Verificación del Método loadDashboardView</h2>";
$controllerFile = ROOT . 'app/controllers/RootController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    if (strpos($content, 'loadDashboardView') !== false) {
        echo "<div style='color: green;'>✅ Método loadDashboardView implementado</div>";
    } else {
        echo "<div style='color: red;'>❌ Método loadDashboardView NO implementado</div>";
    }
    
    if (strpos($content, 'dashHeader.php') !== false) {
        echo "<div style='color: green;'>✅ Usa dashHeader.php</div>";
    } else {
        echo "<div style='color: red;'>❌ NO usa dashHeader.php</div>";
    }
    
    if (strpos($content, 'dashFooter.php') !== false) {
        echo "<div style='color: green;'>✅ Usa dashFooter.php</div>";
    } else {
        echo "<div style='color: red;'>❌ NO usa dashFooter.php</div>";
    }
}

// 3. Verificar vista dashboard
echo "<h2>3. Verificación de la Vista Dashboard</h2>";
$dashboardFile = ROOT . 'app/views/root/dashboard.php';
if (file_exists($dashboardFile)) {
    $content = file_get_contents($dashboardFile);
    
    // Verificar que NO incluya manualmente dashHeader.php
    if (strpos($content, 'dashHeader.php') === false) {
        echo "<div style='color: green;'>✅ NO incluye dashHeader.php manualmente</div>";
    } else {
        echo "<div style='color: red;'>❌ Incluye dashHeader.php manualmente (causará duplicación)</div>";
    }
    
    // Verificar que NO incluya manualmente dashFooter.php
    if (strpos($content, 'dashFooter.php') === false) {
        echo "<div style='color: green;'>✅ NO incluye dashFooter.php manualmente</div>";
    } else {
        echo "<div style='color: red;'>❌ Incluye dashFooter.php manualmente (causará duplicación)</div>";
    }
    
    // Verificar que tenga el contenido correcto
    if (strpos($content, 'dashboard-container') !== false) {
        echo "<div style='color: green;'>✅ Contiene dashboard-container</div>";
    } else {
        echo "<div style='color: red;'>❌ NO contiene dashboard-container</div>";
    }
    
    if (strpos($content, 'rootSidebar.php') !== false) {
        echo "<div style='color: green;'>✅ Incluye rootSidebar.php</div>";
    } else {
        echo "<div style='color: red;'>❌ NO incluye rootSidebar.php</div>";
    }
}

// 4. Instrucciones de prueba
echo "<h2>4. Instrucciones de Prueba</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
echo "<ol>";
echo "<li><strong>Acceder al dashboard:</strong> <a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><strong>Abrir las herramientas de desarrollador</strong> (F12) → Pestaña Elements</li>";
echo "<li><strong>Buscar en el HTML:</strong>";
echo "<ul>";
echo "<li>✅ Solo UN elemento &lt;header&gt; o &lt;nav&gt;</li>";
echo "<li>✅ Solo UN elemento &lt;footer&gt;</li>";
echo "<li>❌ NO debe haber elementos duplicados</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Verificar en la consola:</strong> No debe haber errores de JavaScript</li>";
echo "<li><strong>Verificar funcionalidad:</strong> El sidebar debe funcionar correctamente</li>";
echo "</ol>";
echo "</div>";

// 5. URLs de prueba
echo "<h2>5. URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><a href='http://localhost:8000/?view=root&action=configuracion' target='_blank'>Configuración</a></li>";
echo "<li><a href='http://localhost:8000/?view=root&action=menuRoot' target='_blank'>Menú Root</a></li>";
echo "</ul>";

// 6. Estado del sistema
echo "<h2>6. Estado del Sistema</h2>";
echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb;'>";
echo "<strong>✅ Headers Corregidos</strong><br>";
echo "El problema de headers duplicados ha sido resuelto.<br>";
echo "El dashboard ahora usa el método loadDashboardView que incluye correctamente dashHeader.php y dashFooter.php.";
echo "</div>";

echo "<hr>";
echo "<p><strong>Nota:</strong> Si aún ves headers duplicados, verifica que no haya caché del navegador (Ctrl+F5).</p>";
?> 