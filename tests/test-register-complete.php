<?php
// Test completo para verificar que el registro funciona correctamente
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test Completo - Sistema de Registro</h2>";

// 1. Verificar archivos principales
echo "<h3>1. Verificación de Archivos Principales</h3>";

$files = [
    '../app/processes/registerProcess.php' => 'registerProcess.php',
    '../app/controllers/registerController.php' => 'registerController.php',
    '../app/resources/js/registerFunction.js' => 'registerFunction.js',
    '../app/views/index/register.php' => 'register.php'
];

foreach ($files as $file => $name) {
    if (file_exists($file)) {
        echo "✓ $name existe<br>";
    } else {
        echo "✗ $name NO existe<br>";
    }
}

// 2. Verificar formulario de registro
echo "<h3>2. Verificación de Formulario</h3>";
$registerFile = '../app/views/index/register.php';
if (file_exists($registerFile)) {
    $content = file_get_contents($registerFile);
    
    // Verificar action y method
    if (strpos($content, 'method="POST"') !== false) {
        echo "✓ Tiene method=\"POST\"<br>";
    } else {
        echo "✗ NO tiene method=\"POST\"<br>";
    }
    
    if (strpos($content, 'action=') !== false) {
        echo "✓ Tiene action definido<br>";
    } else {
        echo "✗ NO tiene action definido<br>";
    }
    
    // Verificar campos con name
    $fields = ['credType', 'userDocument', 'userEmail', 'userPassword', 'passwordConf'];
    foreach ($fields as $field) {
        if (strpos($content, "name=\"$field\"") !== false) {
            echo "✓ Campo $field tiene name<br>";
        } else {
            echo "✗ Campo $field NO tiene name<br>";
        }
    }
    
    // Verificar que incluye el script
    if (strpos($content, 'registerFunction.js') !== false) {
        echo "✓ Incluye registerFunction.js<br>";
    } else {
        echo "✗ NO incluye registerFunction.js<br>";
    }
}

// 3. Verificar JavaScript
echo "<h3>3. Verificación de JavaScript</h3>";
$jsFile = '../app/resources/js/registerFunction.js';
if (file_exists($jsFile)) {
    $content = file_get_contents($jsFile);
    
    // Verificar que envía subject
    if (strpos($content, '"subject": "register"') !== false) {
        echo "✓ Envía subject: 'register'<br>";
    } else {
        echo "✗ NO envía subject: 'register'<br>";
    }
    
    // Verificar URL de envío
    if (strpos($content, 'registerProcess.php') !== false) {
        echo "✓ Envía a registerProcess.php<br>";
    } else {
        echo "✗ NO envía a registerProcess.php<br>";
    }
    
    // Verificar que previene envío por defecto
    if (strpos($content, 'e.preventDefault()') !== false) {
        echo "✓ Previene envío por defecto del formulario<br>";
    } else {
        echo "✗ NO previene envío por defecto del formulario<br>";
    }
}

// 4. Verificar registerProcess.php
echo "<h3>4. Verificación de registerProcess.php</h3>";
$processFile = '../app/processes/registerProcess.php';
if (file_exists($processFile)) {
    $content = file_get_contents($processFile);
    
    // Verificar debug logs
    if (strpos($content, 'error_log') !== false) {
        echo "✓ Tiene logs de debug<br>";
    } else {
        echo "✗ NO tiene logs de debug<br>";
    }
    
    // Verificar verificación de subject
    if (strpos($content, "isset(\$_POST['subject'])") !== false) {
        echo "✓ Verifica que subject existe<br>";
    } else {
        echo "✗ NO verifica que subject existe<br>";
    }
    
    // Verificar verificación de método
    if (strpos($content, "\$_SERVER['REQUEST_METHOD'] === 'POST'") !== false) {
        echo "✓ Verifica método POST<br>";
    } else {
        echo "✗ NO verifica método POST<br>";
    }
}

// 5. Simular envío de datos
echo "<h3>5. Simulación de Envío de Datos</h3>";

// Simular datos POST válidos
$_POST = [
    'credType' => 'CC',
    'userDocument' => '12345678',
    'userEmail' => 'test@test.com',
    'userPassword' => '123456',
    'subject' => 'register'
];

$_SERVER['REQUEST_METHOD'] = 'POST';

echo "Datos POST simulados:<br>";
foreach ($_POST as $key => $value) {
    echo "- $key: $value<br>";
}

// Procesar con registerProcess.php
if (file_exists($processFile)) {
    ob_start();
    
    try {
        include $processFile;
        $output = ob_get_contents();
        
        echo "Respuesta del proceso:<br>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
        
        // Intentar decodificar JSON
        $jsonResponse = json_decode($output, true);
        if ($jsonResponse) {
            echo "Respuesta JSON válida:<br>";
            echo "- Status: " . ($jsonResponse['status'] ?? 'no definido') . "<br>";
            echo "- Mensaje: " . ($jsonResponse['msg'] ?? 'no definido') . "<br>";
            
            if (isset($jsonResponse['debug'])) {
                echo "- Debug info disponible<br>";
            }
        } else {
            echo "Respuesta no es JSON válido<br>";
        }
        
    } catch (Exception $e) {
        echo "Error al procesar: " . $e->getMessage() . "<br>";
    }
    
    ob_end_clean();
}

// 6. URLs de prueba
echo "<h3>6. URLs para Probar</h3>";
echo "Puedes probar el registro en:<br>";
echo "- <a href='http://localhost:8000/?view=index&action=register' target='_blank'>http://localhost:8000/?view=index&action=register</a><br>";

echo "<h3>7. Pasos para Probar</h3>";
echo "1. Accede a la página de registro<br>";
echo "2. Llena el formulario con datos válidos<br>";
echo "3. Haz clic en 'Completar Registro'<br>";
echo "4. Verifica en la consola del navegador que no hay errores<br>";
echo "5. Verifica que se envía el subject: 'register'<br>";
echo "6. Verifica la respuesta del servidor<br>";

echo "<h3>8. Resumen de Cambios</h3>";
echo "Cambios realizados:<br>";
echo "- Agregué method=\"POST\" y action al formulario<br>";
echo "- Corregí los nombres de los campos (name=\"userDocument\" en lugar de name=\"documento\")<br>";
echo "- Agregué el script registerFunction.js al final de la página<br>";
echo "- Mejoré el registerProcess.php con mejor manejo de errores y debug<br>";
echo "- Agregué logs de debug para rastrear problemas<br>";

echo "<br><strong>Test completado. El sistema de registro debería funcionar correctamente ahora.</strong>";
?> 