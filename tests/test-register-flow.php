<?php
/**
 * Test para debuggear el flujo de registro y ver qué alertas se están mostrando
 */

echo "<h1>🔍 Test de Flujo de Registro</h1>";

require_once '../config.php';
require_once '../app/scripts/connection.php';
require_once '../app/models/UserModel.php';
require_once '../app/controllers/RegisterController.php';

$dbConn = getConnection();

echo "<h2>1. Test Directo del UserModel</h2>";

// Test 1: Intentar crear usuario con documento duplicado
$testUser = [
    'credential_type' => 'CC',
    'credential_number' => '12345678',
    'first_name' => 'Test',
    'last_name' => 'User',
    'date_of_birth' => '1990-01-01',
    'email' => 'test@example.com',
    'phone' => '3001234567',
    'address' => 'Calle Test 123',
    'password' => '123456'
];

echo "<h3>Test 1.1: Crear usuario por primera vez</h3>";
try {
    $userModel = new UserModel($dbConn);
    $result = $userModel->createUser($testUser);
    
    if ($result) {
        echo "<div class='alert alert-success'>✅ Usuario creado exitosamente</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Error: createUser devolvió false</div>";
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Excepción capturada: " . $e->getMessage() . "</div>";
}

echo "<h3>Test 1.2: Intentar crear el mismo usuario (debería fallar)</h3>";
try {
    $result = $userModel->createUser($testUser);
    
    if ($result) {
        echo "<div class='alert alert-danger'>❌ ERROR: Se creó un usuario duplicado</div>";
    } else {
        echo "<div class='alert alert-success'>✅ Correcto: createUser devolvió false</div>";
    }
} catch (Exception $e) {
    echo "<div class='alert alert-success'>✅ Correcto: Se lanzó excepción: " . $e->getMessage() . "</div>";
}

echo "<h2>2. Test del RegisterController</h2>";

// Simular datos POST
$_POST = [
    'credType' => 'CC',
    'userDocument' => '87654321',
    'userEmail' => 'test2@example.com',
    'userPassword' => '123456',
    'subject' => 'register'
];

echo "<h3>Test 2.1: Registro exitoso</h3>";

// Capturar la salida del controlador
ob_start();
$controller = new RegisterController($dbConn);
$controller->registerUser();
$output = ob_get_clean();

echo "<div class='alert alert-info'>📤 Salida del controlador:</div>";
echo "<pre>" . htmlspecialchars($output) . "</pre>";

// Decodificar JSON para verificar
$response = json_decode($output, true);
if ($response) {
    if ($response['status'] === 'ok') {
        echo "<div class='alert alert-success'>✅ Respuesta correcta: " . $response['msg'] . "</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Respuesta de error: " . $response['msg'] . "</div>";
    }
} else {
    echo "<div class='alert alert-warning'>⚠️ No se pudo decodificar JSON</div>";
}

echo "<h3>Test 2.2: Intentar registro con documento duplicado</h3>";

$_POST = [
    'credType' => 'CC',
    'userDocument' => '87654321', // Mismo documento
    'userEmail' => 'test3@example.com', // Email diferente
    'userPassword' => '123456',
    'subject' => 'register'
];

ob_start();
$controller->registerUser();
$output = ob_get_clean();

echo "<div class='alert alert-info'>📤 Salida del controlador (duplicado):</div>";
echo "<pre>" . htmlspecialchars($output) . "</pre>";

$response = json_decode($output, true);
if ($response) {
    if ($response['status'] === 'error') {
        echo "<div class='alert alert-success'>✅ Correcto: Se detectó el error: " . $response['msg'] . "</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ ERROR: Se devolvió status 'ok' cuando debería ser 'error'</div>";
    }
} else {
    echo "<div class='alert alert-warning'>⚠️ No se pudo decodificar JSON</div>";
}

echo "<h2>3. Test del registerProcess.php</h2>";

echo "<h3>Test 3.1: Simular petición POST completa</h3>";

// Simular petición POST
$_POST = [
    'credType' => 'CC',
    'userDocument' => '11111111',
    'userEmail' => 'test4@example.com',
    'userPassword' => '123456',
    'subject' => 'register'
];

$_SERVER['REQUEST_METHOD'] = 'POST';

ob_start();
require_once '../app/processes/registerProcess.php';
$output = ob_get_clean();

echo "<div class='alert alert-info'>📤 Salida del registerProcess.php:</div>";
echo "<pre>" . htmlspecialchars($output) . "</pre>";

$response = json_decode($output, true);
if ($response) {
    if ($response['status'] === 'ok') {
        echo "<div class='alert alert-success'>✅ Proceso exitoso: " . $response['msg'] . "</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Proceso falló: " . $response['msg'] . "</div>";
    }
} else {
    echo "<div class='alert alert-warning'>⚠️ No se pudo decodificar JSON del proceso</div>";
}

echo "<h2>4. Verificar estado de la base de datos</h2>";

try {
    $stmt = $dbConn->prepare("SELECT credential_number, email, first_name, last_name FROM users WHERE credential_number IN ('12345678', '87654321', '11111111') ORDER BY credential_number");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Usuarios creados durante las pruebas:</h3>";
    if (empty($users)) {
        echo "<div class='alert alert-info'>📊 No se encontraron usuarios de prueba</div>";
    } else {
        echo "<table class='table'>";
        echo "<thead><tr><th>Documento</th><th>Email</th><th>Nombre</th></tr></thead>";
        echo "<tbody>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['credential_number'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td>" . $user['first_name'] . " " . $user['last_name'] . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error verificando BD: " . $e->getMessage() . "</div>";
}

echo "<h2>5. Instrucciones para probar en el navegador</h2>";
echo "<p>Para probar el registro en el navegador:</p>";
echo "<ol>";
echo "<li>Ve a <a href='http://localhost:8000/?view=index&action=register' target='_blank'>Registro</a></li>";
echo "<li>Intenta registrar un usuario con documento: <strong>12345678</strong></li>";
echo "<li>Deberías ver el mensaje: <em>Ya existe un usuario con ese documento.</em></li>";
echo "<li>Verifica en la consola del navegador (F12) que la respuesta JSON sea correcta</li>";
echo "</ol>";

echo "<h2>6. Debug del JavaScript</h2>";
echo "<p>Para debuggear el JavaScript:</p>";
echo "<ol>";
echo "<li>Abre las herramientas de desarrollador (F12)</li>";
echo "<li>Ve a la pestaña Console</li>";
echo "<li>Intenta registrar un usuario duplicado</li>";
echo "<li>Verifica que se muestre el console.log con la respuesta</li>";
echo "<li>Verifica que el status sea 'error' y no 'ok'</li>";
echo "</ol>";

?>

<style>
.alert { padding: 15px; margin: 10px 0; border-radius: 5px; }
.alert-success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
.alert-danger { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
.alert-warning { background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
.alert-info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
.table { width: 100%; margin-bottom: 1rem; background-color: transparent; border-collapse: collapse; }
.table th, .table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; }
.table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; }
pre { background-color: #f8f9fa; padding: 10px; border-radius: 5px; border: 1px solid #dee2e6; overflow-x: auto; }
</style> 