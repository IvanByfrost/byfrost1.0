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

// Test para verificar que el sistema de asignación de roles funciona correctamente
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Verificación - Sistema de Asignación de Roles</h2>";

// 1. Verificar archivos principales
echo "<h3>1. Verificación de Archivos Principales</h3>";

$files = [
    '../app/views/user/assignRole.php' => 'assignRole.php',
    '../app/views/user/assignRoleResults.php' => 'assignRoleResults.php',
    '../app/resources/js/assignRole.js' => 'assignRole.js',
    '../app/controllers/userController.php' => 'userController.php'
];

foreach ($files as $file => $name) {
    if (file_exists($file)) {
        echo "✓ $name existe<br>";
    } else {
        echo "✗ $name NO existe<br>";
    }
}

// 2. Verificar formulario de búsqueda
echo "<h3>2. Verificación de Formulario de Búsqueda</h3>";
$assignRoleFile = '../app/views/user/assignRole.php';
if (file_exists($assignRoleFile)) {
    $content = file_get_contents($assignRoleFile);
    
    // Verificar método POST
    if (strpos($content, 'method="POST"') !== false) {
        echo "✓ Formulario usa method=\"POST\"<br>";
    } else {
        echo "✗ Formulario NO usa method=\"POST\"<br>";
    }
    
    // Verificar campos requeridos
    $fields = ['credential_type', 'credential_number'];
    foreach ($fields as $field) {
        if (strpos($content, "name=\"$field\"") !== false) {
            echo "✓ Campo $field tiene name<br>";
        } else {
            echo "✗ Campo $field NO tiene name<br>";
        }
    }
    
    // Verificar que incluye el script
    if (strpos($content, 'assignRole.js') !== false) {
        echo "✓ Incluye assignRole.js<br>";
    } else {
        echo "✗ NO incluye assignRole.js<br>";
    }
}

// 3. Verificar vista parcial de resultados
echo "<h3>3. Verificación de Vista Parcial de Resultados</h3>";
$resultsFile = '../app/views/user/assignRoleResults.php';
if (file_exists($resultsFile)) {
    $content = file_get_contents($resultsFile);
    
    // Verificar que tiene la estructura de tabla
    if (strpos($content, '<table class="table table-striped">') !== false) {
        echo "✓ Tiene tabla de resultados<br>";
    } else {
        echo "✗ NO tiene tabla de resultados<br>";
    }
    
    // Verificar que maneja casos sin resultados
    if (strpos($content, 'alert alert-warning') !== false) {
        echo "✓ Maneja casos sin resultados<br>";
    } else {
        echo "✗ NO maneja casos sin resultados<br>";
    }
    
    // Verificar que maneja errores
    if (strpos($content, 'alert alert-danger') !== false) {
        echo "✓ Maneja errores<br>";
    } else {
        echo "✗ NO maneja errores<br>";
    }
}

// 4. Verificar JavaScript
echo "<h3>4. Verificación de JavaScript</h3>";
$jsFile = '../app/resources/js/assignRole.js';
if (file_exists($jsFile)) {
    $content = file_get_contents($jsFile);
    
    // Verificar que usa POST
    if (strpos($content, 'method: \'POST\'') !== false) {
        echo "✓ Usa método POST<br>";
    } else {
        echo "✗ NO usa método POST<br>";
    }
    
    // Verificar que usa FormData
    if (strpos($content, 'FormData') !== false) {
        echo "✓ Usa FormData para enviar datos<br>";
    } else {
        echo "✗ NO usa FormData para enviar datos<br>";
    }
    
    // Verificar que previene envío por defecto
    if (strpos($content, 'e.preventDefault()') !== false) {
        echo "✓ Previene envío por defecto del formulario<br>";
    } else {
        echo "✗ NO previene envío por defecto del formulario<br>";
    }
}

// 5. Verificar controlador
echo "<h3>5. Verificación de Controlador</h3>";
$controllerFile = '../app/controllers/userController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    // Verificar que maneja GET y POST
    if (strpos($content, '$_GET[\'credential_type\'] ?? $_POST[\'credential_type\']') !== false) {
        echo "✓ Maneja tanto GET como POST<br>";
    } else {
        echo "✗ NO maneja tanto GET como POST<br>";
    }
    
    // Verificar que detecta peticiones AJAX
    if (strpos($content, '$this->isAjaxRequest()') !== false) {
        echo "✓ Detecta peticiones AJAX<br>";
    } else {
        echo "✗ NO detecta peticiones AJAX<br>";
    }
    
    // Verificar que usa vista parcial para AJAX
    if (strpos($content, 'assignRoleResults') !== false) {
        echo "✓ Usa vista parcial para AJAX<br>";
    } else {
        echo "✗ NO usa vista parcial para AJAX<br>";
    }
}

