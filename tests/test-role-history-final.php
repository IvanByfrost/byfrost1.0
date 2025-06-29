<?php
/**
 * Test final para verificar el sistema completo de historial de roles
 */

require_once __DIR__ . '/../config.php';

echo "<h1>Test Final: Sistema de Historial de Roles</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
    .warning { color: orange; }
    pre { background-color: #f5f5f5; padding: 10px; border-radius: 5px; }
    .test-section { border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 5px; }
</style>";

echo "<div class='test-section'>";
echo "<h2>1. Verificación de archivos</h2>";

// Verificar archivos necesarios
$files = [
    'app/views/user/roleHistory.php' => 'Vista del historial de roles',
    'app/controllers/UserController.php' => 'Controlador de usuarios',
    'app/models/userModel.php' => 'Modelo de usuarios',
    'app/resources/js/roleHistory.js' => 'JavaScript del historial',
    'app/views/layouts/dashFooter.php' => 'Footer del dashboard'
];

foreach ($files as $file => $description) {
    if (file_exists(ROOT . '/' . $file)) {
        echo "<div class='success'>✅ $description encontrado</div>";
    } else {
        echo "<div class='error'>❌ $description NO encontrado</div>";
    }
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>2. Verificación del JavaScript</h2>";

$jsFile = ROOT . '/app/resources/js/roleHistory.js';
if (file_exists($jsFile)) {
    $jsContent = file_get_contents($jsFile);
    
    $jsChecks = [
        'initializeRoleHistory' => 'Función de inicialización',
        'handleRoleHistorySubmit' => 'Manejador del formulario',
        'searchRoleHistory' => 'Función de búsqueda AJAX',
        'ROLE_HISTORY_BASE_URL' => 'Variable de URL base',
        'roleHistoryForm' => 'ID del formulario'
    ];
    
    foreach ($jsChecks as $check => $description) {
        if (strpos($jsContent, $check) !== false) {
            echo "<div class='success'>✅ $description encontrado</div>";
        } else {
            echo "<div class='error'>❌ $description NO encontrado</div>";
        }
    }
} else {
    echo "<div class='error'>❌ Archivo JavaScript no encontrado</div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>3. Verificación del controlador</h2>";

$controllerFile = ROOT . '/app/controllers/UserController.php';
if (file_exists($controllerFile)) {
    $controllerContent = file_get_contents($controllerFile);
    
    $controllerChecks = [
        'showRoleHistory' => 'Método showRoleHistory',
        'isAjaxRequest' => 'Detección de AJAX',
        'searchUsersByDocument' => 'Búsqueda por documento',
        'getRoleHistory' => 'Obtención de historial'
    ];
    
    foreach ($controllerChecks as $check => $description) {
        if (strpos($controllerContent, $check) !== false) {
            echo "<div class='success'>✅ $description encontrado</div>";
        } else {
            echo "<div class='error'>❌ $description NO encontrado</div>";
        }
    }
} else {
    echo "<div class='error'>❌ Controlador no encontrado</div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>4. Verificación del modelo</h2>";

$modelFile = ROOT . '/app/models/userModel.php';
if (file_exists($modelFile)) {
    $modelContent = file_get_contents($modelFile);
    
    $modelChecks = [
        'searchUsersByDocument' => 'Búsqueda por documento',
        'getRoleHistory' => 'Obtención de historial',
        'user_roles' => 'Tabla de roles'
    ];
    
    foreach ($modelChecks as $check => $description) {
        if (strpos($modelContent, $check) !== false) {
            echo "<div class='success'>✅ $description encontrado</div>";
        } else {
            echo "<div class='error'>❌ $description NO encontrado</div>";
        }
    }
} else {
    echo "<div class='error'>❌ Modelo no encontrado</div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>5. URLs de prueba</h2>";

$baseUrl = url;
echo "<div class='info'>URL base: $baseUrl</div>";

// URLs de prueba
$testUrls = [
    'Página principal' => $baseUrl . 'index.php',
    'Dashboard Root' => $baseUrl . 'index.php?view=root&action=dashboard',
    'Historial de Roles' => $baseUrl . 'index.php?view=user&action=showRoleHistory',
    'AJAX de prueba' => $baseUrl . 'index.php?controller=User&action=showRoleHistory&credential_type=CC&credential_number=1031180139&ajax=1'
];

foreach ($testUrls as $description => $url) {
    echo "<div class='info'>$description: <a href='$url' target='_blank'>$url</a></div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>6. Instrucciones para probar</h2>";
echo "<ol>";
echo "<li><strong>Accede al dashboard como root:</strong> <a href='{$baseUrl}index.php?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><strong>Ve a la sección de historial de roles</strong></li>";
echo "<li><strong>Abre la consola del navegador (F12)</strong></li>";
echo "<li><strong>Completa el formulario con:</strong>";
echo "<ul>";
echo "<li>Tipo de documento: CC</li>";
echo "<li>Número: 1031180139</li>";
echo "</ul></li>";
echo "<li><strong>Haz clic en 'Buscar'</strong></li>";
echo "<li><strong>Verifica en la consola que no hay errores</strong></li>";
echo "<li><strong>En la pestaña Network, busca la petición AJAX</strong></li>";
echo "</ol>";
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>7. Debug en consola</h2>";
echo "<p>Ejecuta estos comandos en la consola del navegador:</p>";
echo "<pre>";
echo "console.log('ROLE_HISTORY_BASE_URL:', window.ROLE_HISTORY_BASE_URL);\n";
echo "console.log('Formulario:', document.getElementById('roleHistoryForm'));\n";
echo "console.log('jQuery:', typeof $ !== 'undefined');\n";
echo "console.log('Función searchRoleHistory:', typeof searchRoleHistory);\n";
echo "</pre>";
echo "</div>";

echo "<hr>";
echo "<h2>Resumen</h2>";
echo "<p>Si todos los checks muestran ✅, el sistema está configurado correctamente.</p>";
echo "<p>El problema más probable es la URL base. Si la URL base no es correcta, modifica temporalmente en <code>config.php</code>:</p>";
echo "<pre>return 'http://localhost:8000/';</pre>";
echo "<p>¡Prueba el formulario y me dices qué pasa!</p>";
?> 