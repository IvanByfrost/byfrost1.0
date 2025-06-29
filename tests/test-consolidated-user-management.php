<?php
/**
 * Test para verificar el sistema consolidado de gestión de usuarios
 */

require_once __DIR__ . '/../config.php';

echo "<h1>Test: Sistema Consolidado de Gestión de Usuarios</h1>";
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
echo "<h2>1. Verificación de archivos consolidados</h2>";

// Verificar archivos necesarios
$files = [
    'app/views/user/roleHistory.php' => 'Vista del historial de roles',
    'app/views/user/assignRole.php' => 'Vista de asignación de roles',
    'app/views/user/consultUser.php' => 'Vista de consulta de usuarios',
    'app/controllers/UserController.php' => 'Controlador de usuarios',
    'app/models/userModel.php' => 'Modelo de usuarios',
    'app/resources/js/userManagement.js' => 'JavaScript consolidado',
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
echo "<h2>2. Verificación del JavaScript consolidado</h2>";

$jsFile = ROOT . '/app/resources/js/userManagement.js';
if (file_exists($jsFile)) {
    $jsContent = file_get_contents($jsFile);
    
    $jsChecks = [
        'initializeUserManagement' => 'Función de inicialización principal',
        'handleSearchSubmit' => 'Manejador del formulario de búsqueda',
        'handleRoleHistorySubmit' => 'Manejador del formulario de historial',
        'searchRoleHistory' => 'Función de búsqueda de historial',
        'searchUsersByDocument' => 'Función de búsqueda por documento',
        'assignRole' => 'Función de asignación de roles',
        'USER_MANAGEMENT_BASE_URL' => 'Variable de URL base',
        'roleHistoryForm' => 'ID del formulario de historial',
        'searchUserForm' => 'ID del formulario de búsqueda'
    ];
    
    foreach ($jsChecks as $check => $description) {
        if (strpos($jsContent, $check) !== false) {
            echo "<div class='success'>✅ $description encontrado</div>";
        } else {
            echo "<div class='error'>❌ $description NO encontrado</div>";
        }
    }
} else {
    echo "<div class='error'>❌ Archivo JavaScript consolidado no encontrado</div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>3. Verificación de que roleHistory.js fue eliminado</h2>";

$oldJsFile = ROOT . '/app/resources/js/roleHistory.js';
if (!file_exists($oldJsFile)) {
    echo "<div class='success'>✅ Archivo roleHistory.js eliminado correctamente</div>";
} else {
    echo "<div class='error'>❌ Archivo roleHistory.js aún existe</div>";
}

// Verificar que dashFooter no incluya roleHistory.js
$footerFile = ROOT . '/app/views/layouts/dashFooter.php';
if (file_exists($footerFile)) {
    $footerContent = file_get_contents($footerFile);
    
    if (strpos($footerContent, 'roleHistory.js') === false) {
        echo "<div class='success'>✅ dashFooter.php no incluye roleHistory.js</div>";
    } else {
        echo "<div class='error'>❌ dashFooter.php aún incluye roleHistory.js</div>";
    }
    
    if (strpos($footerContent, 'userManagement.js') !== false) {
        echo "<div class='success'>✅ dashFooter.php incluye userManagement.js</div>";
    } else {
        echo "<div class='error'>❌ dashFooter.php NO incluye userManagement.js</div>";
    }
} else {
    echo "<div class='error'>❌ Archivo dashFooter.php no encontrado</div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>4. URLs de prueba</h2>";

$baseUrl = url;
echo "<div class='info'>URL base: $baseUrl</div>";

// URLs de prueba
$testUrls = [
    'Dashboard Root' => $baseUrl . 'index.php?view=root&action=dashboard',
    'Asignar Roles' => $baseUrl . 'index.php?view=user&action=assignRole',
    'Consultar Usuarios' => $baseUrl . 'index.php?view=user&action=consultUser',
    'Historial de Roles' => $baseUrl . 'index.php?view=user&action=showRoleHistory'
];

foreach ($testUrls as $description => $url) {
    echo "<div class='info'>$description: <a href='$url' target='_blank'>$url</a></div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>5. Instrucciones para probar</h2>";
echo "<ol>";
echo "<li><strong>Accede al dashboard como root:</strong> <a href='{$baseUrl}index.php?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><strong>Prueba cada funcionalidad:</strong>";
echo "<ul>";
echo "<li><strong>Asignar Roles:</strong> Busca un usuario y asígnale un rol</li>";
echo "<li><strong>Consultar Usuarios:</strong> Busca usuarios por documento o por rol</li>";
echo "<li><strong>Historial de Roles:</strong> Busca el historial de roles de un usuario</li>";
echo "</ul></li>";
echo "<li><strong>Abre la consola del navegador (F12)</strong></li>";
echo "<li><strong>Verifica que no hay errores de JavaScript</strong></li>";
echo "<li><strong>Verifica que las peticiones AJAX funcionan correctamente</strong></li>";
echo "</ol>";
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>6. Debug en consola</h2>";
echo "<p>Ejecuta estos comandos en la consola del navegador:</p>";
echo "<pre>";
echo "console.log('USER_MANAGEMENT_BASE_URL:', window.USER_MANAGEMENT_BASE_URL);\n";
echo "console.log('Formularios encontrados:');\n";
echo "console.log('- searchUserForm:', document.getElementById('searchUserForm'));\n";
echo "console.log('- roleHistoryForm:', document.getElementById('roleHistoryForm'));\n";
echo "console.log('jQuery:', typeof $ !== 'undefined');\n";
echo "console.log('Funciones disponibles:');\n";
echo "console.log('- searchRoleHistory:', typeof searchRoleHistory);\n";
echo "console.log('- searchUsersByDocument:', typeof searchUsersByDocument);\n";
echo "console.log('- assignRole:', typeof assignRole);\n";
echo "</pre>";
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>7. Beneficios de la consolidación</h2>";
echo "<ul>";
echo "<li>✅ <strong>Menos archivos:</strong> Solo un archivo JS en lugar de dos</li>";
echo "<li>✅ <strong>Menos peticiones HTTP:</strong> Una sola carga de JavaScript</li>";
echo "<li>✅ <strong>Código reutilizable:</strong> Funciones compartidas entre funcionalidades</li>";
echo "<li>✅ <strong>Mantenimiento más fácil:</strong> Todo en un lugar</li>";
echo "<li>✅ <strong>Mejor rendimiento:</strong> Menos overhead de carga</li>";
echo "</ul>";
echo "</div>";

echo "<hr>";
echo "<h2>Resumen</h2>";
echo "<p>Si todos los checks muestran ✅, el sistema consolidado está funcionando correctamente.</p>";
echo "<p>El sistema ahora maneja:</p>";
echo "<ul>";
echo "<li>🔍 <strong>Búsqueda de usuarios</strong> para asignación de roles</li>";
echo "<li>📋 <strong>Consulta de usuarios</strong> por documento o rol</li>";
echo "<li>📊 <strong>Historial de roles</strong> de usuarios</li>";
echo "<li>👤 <strong>Asignación de roles</strong> con modal</li>";
echo "<li>🔄 <strong>Gestión de usuarios sin rol</strong></li>";
echo "</ul>";
echo "<p>¡Todo consolidado en un solo archivo JavaScript!</p>";
?> 