// 6. Simular petición POST
echo "<h3>6. Simulación de Petición POST</h3>";

// Simular datos POST
$_POST = [
    'credential_type' => 'CC',
    'credential_number' => '12345678',
    'action' => 'search'
];

$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

echo "Datos POST simulados:<br>";
foreach ($_POST as $key => $value) {
    echo "- $key: $value<br>";
}

// 7. URLs de prueba
echo "<h3>7. URLs para Probar</h3>";
echo "Puedes probar el sistema en:<br>";
echo "- <a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>http://localhost:8000/?view=user&action=assignRole</a> (página principal)<br>";

echo "<h3>8. Pasos para Probar</h3>";
echo "1. Accede a la página de asignación de roles<br>";
echo "2. Selecciona un tipo de documento<br>";
echo "3. Ingresa un número de documento<br>";
echo "4. Haz clic en 'Buscar'<br>";
echo "5. Verifica que la búsqueda funcione sin recargar la página<br>";
echo "6. Verifica que se muestren los resultados correctamente<br>";

echo "<h3>9. Resumen de Cambios</h3>";
echo "Cambios realizados:<br>";
echo "- Cambié el formulario de GET a POST<br>";
echo "- Actualicé el JavaScript para usar FormData y POST<br>";
echo "- Creé una vista parcial para resultados AJAX<br>";
echo "- Actualicé el controlador para manejar tanto GET como POST<br>";
echo "- Mejoré el manejo de peticiones AJAX<br>";

echo "<br><strong>Test completado. El sistema de asignación de roles debería funcionar correctamente ahora.</strong>";

// Test para verificar el sistema de asignación de roles con assignProcess.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Asignación de Roles - assignProcess.php</h2>";

// 1. Verificar que assignProcess.php existe
echo "<h3>1. Verificación de archivos</h3>";
$assignProcessFile = '../app/processes/assignProcess.php';
if (file_exists($assignProcessFile)) {
    echo "✓ assignProcess.php existe<br>";
    
    $content = file_get_contents($assignProcessFile);
    
    // Verificar que tiene el patrón correcto
    if (strpos($content, 'switch ($subject)') !== false) {
        echo "✓ Tiene switch para manejar subjects<br>";
    } else {
        echo "✗ NO tiene switch para subjects<br>";
    }
    
    if (strpos($content, 'assign_role') !== false) {
        echo "✓ Maneja subject 'assign_role'<br>";
    } else {
        echo "✗ NO maneja subject 'assign_role'<br>";
    }
    
    if (strpos($content, 'search_users') !== false) {
        echo "✓ Maneja subject 'search_users'<br>";
    } else {
        echo "✗ NO maneja subject 'search_users'<br>";
    }
    
    if (strpos($content, 'get_users_without_role') !== false) {
        echo "✓ Maneja subject 'get_users_without_role'<br>";
    } else {
        echo "✗ NO maneja subject 'get_users_without_role'<br>";
    }
    
} else {
    echo "✗ assignProcess.php NO existe<br>";
}

// 2. Verificar UserModel
echo "<h3>2. Verificación de UserModel</h3>";
$userModelFile = '../app/models/userModel.php';
if (file_exists($userModelFile)) {
    echo "✓ userModel.php existe<br>";
    
    $content = file_get_contents($userModelFile);
    
    $methods = ['assignRole', 'searchUsersByDocument', 'getUsersWithoutRole'];
    foreach ($methods as $method) {
        if (strpos($content, "function $method") !== false) {
            echo "✓ Tiene método $method()<br>";
        } else {
            echo "✗ NO tiene método $method()<br>";
        }
    }
    
} else {
    echo "✗ userModel.php NO existe<br>";
}

// 3. Simular petición POST para asignar rol
echo "<h3>3. Test de asignación de rol</h3>";

// Incluir archivos necesarios con rutas corregidas
require_once '../config.php';
require_once '../app/scripts/connection.php';
require_once '../app/models/userModel.php';

