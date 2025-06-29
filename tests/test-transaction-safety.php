<?php
/**
 * Test para verificar que las transacciones funcionan correctamente
 * y que no se crean usuarios cuando hay errores
 */

echo "<h1>🔒 Test de Seguridad de Transacciones</h1>";

require_once '../config.php';
require_once '../app/scripts/connection.php';
require_once '../app/models/UserModel.php';

$dbConn = getConnection();
$userModel = new UserModel($dbConn);

echo "<h2>1. Test 1: Intentar crear usuario con documento duplicado</h2>";

// Primero crear un usuario
$testUser1 = [
    'credential_type' => 'CC',
    'credential_number' => '99999999',
    'first_name' => 'Test',
    'last_name' => 'User1',
    'date_of_birth' => '1990-01-01',
    'email' => 'test1@example.com',
    'phone' => '3001111111',
    'address' => 'Calle Test 1',
    'password' => '123456'
];

try {
    $result = $userModel->createUser($testUser1);
    if ($result) {
        echo "<div class='alert alert-success'>✅ Usuario 1 creado exitosamente</div>";
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error creando usuario 1: " . $e->getMessage() . "</div>";
}

// Ahora intentar crear otro usuario con el mismo documento
$testUser2 = [
    'credential_type' => 'CC',
    'credential_number' => '99999999', // Mismo documento
    'first_name' => 'Test',
    'last_name' => 'User2',
    'date_of_birth' => '1990-01-01',
    'email' => 'test2@example.com', // Email diferente
    'phone' => '3002222222',
    'address' => 'Calle Test 2',
    'password' => '123456'
];

try {
    $result = $userModel->createUser($testUser2);
    if ($result) {
        echo "<div class='alert alert-danger'>❌ ERROR: Se creó un usuario duplicado</div>";
    }
} catch (Exception $e) {
    echo "<div class='alert alert-success'>✅ Correcto: Se detectó el documento duplicado</div>";
    echo "<div class='alert alert-info'>Mensaje: " . $e->getMessage() . "</div>";
}

echo "<h2>2. Test 2: Intentar crear usuario con email duplicado</h2>";

$testUser3 = [
    'credential_type' => 'CC',
    'credential_number' => '88888888', // Documento diferente
    'first_name' => 'Test',
    'last_name' => 'User3',
    'date_of_birth' => '1990-01-01',
    'email' => 'test1@example.com', // Mismo email que el primer usuario
    'phone' => '3003333333',
    'address' => 'Calle Test 3',
    'password' => '123456'
];

try {
    $result = $userModel->createUser($testUser3);
    if ($result) {
        echo "<div class='alert alert-danger'>❌ ERROR: Se creó un usuario con email duplicado</div>";
    }
} catch (Exception $e) {
    echo "<div class='alert alert-success'>✅ Correcto: Se detectó el email duplicado</div>";
    echo "<div class='alert alert-info'>Mensaje: " . $e->getMessage() . "</div>";
}

echo "<h2>3. Verificar estado de la base de datos</h2>";

// Verificar cuántos usuarios tenemos con esos documentos
try {
    $stmt = $dbConn->prepare("SELECT COUNT(*) FROM users WHERE credential_number IN ('99999999', '88888888')");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    echo "<div class='alert alert-info'>📊 Usuarios con documentos de prueba: $count</div>";
    
    if ($count == 1) {
        echo "<div class='alert alert-success'>✅ Correcto: Solo se creó 1 usuario (el primero)</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ ERROR: Se crearon $count usuarios cuando debería ser 1</div>";
    }
    
    // Mostrar los usuarios que existen
    $stmt = $dbConn->prepare("SELECT credential_number, email, first_name, last_name FROM users WHERE credential_number IN ('99999999', '88888888')");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Usuarios existentes:</h3>";
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
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error verificando base de datos: " . $e->getMessage() . "</div>";
}

echo "<h2>4. Test 3: Verificar que el rol se asignó correctamente</h2>";

try {
    $stmt = $dbConn->prepare("
        SELECT u.credential_number, u.email, ur.role_type 
        FROM users u 
        LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1
        WHERE u.credential_number = '99999999'
    ");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        if ($user['role_type']) {
            echo "<div class='alert alert-success'>✅ Usuario tiene rol asignado: " . $user['role_type'] . "</div>";
        } else {
            echo "<div class='alert alert-warning'>⚠️ Usuario no tiene rol asignado</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>❌ No se encontró el usuario</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error verificando rol: " . $e->getMessage() . "</div>";
}

echo "<h2>5. Limpieza (opcional)</h2>";
echo "<p>Para limpiar los datos de prueba, ejecuta:</p>";
echo "<code>DELETE FROM user_roles WHERE user_id IN (SELECT user_id FROM users WHERE credential_number IN ('99999999', '88888888'));</code><br>";
echo "<code>DELETE FROM users WHERE credential_number IN ('99999999', '88888888');</code>";

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
code { background-color: #f8f9fa; padding: 2px 4px; border-radius: 3px; font-family: monospace; }
</style> 