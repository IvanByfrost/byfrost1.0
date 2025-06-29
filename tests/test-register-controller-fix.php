<?php
/**
 * Test específico para verificar que el RegisterController funciona correctamente
 * después de arreglar la conexión a la base de datos
 */

echo "<h1>🔧 Test de RegisterController - Conexión Arreglada</h1>";

require_once '../config.php';
require_once '../app/scripts/connection.php';
require_once '../app/controllers/RegisterController.php';

$dbConn = getConnection();

echo "<h2>1. Test de Registro Exitoso</h2>";

// Simular datos POST para registro exitoso
$_POST = [
    'credType' => 'CC',
    'userDocument' => '99999999',
    'userEmail' => 'test.success@example.com',
    'userPassword' => '123456',
    'subject' => 'register'
];

$_SERVER['REQUEST_METHOD'] = 'POST';

echo "<h3>1.1: Intentar registro exitoso</h3>";

ob_start();
$controller = new RegisterController($dbConn);
$controller->registerUser();
$output = ob_get_clean();

echo "<div class='alert alert-info'>📤 Salida del controlador:</div>";
echo "<pre>" . htmlspecialchars($output) . "</pre>";

$response = json_decode($output, true);
if ($response) {
    if ($response['status'] === 'ok') {
        echo "<div class='alert alert-success'>✅ Registro exitoso: " . $response['msg'] . "</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Error en registro: " . $response['msg'] . "</div>";
    }
} else {
    echo "<div class='alert alert-warning'>⚠️ No se pudo decodificar JSON</div>";
}

echo "<h2>2. Test de Registro con Documento Duplicado</h2>";

// Intentar registrar el mismo usuario otra vez
echo "<h3>2.1: Intentar registro con documento duplicado</h3>";

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

echo "<h2>3. Test de Registro con Email Duplicado</h2>";

// Intentar registrar con email duplicado
$_POST = [
    'credType' => 'CC',
    'userDocument' => '88888888', // Documento diferente
    'userEmail' => 'test.success@example.com', // Mismo email
    'userPassword' => '123456',
    'subject' => 'register'
];

echo "<h3>3.1: Intentar registro con email duplicado</h3>";

ob_start();
$controller->registerUser();
$output = ob_get_clean();

echo "<div class='alert alert-info'>📤 Salida del controlador (email duplicado):</div>";
echo "<pre>" . htmlspecialchars($output) . "</pre>";

$response = json_decode($output, true);
if ($response) {
    if ($response['status'] === 'error') {
        echo "<div class='alert alert-success'>✅ Correcto: Se detectó el error de email: " . $response['msg'] . "</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ ERROR: Se devolvió status 'ok' cuando debería ser 'error'</div>";
    }
} else {
    echo "<div class='alert alert-warning'>⚠️ No se pudo decodificar JSON</div>";
}

echo "<h2>4. Test del registerProcess.php Completo</h2>";

// Simular petición POST completa
$_POST = [
    'credType' => 'CC',
    'userDocument' => '77777777',
    'userEmail' => 'test.process@example.com',
    'userPassword' => '123456',
    'subject' => 'register'
];

echo "<h3>4.1: Test del proceso completo</h3>";

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

echo "<h2>5. Verificar Estado de la Base de Datos</h2>";

try {
    $stmt = $dbConn->prepare("SELECT credential_number, email, first_name, last_name FROM users WHERE credential_number IN ('99999999', '77777777') ORDER BY credential_number");
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
    
    // Verificar roles asignados
    $stmt = $dbConn->prepare("
        SELECT u.credential_number, ur.role_type 
        FROM users u 
        LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1
        WHERE u.credential_number IN ('99999999', '77777777')
        ORDER BY u.credential_number
    ");
    $stmt->execute();
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Roles asignados:</h3>";
    if (empty($roles)) {
        echo "<div class='alert alert-warning'>⚠️ No se encontraron roles asignados</div>";
    } else {
        echo "<table class='table'>";
        echo "<thead><tr><th>Documento</th><th>Rol</th></tr></thead>";
        echo "<tbody>";
        foreach ($roles as $role) {
            echo "<tr>";
            echo "<td>" . $role['credential_number'] . "</td>";
            echo "<td>" . ($role['role_type'] ?: 'Sin rol') . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error verificando BD: " . $e->getMessage() . "</div>";
}

echo "<h2>6. Instrucciones para Probar en el Navegador</h2>";
echo "<p>Ahora que hemos arreglado la conexión a la base de datos:</p>";
echo "<ol>";
echo "<li>Ve a <a href='http://localhost:8000/?view=index&action=register' target='_blank'>Registro</a></li>";
echo "<li>Intenta registrar un usuario con documento: <strong>99999999</strong></li>";
echo "<li>Deberías ver el mensaje de éxito</li>";
echo "<li>Intenta registrar otro usuario con el mismo documento</li>";
echo "<li>Deberías ver el mensaje: <em>Ya existe un usuario con ese documento.</em></li>";
echo "<li>Verifica en la consola del navegador (F12) que la respuesta JSON sea correcta</li>";
echo "</ol>";

echo "<h2>7. Limpieza (Opcional)</h2>";
echo "<p>Para limpiar los datos de prueba:</p>";
echo "<code>DELETE FROM user_roles WHERE user_id IN (SELECT user_id FROM users WHERE credential_number IN ('99999999', '77777777'));</code><br>";
echo "<code>DELETE FROM users WHERE credential_number IN ('99999999', '77777777');</code>";

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
code { background-color: #f8f9fa; padding: 2px 4px; border-radius: 3px; font-family: monospace; }
</style> 