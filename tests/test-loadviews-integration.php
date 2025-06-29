<?php
/**
 * Test para verificar la integración entre loadViews.js y userManagement.js
 */

require_once __DIR__ . '/../config.php';

echo "<h1>Test: Integración loadViews.js + userManagement.js</h1>";
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
echo "<h2>1. Verificación de archivos de integración</h2>";

// Verificar archivos necesarios
$files = [
    'app/resources/js/loadView.js' => 'loadView.js',
    'app/resources/js/userManagement.js' => 'userManagement.js',
    'app/views/layouts/dashFooter.php' => 'dashFooter.php'
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
echo "<h2>2. Verificación de loadView.js</h2>";

$loadViewFile = ROOT . '/app/resources/js/loadView.js';
if (file_exists($loadViewFile)) {
    $loadViewContent = file_get_contents($loadViewFile);
    
    $loadViewChecks = [
        'initUserManagementAfterLoad' => 'Llamada a inicialización de userManagement',
        'user/assignRole' => 'Detección de vista assignRole',
        'user/consultUser' => 'Detección de vista consultUser',
        'user/showRoleHistory' => 'Detección de vista showRoleHistory',
        'user/roleHistory' => 'Detección de vista roleHistory',
        'loadPartial' => 'Endpoint de vistas parciales'
    ];
    
    foreach ($loadViewChecks as $check => $description) {
        if (strpos($loadViewContent, $check) !== false) {
            echo "<div class='success'>✅ $description encontrado</div>";
        } else {
            echo "<div class='error'>❌ $description NO encontrado</div>";
        }
    }
} else {
    echo "<div class='error'>❌ Archivo loadView.js no encontrado</div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>3. Verificación de userManagement.js</h2>";

$userManagementFile = ROOT . '/app/resources/js/userManagement.js';
if (file_exists($userManagementFile)) {
    $userManagementContent = file_get_contents($userManagementFile);
    
    $userManagementChecks = [
        'initUserManagementAfterLoad' => 'Función de inicialización post-carga',
        'waitForDOM' => 'Función de espera de DOM',
        'isLoadViewsPage' => 'Detección de páginas con loadViews',
        'mainContent' => 'Detección del contenedor principal',
        'initializeUserManagement' => 'Función de inicialización principal'
    ];
    
    foreach ($userManagementChecks as $check => $description) {
        if (strpos($userManagementContent, $check) !== false) {
            echo "<div class='success'>✅ $description encontrado</div>";
        } else {
            echo "<div class='error'>❌ $description NO encontrado</div>";
        }
    }
} else {
    echo "<div class='error'>❌ Archivo userManagement.js no encontrado</div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>4. Verificación de dashFooter.php</h2>";

$dashFooterFile = ROOT . '/app/views/layouts/dashFooter.php';
if (file_exists($dashFooterFile)) {
    $dashFooterContent = file_get_contents($dashFooterFile);
    
    if (strpos($dashFooterContent, 'loadView.js') !== false) {
        echo "<div class='success'>✅ loadView.js incluido en dashFooter</div>";
    } else {
        echo "<div class='error'>❌ loadView.js NO incluido en dashFooter</div>";
    }
    
    if (strpos($dashFooterContent, 'userManagement.js') !== false) {
        echo "<div class='success'>✅ userManagement.js incluido en dashFooter</div>";
    } else {
        echo "<div class='error'>❌ userManagement.js NO incluido en dashFooter</div>";
    }
    
    // Verificar el orden de carga
    $loadViewPos = strpos($dashFooterContent, 'loadView.js');
    $userManagementPos = strpos($dashFooterContent, 'userManagement.js');
    
    if ($loadViewPos !== false && $userManagementPos !== false) {
        if ($loadViewPos < $userManagementPos) {
            echo "<div class='success'>✅ Orden correcto: loadView.js antes que userManagement.js</div>";
        } else {
            echo "<div class='warning'>⚠️ Orden incorrecto: userManagement.js antes que loadView.js</div>";
        }
    }
} else {
    echo "<div class='error'>❌ Archivo dashFooter.php no encontrado</div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>5. URLs de prueba para integración</h2>";

$baseUrl = url;
echo "<div class='info'>URL base: $baseUrl</div>";

// URLs de prueba
$testUrls = [
    'Dashboard con loadViews' => $baseUrl . 'index.php?view=root&action=dashboard',
    'Asignar Roles (directo)' => $baseUrl . 'index.php?view=user&action=assignRole',
    'Consultar Usuarios (directo)' => $baseUrl . 'index.php?view=user&action=consultUser',
    'Historial de Roles (directo)' => $baseUrl . 'index.php?view=user&action=showRoleHistory'
];

foreach ($testUrls as $description => $url) {
    echo "<div class='info'>$description: <a href='$url' target='_blank'>$url</a></div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>6. Instrucciones para probar la integración</h2>";
echo "<ol>";
echo "<li><strong>Accede al dashboard:</strong> <a href='{$baseUrl}index.php?view=root&action=dashboard' target='_blank'>Dashboard</a></li>";
echo "<li><strong>Abre la consola del navegador (F12)</strong></li>";
echo "<li><strong>Navega usando los enlaces del sidebar:</strong>";
echo "<ul>";
echo "<li>Asignar Roles</li>";
echo "<li>Consultar Usuarios</li>";
echo "<li>Historial de Roles</li>";
echo "</ul></li>";
echo "<li><strong>Verifica en la consola:</strong>";
echo "<ul>";
echo "<li>Mensajes de 'Cargando vista:'</li>";
echo "<li>Mensajes de 'Vista de gestión de usuarios cargada, inicializando JavaScript...'</li>";
echo "<li>Mensajes de 'Inicializando gestión de usuarios después de carga de vista...'</li>";
echo "<li>Mensajes de 'Formulario configurado correctamente'</li>";
echo "</ul></li>";
echo "<li><strong>Prueba las funcionalidades:</strong>";
echo "<ul>";
echo "<li>Completa formularios y haz búsquedas</li>";
echo "<li>Verifica que las peticiones AJAX funcionan</li>";
echo "<li>Confirma que no hay errores en la consola</li>";
echo "</ul></li>";
echo "</ol>";
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>7. Debug en consola</h2>";
echo "<p>Ejecuta estos comandos en la consola del navegador:</p>";
echo "<pre>";
echo "// Verificar que las funciones están disponibles\n";
echo "console.log('loadView disponible:', typeof loadView);\n";
echo "console.log('initUserManagementAfterLoad disponible:', typeof initUserManagementAfterLoad);\n";
echo "console.log('initializeUserManagement disponible:', typeof initializeUserManagement);\n";
echo "\n";
echo "// Verificar elementos del DOM\n";
echo "console.log('mainContent encontrado:', document.getElementById('mainContent'));\n";
echo "console.log('Formularios después de navegar:');\n";
echo "console.log('- searchUserForm:', document.getElementById('searchUserForm'));\n";
echo "console.log('- roleHistoryForm:', document.getElementById('roleHistoryForm'));\n";
echo "\n";
echo "// Simular carga de vista (si estás en el dashboard)\n";
echo "if (typeof loadView === 'function') {\n";
echo "    console.log('Probando carga de vista...');\n";
echo "    // loadView('user/assignRole'); // Descomenta para probar\n";
echo "}\n";
echo "</pre>";
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>8. Flujo de integración</h2>";
echo "<div class='info'>";
echo "<strong>1. Usuario hace clic en enlace del sidebar</strong><br>";
echo "→ loadView('user/assignRole') se ejecuta<br><br>";
echo "<strong>2. loadView.js hace petición AJAX</strong><br>";
echo "→ POST a index.php?view=index&action=loadPartial<br><br>";
echo "<strong>3. Vista se carga en mainContent</strong><br>";
echo "→ HTML de la vista se inserta en el DOM<br><br>";
echo "<strong>4. loadView.js detecta vista de gestión de usuarios</strong><br>";
echo "→ Ejecuta initUserManagementAfterLoad()<br><br>";
echo "<strong>5. userManagement.js se inicializa</strong><br>";
echo "→ Configura eventos de formularios<br>";
echo "→ Detecta tipo de página y configura funcionalidad específica<br><br>";
echo "<strong>6. Usuario puede usar la funcionalidad</strong><br>";
echo "→ Formularios funcionan con AJAX<br>";
echo "→ Búsquedas y asignaciones funcionan correctamente";
echo "</div>";
echo "</div>";

echo "<hr>";
echo "<h2>Resumen</h2>";
echo "<p>Si todos los checks muestran ✅, la integración está funcionando correctamente.</p>";
echo "<p><strong>Beneficios de la integración:</strong></p>";
echo "<ul>";
echo "<li>✅ <strong>Navegación fluida:</strong> Sin recargas de página</li>";
echo "<li>✅ <strong>JavaScript dinámico:</strong> Se inicializa automáticamente</li>";
echo "<li>✅ <strong>Experiencia de usuario mejorada:</strong> Transiciones suaves</li>";
echo "<li>✅ <strong>Código mantenible:</strong> Separación clara de responsabilidades</li>";
echo "</ul>";
echo "<p>¡Prueba la navegación y confirma que todo funciona!</p>";
?> 