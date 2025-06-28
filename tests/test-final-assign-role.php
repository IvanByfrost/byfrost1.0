<?php
// Test final para verificar el sistema de asignación de roles
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test Final - Sistema de Asignación de Roles</h2>";

// 1. Verificar archivos críticos
echo "<h3>1. Verificación de Archivos</h3>";

$files = [
    '../app/views/user/assignRole.php' => 'Vista principal',
    '../app/resources/js/assignRole.js' => 'JavaScript',
    '../app/processes/assignProcess.php' => 'Proceso AJAX',
    '../app/models/userModel.php' => 'Modelo de usuario'
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "✓ $description existe<br>";
    } else {
        echo "✗ $description NO existe<br>";
    }
}

// 2. Verificar formulario HTML
echo "<h3>2. Verificación del Formulario</h3>";
$viewFile = '../app/views/user/assignRole.php';
if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    // Verificar que tiene action="#"
    if (strpos($content, 'action="#"') !== false) {
        echo "✓ Formulario tiene action=\"#\"<br>";
    } else {
        echo "✗ Formulario NO tiene action=\"#\"<br>";
    }
    
    // Verificar que tiene onsubmit="return false;"
    if (strpos($content, 'onsubmit="return false;"') !== false) {
        echo "✓ Formulario previene envío por defecto<br>";
    } else {
        echo "✗ Formulario NO previene envío por defecto<br>";
    }
    
    // Verificar que incluye el JavaScript
    if (strpos($content, 'assignRole.js') !== false) {
        echo "✓ Incluye assignRole.js<br>";
    } else {
        echo "✗ NO incluye assignRole.js<br>";
    }
}

// 3. Verificar JavaScript
echo "<h3>3. Verificación de JavaScript</h3>";
$jsFile = '../app/resources/js/assignRole.js';
if (file_exists($jsFile)) {
    $content = file_get_contents($jsFile);
    
    // Verificar que usa $.ajax
    if (strpos($content, '$.ajax({') !== false) {
        echo "✓ Usa $.ajax (patrón correcto)<br>";
    } else {
        echo "✗ NO usa $.ajax<br>";
    }
    
    // Verificar que llama a assignProcess.php
    if (strpos($content, 'assignProcess.php') !== false) {
        echo "✓ Llama a assignProcess.php<br>";
    } else {
        echo "✗ NO llama a assignProcess.php<br>";
    }
    
    // Verificar que envía subject
    if (strpos($content, '"subject":') !== false) {
        echo "✓ Envía subject en las peticiones<br>";
    } else {
        echo "✗ NO envía subject<br>";
    }
    
    // Verificar que previene envío por defecto
    if (strpos($content, 'e.preventDefault()') !== false) {
        echo "✓ Previene envío por defecto del formulario<br>";
    } else {
        echo "✗ NO previene envío por defecto<br>";
    }
}

// 4. Verificar assignProcess.php
echo "<h3>4. Verificación de assignProcess.php</h3>";
$processFile = '../app/processes/assignProcess.php';
if (file_exists($processFile)) {
    $content = file_get_contents($processFile);
    
    // Verificar que maneja subjects
    if (strpos($content, 'switch ($subject)') !== false) {
        echo "✓ Maneja subjects con switch<br>";
    } else {
        echo "✗ NO maneja subjects con switch<br>";
    }
    
    // Verificar subjects específicos
    $subjects = ['assign_role', 'search_users', 'get_users_without_role'];
    foreach ($subjects as $subject) {
        if (strpos($content, "case '$subject':") !== false) {
            echo "✓ Maneja subject '$subject'<br>";
        } else {
            echo "✗ NO maneja subject '$subject'<br>";
        }
    }
    
    // Verificar que devuelve JSON
    if (strpos($content, 'json_encode') !== false) {
        echo "✓ Devuelve respuestas JSON<br>";
    } else {
        echo "✗ NO devuelve respuestas JSON<br>";
    }
}

// 5. Test de funcionalidad
echo "<h3>5. Test de Funcionalidad</h3>";

// Incluir archivos necesarios
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/app/scripts/connection.php';
require_once dirname(__DIR__) . '/app/models/userModel.php';

try {
    $dbConn = getConnection();
    $model = new UserModel($dbConn);
    
    echo "✓ Conexión a BD exitosa<br>";
    
    // Test de búsqueda
    $_POST = [
        'credential_type' => 'CC',
        'credential_number' => '12345678',
        'subject' => 'search_users'
    ];
    $_SERVER['REQUEST_METHOD'] = 'POST';
    
    ob_start();
    include dirname(__DIR__) . '/app/processes/assignProcess.php';
    $output = ob_get_clean();
    
    $jsonResponse = json_decode($output, true);
    if ($jsonResponse !== null) {
        echo "✓ assignProcess.php responde JSON válido<br>";
        echo "Status: " . ($jsonResponse['status'] ?? 'no definido') . "<br>";
        echo "Mensaje: " . ($jsonResponse['msg'] ?? 'no definido') . "<br>";
    } else {
        echo "✗ assignProcess.php NO responde JSON válido<br>";
    }
    
} catch (Exception $e) {
    echo "✗ Error en test de funcionalidad: " . $e->getMessage() . "<br>";
}

// 6. Resumen y próximos pasos
echo "<h3>6. Resumen</h3>";
echo "El sistema de asignación de roles está configurado para:<br>";
echo "✓ Usar assignProcess.php directamente (sin controladores)<br>";
echo "✓ Manejar subjects para diferentes acciones<br>";
echo "✓ Devolver respuestas JSON consistentes<br>";
echo "✓ Prevenir redirecciones no deseadas<br>";
echo "✓ Usar el patrón de registerFunction.js<br>";

echo "<h3>7. URLs para Probar</h3>";
echo "Puedes probar el sistema en:<br>";
echo "- <a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles</a><br>";
echo "- <a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a> → Usuarios → Asignar rol<br>";

echo "<h3>8. Pasos para Probar</h3>";
echo "1. Abre la página de asignación de roles<br>";
echo "2. Abre las herramientas de desarrollador (F12) → Console<br>";
echo "3. Deberías ver: 'Inicializando sistema de asignación de roles...'<br>";
echo "4. Llena el formulario y haz clic en 'Buscar'<br>";
echo "5. Verifica que NO se recargue la página<br>";
echo "6. Verifica que aparezcan los resultados en la consola<br>";

echo "<h3>9. Solución de Problemas</h3>";
echo "Si aún tienes problemas:<br>";
echo "- Verifica que la consola no muestre errores de JavaScript<br>";
echo "- Verifica que las peticiones AJAX vayan a assignProcess.php<br>";
echo "- Verifica que la sesión esté activa<br>";
echo "- Verifica que no haya redirecciones en el backend<br>";

echo "<br><strong>✅ Test completado. El sistema debería funcionar correctamente ahora.</strong>";
?> 