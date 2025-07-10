<?php
// Diagnóstico de Sidebar y JS/CSS para el dashboard del director
require_once '../config.php';

function checkFile($path, $desc) {
    if (file_exists($path)) {
        echo "<p style='color:green;'>✅ $desc encontrado: <code>$path</code></p>";
    } else {
        echo "<p style='color:red;'>❌ $desc NO encontrado: <code>$path</code></p>";
    }
}

echo "<h1>🩺 Diagnóstico Sidebar y JS/CSS</h1>";

// 1. Verificar archivos JS y CSS
checkFile('../app/resources/js/dashboard.js', 'dashboard.js');
checkFile('../app/resources/css/dashboard-modern.css', 'dashboard-modern.css');

// 2. Verificar que el sidebar tenga el fallback de loadView
$sidebar = file_get_contents('../app/views/director/directorSidebar.php');
if (strpos($sidebar, 'typeof loadView') !== false) {
    echo "<p style='color:green;'>✅ Fallback de <code>loadView</code> presente en directorSidebar.php</p>";
} else {
    echo "<p style='color:red;'>❌ Fallback de <code>loadView</code> NO encontrado en directorSidebar.php</p>";
}

// 3. Buscar enlaces con onclick="loadView( o safeLoadView(
preg_match_all('/onclick="(loadView|safeLoadView)\((.*?)\)"/', $sidebar, $matches);
if (count($matches[0]) > 0) {
    echo "<p style='color:green;'>✅ Se encontraron ".count($matches[0])." enlaces con <code>onclick=\"loadView(...)</code> o <code>safeLoadView(...)</code></p>";
} else {
    echo "<p style='color:orange;'>⚠️ No se encontraron enlaces con <code>onclick=\"loadView(...)</code> en el sidebar</p>";
}

// 4. Mostrar los primeros 3 enlaces encontrados
if (count($matches[0]) > 0) {
    echo "<ul>";
    foreach (array_slice($matches[0], 0, 3) as $enlace) {
        echo "<li><code>".htmlspecialchars($enlace)."</code></li>";
    }
    echo "</ul>";
}

// 5. Sugerir prueba manual en navegador
$dashboardUrl = 'http://localhost:8000/?view=directorDashboard';
echo "<h2>Prueba Manual</h2>";
echo "<p>Abre <a href='$dashboardUrl' target='_blank'>$dashboardUrl</a> y revisa la consola del navegador (F12 → Consola).</p>";
echo "<p>Haz clic en un enlace del sidebar y observa si la URL cambia o si aparece algún error en la consola.</p>";

// 6. Instrucciones para depuración
?>
<h2>¿Qué hacer si sigue sin funcionar?</h2>
<ol>
    <li>Verifica que <code>dashboard.js</code> y <code>dashboard-modern.css</code> se cargan correctamente (ver arriba).</li>
    <li>Revisa la consola del navegador por errores de JavaScript.</li>
    <li>Si ves "loadView is not defined", el JS no se está cargando o el fallback no está presente.</li>
    <li>Prueba cambiando un enlace del sidebar a <code>&lt;a href='?view=directorDashboard'&gt;Inicio&lt;/a&gt;</code> para descartar problemas de JS.</li>
    <li>Si necesitas ayuda, copia aquí los errores de la consola o el resultado de este diagnóstico.</li>
</ol> 