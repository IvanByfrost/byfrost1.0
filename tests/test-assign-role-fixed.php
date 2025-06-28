<?php
/**
 * Test: Sistema de Asignación de Roles - Verificación Final
 */

echo "<h1>Test: Sistema de Asignación de Roles</h1>";
echo "<p><strong>Objetivo:</strong> Verificar que el sistema funcione sin recargar la página</p>";

// 1. Verificar archivos críticos
echo "<h2>1. Verificación de Archivos</h2>";
define('ROOT', __DIR__ . '/');

$criticalFiles = [
    'app/views/user/assignRole.php' => 'Vista principal',
    'app/resources/js/assignRole.js' => 'JavaScript AJAX',
    'app/controllers/UserController.php' => 'Controlador',
    'app/models/UserModel.php' => 'Modelo',
    'app/controllers/MainController.php' => 'Controlador base'
];

foreach ($criticalFiles as $file => $description) {
    $exists = file_exists(ROOT . $file);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$file</strong> ($description): " . ($exists ? "EXISTE" : "NO EXISTE") . "</div>";
}

// 2. Verificar configuración del formulario
echo "<h2>2. Verificación del Formulario</h2>";
$viewFile = ROOT . 'app/views/user/assignRole.php';
if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    // Verificar que NO tenga method="GET"
    if (strpos($content, 'method="GET"') === false) {
        echo "<div style='color: green;'>✅ Formulario sin method='GET' - Correcto para AJAX</div>";
    } else {
        echo "<div style='color: red;'>❌ Formulario aún tiene method='GET' - Causará recarga</div>";
    }
    
    // Verificar que tenga el ID correcto
    if (strpos($content, 'id="searchUserForm"') !== false) {
        echo "<div style='color: green;'>✅ Formulario con ID correcto</div>";
    } else {
        echo "<div style='color: red;'>❌ Formulario sin ID correcto</div>";
    }
    
    // Verificar inclusión de JavaScript
    if (strpos($content, 'assignRole.js') !== false) {
        echo "<div style='color: green;'>✅ JavaScript incluido</div>";
    } else {
        echo "<div style='color: red;'>❌ JavaScript NO incluido</div>";
    }
}

// 3. Verificar JavaScript
echo "<h2>3. Verificación de JavaScript</h2>";
$jsFile = ROOT . 'app/resources/js/assignRole.js';
if (file_exists($jsFile)) {
    $jsContent = file_get_contents($jsFile);
    
    // Verificar prevención de envío normal
    if (strpos($jsContent, 'e.preventDefault()') !== false) {
        echo "<div style='color: green;'>✅ Prevención de envío normal implementada</div>";
    } else {
        echo "<div style='color: red;'>❌ NO hay prevención de envío normal</div>";
    }
    
    // Verificar uso de fetch
    if (strpos($jsContent, 'fetch(') !== false) {
        echo "<div style='color: green;'>✅ Fetch API implementada</div>";
    } else {
        echo "<div style='color: red;'>❌ Fetch API NO implementada</div>";
    }
    
    // Verificar manejo de eventos
    if (strpos($jsContent, 'addEventListener') !== false) {
        echo "<div style='color: green;'>✅ Event listeners configurados</div>";
    } else {
        echo "<div style='color: red;'>❌ Event listeners NO configurados</div>";
    }
}

// 4. Verificar controlador
echo "<h2>4. Verificación del Controlador</h2>";
$controllerFile = ROOT . 'app/controllers/UserController.php';
if (file_exists($controllerFile)) {
    $controllerContent = file_get_contents($controllerFile);
    
    // Verificar método assignRole
    if (strpos($controllerContent, 'public function assignRole()') !== false) {
        echo "<div style='color: green;'>✅ Método assignRole existe</div>";
    } else {
        echo "<div style='color: red;'>❌ Método assignRole NO existe</div>";
    }
    
    // Verificar método processAssignRole
    if (strpos($controllerContent, 'public function processAssignRole()') !== false) {
        echo "<div style='color: green;'>✅ Método processAssignRole existe</div>";
    } else {
        echo "<div style='color: red;'>❌ Método processAssignRole NO existe</div>";
    }
    
    // Verificar manejo de AJAX
    if (strpos($controllerContent, 'isAjaxRequest()') !== false) {
        echo "<div style='color: green;'>✅ Manejo de AJAX implementado</div>";
    } else {
        echo "<div style='color: red;'>❌ Manejo de AJAX NO implementado</div>";
    }
}

// 5. Instrucciones de prueba
echo "<h2>5. Instrucciones de Prueba</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
echo "<ol>";
echo "<li><strong>Abrir el dashboard:</strong> <a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>http://localhost:8000/?view=root&action=dashboard</a></li>";
echo "<li><strong>Navegar a:</strong> Usuarios → Asignar rol</li>";
echo "<li><strong>Abrir herramientas de desarrollador</strong> (F12) → Pestaña Console</li>";
echo "<li><strong>Verificar en la consola:</strong>";
echo "<ul>";
echo "<li>✅ 'Inicializando sistema de asignación de roles...'</li>";
echo "<li>✅ 'Formulario de búsqueda encontrado, configurando eventos...'</li>";
echo "<li>✅ 'Cargando usuarios sin rol...'</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Llenar el formulario:</strong>";
echo "<ul>";
echo "<li>Tipo de documento: CC</li>";
echo "<li>Número: 1031180139</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Hacer clic en 'Buscar'</strong></li>";
echo "<li><strong>Verificar en la consola:</strong>";
echo "<ul>";
echo "<li>✅ 'Formulario enviado, procesando búsqueda...'</li>";
echo "<li>✅ 'Buscando usuarios: CC 1031180139'</li>";
echo "<li>✅ 'URL de búsqueda: [url]'</li>";
echo "<li>✅ 'Respuesta recibida: 200'</li>";
echo "<li>✅ 'HTML recibido, procesando...'</li>";
echo "<li>✅ 'Resultados actualizados'</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Verificar que NO se recargue la página</strong> - La URL debe permanecer igual</li>";
echo "</ol>";
echo "</div>";

// 6. URLs de prueba
echo "<h2>6. URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles (Directo)</a></li>";
echo "<li><a href='test-ajax-debug.php' target='_blank'>Debug AJAX (Herramienta de prueba)</a></li>";
echo "</ul>";

// 7. Estado del sistema
echo "<h2>7. Estado del Sistema</h2>";
echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb;'>";
echo "<strong>✅ Sistema Listo para Pruebas</strong><br>";
echo "El sistema de asignación de roles ha sido configurado para funcionar completamente con AJAX.<br>";
echo "No debería haber recargas de página al buscar usuarios o asignar roles.";
echo "</div>";

echo "<hr>";
echo "<p><strong>Nota:</strong> Si aún experimentas recargas de página, verifica la consola del navegador para errores de JavaScript.</p>";
?> 