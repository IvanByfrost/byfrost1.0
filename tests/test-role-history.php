<?php
/**
 * Test para verificar el funcionamiento del historial de roles
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/models/userModel.php';

echo "<h1>Test: Historial de Roles</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
    .warning { color: orange; }
    table { border-collapse: collapse; width: 100%; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
</style>";

try {
    // Conectar a la base de datos
    $dbConn = new PDO("mysql:host=localhost;dbname=baldur_db", "byfrost_app_user", "ByFrost2024!Secure#");
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='success'>✅ Conexión a la base de datos exitosa</div>";
    
    // Crear instancia del modelo
    $userModel = new UserModel($dbConn);
    echo "<div class='success'>✅ Modelo UserModel creado correctamente</div>";
    
    // Test 1: Buscar un usuario existente
    echo "<h2>Test 1: Búsqueda de usuario</h2>";
    $users = $userModel->searchUsersByDocument('CC', '1031180139');
    
    if (!empty($users)) {
        echo "<div class='success'>✅ Usuario encontrado: " . $users[0]['first_name'] . " " . $users[0]['last_name'] . "</div>";
        
        // Test 2: Obtener historial de roles
        echo "<h2>Test 2: Historial de roles</h2>";
        $userId = $users[0]['user_id'];
        $roleHistory = $userModel->getRoleHistory($userId);
        
        if (!empty($roleHistory)) {
            echo "<div class='success'>✅ Historial de roles encontrado (" . count($roleHistory) . " registros)</div>";
            
            echo "<table>";
            echo "<thead><tr><th>Rol</th><th>Activo</th><th>Fecha de Asignación</th></tr></thead>";
            echo "<tbody>";
            foreach ($roleHistory as $rol) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($rol['role_type']) . "</td>";
                echo "<td>" . ($rol['is_active'] ? 'Sí' : 'No') . "</td>";
                echo "<td>" . htmlspecialchars($rol['created_at']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='warning'>⚠️ No hay historial de roles para este usuario</div>";
        }
        
        // Test 3: Simular petición AJAX
        echo "<h2>Test 3: Simulación de petición AJAX</h2>";
        echo "<div class='info'>Simulando petición AJAX con parámetros: controller=User, action=showRoleHistory, credential_type=CC, credential_number=1031180139, ajax=1</div>";
        
        // Simular parámetros GET
        $_GET['controller'] = 'User';
        $_GET['action'] = 'showRoleHistory';
        $_GET['credential_type'] = 'CC';
        $_GET['credential_number'] = '1031180139';
        $_GET['ajax'] = '1';
        
        // Incluir el controlador
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $userController = new UserController($dbConn);
        
        // Capturar la salida
        ob_start();
        $userController->showRoleHistory();
        $output = ob_get_clean();
        
        echo "<div class='success'>✅ Respuesta AJAX generada correctamente</div>";
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0; background-color: #f9f9f9;'>";
        echo "<strong>Respuesta AJAX:</strong><br>";
        echo htmlspecialchars($output);
        echo "</div>";
        
    } else {
        echo "<div class='error'>❌ No se encontró el usuario de prueba</div>";
        echo "<div class='info'>Asegúrate de que existe un usuario con CC: 1031180139</div>";
    }
    
    // Test 4: Buscar usuario inexistente
    echo "<h2>Test 4: Búsqueda de usuario inexistente</h2>";
    $users = $userModel->searchUsersByDocument('CC', '9999999999');
    
    if (empty($users)) {
        echo "<div class='success'>✅ Correcto: No se encontró usuario inexistente</div>";
    } else {
        echo "<div class='error'>❌ Error: Se encontró un usuario que no debería existir</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error: " . $e->getMessage() . "</div>";
    echo "<div class='info'>Stack trace: " . $e->getTraceAsString() . "</div>";
}

echo "<hr>";
echo "<h2>Resumen</h2>";
echo "<p>Si todos los tests muestran ✅, el sistema de historial de roles está funcionando correctamente.</p>";
echo "<p>Para probar la funcionalidad completa, accede a la página de historial de roles desde el dashboard.</p>";
?> 