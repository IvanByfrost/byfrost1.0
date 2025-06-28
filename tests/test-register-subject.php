<?php
// Test para diagnosticar el problema con el subject en el registro
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Diagnóstico - Subject en Registro</h2>";

// 1. Verificar registerProcess.php
echo "<h3>1. Verificación de registerProcess.php</h3>";
$processFile = '../app/processes/registerProcess.php';
if (file_exists($processFile)) {
    echo "✓ registerProcess.php existe<br>";
    
    $content = file_get_contents($processFile);
    
    // Verificar que verifica el subject
    if (strpos($content, "isset(\$_POST['subject'])") !== false) {
        echo "✓ Verifica que subject existe<br>";
    } else {
        echo "✗ NO verifica que subject existe<br>";
    }
    
    if (strpos($content, "\$_POST['subject'] === 'register'") !== false) {
        echo "✓ Verifica que subject sea 'register'<br>";
    } else {
        echo "✗ NO verifica que subject sea 'register'<br>";
    }
    
} else {
    echo "✗ registerProcess.php NO existe<br>";
}

// 2. Verificar registerFunction.js
echo "<h3>2. Verificación de registerFunction.js</h3>";
$jsFile = '../app/resources/js/registerFunction.js';
if (file_exists($jsFile)) {
    echo "✓ registerFunction.js existe<br>";
    
    $content = file_get_contents($jsFile);
    
    // Verificar que envía el subject
    if (strpos($content, '"subject": "register"') !== false) {
        echo "✓ Envía subject: 'register'<br>";
    } else {
        echo "✗ NO envía subject: 'register'<br>";
    }
    
    // Verificar la URL de envío
    if (strpos($content, 'ROOT + \'app/processes/registerProcess.php\'') !== false) {
        echo "✓ Envía a la URL correcta<br>";
    } else {
        echo "✗ NO envía a la URL correcta<br>";
    }
    
} else {
    echo "✗ registerFunction.js NO existe<br>";
}

// 3. Verificar RegisterController
echo "<h3>3. Verificación de RegisterController</h3>";
$controllerFile = '../app/controllers/registerController.php';
if (file_exists($controllerFile)) {
    echo "✓ registerController.php existe<br>";
    
    $content = file_get_contents($controllerFile);
    
    // Verificar que tiene el método registerUser
    if (strpos($content, 'public function registerUser()') !== false) {
        echo "✓ Tiene método registerUser()<br>";
    } else {
        echo "✗ NO tiene método registerUser()<br>";
    }
    
} else {
    echo "✗ registerController.php NO existe<br>";
}

// 4. Simular envío de datos POST
echo "<h3>4. Simulación de Envío POST</h3>";

// Simular datos POST
$_POST = [
    'credType' => 'CC',
    'userDocument' => '12345678',
    'userEmail' => 'test@test.com',
    'userPassword' => '123456',
    'subject' => 'register'
];

echo "Datos POST simulados:<br>";
foreach ($_POST as $key => $value) {
    echo "- $key: $value<br>";
}

// Verificar que registerProcess.php puede procesar estos datos
if (file_exists($processFile)) {
    // Capturar la salida
    ob_start();
    
    try {
        // Incluir el archivo de proceso
        include $processFile;
        
        $output = ob_get_contents();
        echo "Salida del proceso: " . htmlspecialchars($output) . "<br>";
        
        // Intentar decodificar JSON
        $jsonResponse = json_decode($output, true);
        if ($jsonResponse) {
            echo "Respuesta JSON válida:<br>";
            echo "- Status: " . ($jsonResponse['status'] ?? 'no definido') . "<br>";
            echo "- Mensaje: " . ($jsonResponse['msg'] ?? 'no definido') . "<br>";
        } else {
            echo "Respuesta no es JSON válido<br>";
        }
        
    } catch (Exception $e) {
        echo "Error al procesar: " . $e->getMessage() . "<br>";
    }
    
    ob_end_clean();
}

// 5. Verificar configuración
echo "<h3>5. Verificación de Configuración</h3>";
$configFile = '../config.php';
if (file_exists($configFile)) {
    echo "✓ config.php existe<br>";
    
    // Verificar que ROOT esté definido
    if (defined('ROOT')) {
        echo "✓ ROOT está definido: " . ROOT . "<br>";
    } else {
        echo "✗ ROOT NO está definido<br>";
    }
    
} else {
    echo "✗ config.php NO existe<br>";
}

// 6. Verificar conexión a BD
echo "<h3>6. Verificación de Conexión a BD</h3>";
$connectionFile = '../app/scripts/connection.php';
if (file_exists($connectionFile)) {
    echo "✓ connection.php existe<br>";
    
    try {
        require_once $connectionFile;
        $dbConn = getConnection();
        if ($dbConn) {
            echo "✓ Conexión a BD exitosa<br>";
        } else {
            echo "✗ Conexión a BD falló<br>";
        }
    } catch (Exception $e) {
        echo "✗ Error en conexión a BD: " . $e->getMessage() . "<br>";
    }
    
} else {
    echo "✗ connection.php NO existe<br>";
}

echo "<h3>7. Recomendaciones</h3>";
echo "Si el subject no se está capturando:<br>";
echo "1. Verifica que el JavaScript esté cargado correctamente<br>";
echo "2. Verifica que no haya errores en la consola del navegador<br>";
echo "3. Verifica que la URL del proceso sea correcta<br>";
echo "4. Verifica que los datos se estén enviando en el formato correcto<br>";

echo "<br><strong>Test completado.</strong>";
?> 