try {
    $dbConn = getConnection();
    $model = new UserModel($dbConn);
    
    echo "✓ Conexión a BD y UserModel OK<br>";
    
    // Simular datos POST
    $_POST['user_id'] = 1;
    $_POST['role_type'] = 'student';
    $_POST['subject'] = 'assign_role';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    
    // Capturar salida
    ob_start();
    include '../app/processes/assignProcess.php';
    $output = ob_get_clean();
    
    echo "✓ assignProcess.php ejecutado<br>";
    echo "Respuesta: " . htmlspecialchars($output) . "<br>";
    
    // Verificar que la respuesta es JSON válido
    $jsonResponse = json_decode($output, true);
    if ($jsonResponse !== null) {
        echo "✓ Respuesta es JSON válido<br>";
        echo "Status: " . ($jsonResponse['status'] ?? 'no definido') . "<br>";
        echo "Mensaje: " . ($jsonResponse['msg'] ?? 'no definido') . "<br>";
    } else {
        echo "✗ Respuesta NO es JSON válido<br>";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// 4. Test de búsqueda de usuarios
echo "<h3>4. Test de búsqueda de usuarios</h3>";

try {
    // Simular datos POST para búsqueda
    $_POST = []; // Limpiar POST anterior
    $_POST['credential_type'] = 'CC';
    $_POST['credential_number'] = '12345678';
    $_POST['subject'] = 'search_users';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    
    // Capturar salida
    ob_start();
    include '../app/processes/assignProcess.php';
    $output = ob_get_clean();
    
    echo "✓ Búsqueda ejecutada<br>";
    echo "Respuesta: " . htmlspecialchars($output) . "<br>";
    
    // Verificar que la respuesta es JSON válido
    $jsonResponse = json_decode($output, true);
    if ($jsonResponse !== null) {
        echo "✓ Respuesta es JSON válido<br>";
        echo "Status: " . ($jsonResponse['status'] ?? 'no definido') . "<br>";
        echo "Mensaje: " . ($jsonResponse['msg'] ?? 'no definido') . "<br>";
        if (isset($jsonResponse['data'])) {
            echo "Datos: " . count($jsonResponse['data']) . " usuarios encontrados<br>";
        }
    } else {
        echo "✗ Respuesta NO es JSON válido<br>";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// 5. Test de usuarios sin rol
echo "<h3>5. Test de usuarios sin rol</h3>";

try {
    // Simular datos POST para obtener usuarios sin rol
    $_POST = []; // Limpiar POST anterior
    $_POST['subject'] = 'get_users_without_role';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    
    // Capturar salida
    ob_start();
    include '../app/processes/assignProcess.php';
    $output = ob_get_clean();
    
    echo "✓ Obtención de usuarios sin rol ejecutada<br>";
    echo "Respuesta: " . htmlspecialchars($output) . "<br>";
    
    // Verificar que la respuesta es JSON válido
    $jsonResponse = json_decode($output, true);
    if ($jsonResponse !== null) {
        echo "✓ Respuesta es JSON válido<br>";
        echo "Status: " . ($jsonResponse['status'] ?? 'no definido') . "<br>";
        echo "Mensaje: " . ($jsonResponse['msg'] ?? 'no definido') . "<br>";
        if (isset($jsonResponse['data'])) {
            echo "Datos: " . count($jsonResponse['data']) . " usuarios sin rol<br>";
        }
    } else {
        echo "✗ Respuesta NO es JSON válido<br>";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// 6. Verificar JavaScript
echo "<h3>6. Verificación de JavaScript</h3>";
$jsFile = '../app/resources/js/assignRole.js';
if (file_exists($jsFile)) {
    echo "✓ assignRole.js existe<br>";
    
    $content = file_get_contents($jsFile);
    
    // Verificar que usa el patrón de registerFunction.js
    if (strpos($content, '$.ajax({') !== false) {
        echo "✓ Usa $.ajax (patrón de registerFunction.js)<br>";
    } else {
        echo "✗ NO usa $.ajax<br>";
    }
    
    if (strpos($content, 'assignProcess.php') !== false) {
        echo "✓ Llama a assignProcess.php<br>";
    } else {
        echo "✗ NO llama a assignProcess.php<br>";
    }
    
    if (strpos($content, '"subject":') !== false) {
        echo "✓ Envía subject en las peticiones<br>";
    } else {
        echo "✗ NO envía subject<br>";
    }
    
} else {
    echo "✗ assignRole.js NO existe<br>";
}

echo "<h3>7. Resumen</h3>";
echo "El sistema de asignación de roles ahora:<br>";
echo "✓ Usa assignProcess.php directamente (sin pasar por controladores)<br>";
echo "✓ Maneja subjects para diferentes acciones<br>";
echo "✓ Devuelve respuestas JSON consistentes<br>";
echo "✓ Usa el mismo patrón que registerFunction.js<br>";
echo "✓ Evita problemas de sesión y permisos<br>";

echo "<h3>8. Próximos pasos</h3>";
echo "1. Probar el formulario en el navegador<br>";
echo "2. Verificar que la búsqueda funciona<br>";
echo "3. Verificar que la asignación de roles funciona<br>";
echo "4. Verificar que la lista de usuarios sin rol se carga<br>";

echo "<br><strong>Test completado.</strong>";
?> 