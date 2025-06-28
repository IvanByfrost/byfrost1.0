<?php
/**
 * Test para verificar la carga de JavaScript
 */

echo "<h1>Test: Carga de JavaScript</h1>";

// 1. Verificar archivos JavaScript
echo "<h2>1. Verificar archivos JavaScript:</h2>";
define('ROOT', __DIR__ . '/');

$jsFiles = [
    'app/resources/js/loadView.js' => ROOT . 'app/resources/js/loadView.js',
    'app/resources/js/assignRole.js' => ROOT . 'app/resources/js/assignRole.js'
];

foreach ($jsFiles as $name => $path) {
    $exists = file_exists($path);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$name</strong>: " . ($exists ? "EXISTE" : "NO EXISTE") . "</div>";
    
    if ($exists) {
        $content = file_get_contents($path);
        $size = strlen($content);
        echo "<div style='margin-left: 20px; color: blue;'>📄 Tamaño: " . number_format($size) . " bytes</div>";
    }
}

// 2. Verificar inclusión en la vista
echo "<h2>2. Verificar inclusión en la vista:</h2>";
$viewFile = ROOT . 'app/views/user/assignRole.php';
if (file_exists($viewFile)) {
    $viewContent = file_get_contents($viewFile);
    
    if (strpos($viewContent, 'assignRole.js') !== false) {
        echo "<div style='color: green;'>✅ assignRole.js incluido en la vista</div>";
    } else {
        echo "<div style='color: red;'>❌ assignRole.js NO incluido en la vista</div>";
    }
    
    if (strpos($viewContent, 'searchUserForm') !== false) {
        echo "<div style='color: green;'>✅ Formulario searchUserForm encontrado</div>";
    } else {
        echo "<div style='color: red;'>❌ Formulario searchUserForm NO encontrado</div>";
    }
    
    if (strpos($viewContent, 'method="GET"') !== false) {
        echo "<div style='color: orange;'>⚠️ Formulario aún tiene method='GET' - esto puede causar recarga</div>";
    } else {
        echo "<div style='color: green;'>✅ Formulario sin method='GET' - correcto para AJAX</div>";
    }
} else {
    echo "<div style='color: red;'>❌ Vista assignRole.php no encontrada</div>";
}

// 3. Verificar dashboard
echo "<h2>3. Verificar dashboard:</h2>";
$dashboardFile = ROOT . 'app/views/root/dashboard.php';
if (file_exists($dashboardFile)) {
    $dashboardContent = file_get_contents($dashboardFile);
    
    if (strpos($dashboardContent, 'loadView.js') !== false) {
        echo "<div style='color: green;'>✅ loadView.js incluido en el dashboard</div>";
    } else {
        echo "<div style='color: red;'>❌ loadView.js NO incluido en el dashboard</div>";
    }
    
    if (strpos($dashboardContent, 'BASE_URL') !== false) {
        echo "<div style='color: green;'>✅ BASE_URL definido en el dashboard</div>";
    } else {
        echo "<div style='color: red;'>❌ BASE_URL NO definido en el dashboard</div>";
    }
} else {
    echo "<div style='color: red;'>❌ Dashboard no encontrado</div>";
}

// 4. URLs de prueba
echo "<h2>4. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles (Directo)</a></li>";
echo "</ul>";

// 5. Instrucciones de debug
echo "<h2>5. Instrucciones de debug:</h2>";
echo "<ol>";
echo "<li><strong>Abrir las herramientas de desarrollador</strong> (F12)</li>";
echo "<li><strong>Ir a la pestaña Console</strong></li>";
echo "<li><strong>Acceder al dashboard:</strong> <code>http://localhost:8000/?view=root&action=dashboard</code></li>";
echo "<li><strong>Ir a:</strong> Usuarios → Asignar rol</li>";
echo "<li><strong>Verificar en la consola:</strong>";
echo "<ul>";
echo "<li>✅ 'Inicializando sistema de asignación de roles...'</li>";
echo "<li>✅ 'Formulario de búsqueda encontrado, configurando eventos...'</li>";
echo "<li>✅ 'Cargando usuarios sin rol...'</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Probar búsqueda:</strong> Llenar formulario y hacer clic en Buscar</li>";
echo "<li><strong>Verificar en la consola:</strong>";
echo "<ul>";
echo "<li>✅ 'Formulario enviado, procesando búsqueda...'</li>";
echo "<li>✅ 'Buscando usuarios: [tipo] [número]'</li>";
echo "<li>✅ 'URL de búsqueda: [url]'</li>";
echo "<li>✅ 'Respuesta recibida: 200'</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 JavaScript listo para debug</p>";
echo "<p><strong>Nota:</strong> Si ves errores en la consola, compártelos para diagnosticar el problema.</p>";
?> 