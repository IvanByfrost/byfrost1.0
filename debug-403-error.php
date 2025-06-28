<?php
// Script para diagnosticar el error 403
echo "<h1>🔍 Diagnóstico Error 403</h1>";

// Mostrar información de la solicitud
echo "<h2>📋 Información de la Solicitud</h2>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'No definido') . "</p>";
echo "<p><strong>QUERY_STRING:</strong> " . ($_SERVER['QUERY_STRING'] ?? 'No definido') . "</p>";
echo "<p><strong>REQUEST_METHOD:</strong> " . ($_SERVER['REQUEST_METHOD'] ?? 'No definido') . "</p>";

// Mostrar parámetros GET
echo "<h2>🔍 Parámetros GET</h2>";
if (empty($_GET)) {
    echo "<p style='color: red;'>❌ No hay parámetros GET</p>";
} else {
    echo "<ul>";
    foreach ($_GET as $key => $value) {
        echo "<li><strong>$key:</strong> " . htmlspecialchars($value) . "</li>";
    }
    echo "</ul>";
}

// Verificar si view y action están definidos
echo "<h2>🎯 Parámetros Críticos</h2>";
if (isset($_GET['view'])) {
    echo "<p style='color: green;'>✅ view = " . htmlspecialchars($_GET['view']) . "</p>";
} else {
    echo "<p style='color: red;'>❌ view no está definido</p>";
}

if (isset($_GET['action'])) {
    echo "<p style='color: green;'>✅ action = " . htmlspecialchars($_GET['action']) . "</p>";
} else {
    echo "<p style='color: red;'>❌ action no está definido</p>";
}

// Probar URLs específicas
echo "<h2>🧪 URLs de Prueba</h2>";
$testUrls = [
    'Página Principal (sin parámetros)' => 'http://localhost:8000/',
    'Login' => 'http://localhost:8000/?view=index&action=login',
    'Dashboard' => 'http://localhost:8000/?view=root&action=dashboard',
    'Asignar Roles' => 'http://localhost:8000/?view=user&action=assignRole'
];

echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 10px;'>";
foreach ($testUrls as $name => $url) {
    echo "<div style='border: 1px solid #ddd; padding: 15px; border-radius: 5px;'>";
    echo "<h3>$name</h3>";
    echo "<a href='$url' target='_blank' style='color: #007bff; text-decoration: none;'>$url</a>";
    echo "</div>";
}
echo "</div>";

// Verificar el archivo index.php
echo "<h2>📁 Verificar index.php</h2>";
if (file_exists('index.php')) {
    echo "<p style='color: green;'>✅ index.php existe</p>";
    
    // Leer las primeras líneas para verificar la configuración
    $content = file_get_contents('index.php');
    if (strpos($content, 'routerView.php') !== false) {
        echo "<p style='color: green;'>✅ index.php incluye routerView.php</p>";
    } else {
        echo "<p style='color: red;'>❌ index.php NO incluye routerView.php</p>";
    }
} else {
    echo "<p style='color: red;'>❌ index.php no existe</p>";
}

// Verificar routerView.php
echo "<h2>🛣️ Verificar routerView.php</h2>";
if (file_exists('app/scripts/routerView.php')) {
    echo "<p style='color: green;'>✅ routerView.php existe</p>";
} else {
    echo "<p style='color: red;'>❌ routerView.php no existe</p>";
}

// Verificar SecurityMiddleware
echo "<h2>🔒 Verificar SecurityMiddleware</h2>";
if (file_exists('app/library/SecurityMiddleware.php')) {
    echo "<p style='color: green;'>✅ SecurityMiddleware.php existe</p>";
} else {
    echo "<p style='color: red;'>❌ SecurityMiddleware.php no existe</p>";
}

echo "<h2>🚀 Solución Rápida</h2>";
echo "<p>Si sigues viendo el error 403, prueba estas URLs específicas:</p>";
echo "<ol>";
echo "<li><a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a></li>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles</a></li>";
echo "</ol>";

echo "<h2>🔧 Si el problema persiste</h2>";
echo "<p>El problema puede ser que el servidor no esté procesando correctamente las URLs. Prueba:</p>";
echo "<ol>";
echo "<li>Reiniciar el servidor PHP</li>";
echo "<li>Usar XAMPP en lugar del servidor PHP built-in</li>";
echo "<li>Verificar que no haya conflictos de puerto</li>";
echo "</ol>";
?> 