<?php
/**
 * Test para verificar que el director no puede buscar usuarios con rol "root"
 */

echo "<h1>Test: Restricción Director - Usuarios Root</h1>";

// Incluir las dependencias necesarias
require_once '../config.php';
require_once '../app/library/SessionManager.php';
require_once '../app/scripts/connection.php';
require_once '../app/models/UserModel.php';

// Obtener conexión a la base de datos
$dbConn = getConnection();

// Crear instancia del SessionManager
$sessionManager = new SessionManager();

echo "<h2>1. Estado de la Sesión</h2>";
if (!$sessionManager->isLoggedIn()) {
    echo "<div class='alert alert-warning'>⚠️ No estás logueado</div>";
    echo "<p>Ve a <a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a> primero</p>";
} else {
    $user = $sessionManager->getCurrentUser();
    echo "<div class='alert alert-success'>✅ Logueado como: " . $user['email'] . " (Rol: " . $user['role'] . ")</div>";
    
    if (!$sessionManager->hasRole('director')) {
        echo "<div class='alert alert-info'>ℹ️ Tu rol no es director, pero puedes ver cómo funciona la restricción</div>";
    } else {
        echo "<div class='alert alert-success'>✅ Tienes rol de director - se aplicarán las restricciones</div>";
    }
}

echo "<h2>2. Prueba de Restricción en Modelo</h2>";

try {
    $userModel = new UserModel($dbConn);
    $currentUserRole = $sessionManager->getUserRole();
    
    echo "<h3>2.1 Prueba getUsersByRole con rol 'root':</h3>";
    try {
        $users = $userModel->getUsersByRole('root', $currentUserRole);
        echo "<div class='alert alert-success'>✅ Se encontraron " . count($users) . " usuarios con rol root</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>❌ Error esperado: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    
    echo "<h3>2.2 Prueba searchUsersByRole con rol 'root':</h3>";
    try {
        $users = $userModel->searchUsersByRole('root', 'test', $currentUserRole);
        echo "<div class='alert alert-success'>✅ Se encontraron " . count($users) . " usuarios con rol root</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>❌ Error esperado: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    
    echo "<h3>2.3 Prueba searchUsersByRoleAndDocument con rol 'root':</h3>";
    try {
        $users = $userModel->searchUsersByRoleAndDocument('root', '123456789', $currentUserRole);
        echo "<div class='alert alert-success'>✅ Se encontraron " . count($users) . " usuarios con rol root</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>❌ Error esperado: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error en pruebas: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "<h2>3. Prueba de Proceso AJAX</h2>";

echo "<h3>3.1 Simular búsqueda por rol 'root' via AJAX:</h3>";
echo "<div id='ajaxTestResult'></div>";

echo "<script>";
echo "function testAjaxSearch() {";
echo "    $.ajax({";
echo "        type: 'POST',";
echo "        url: '../app/processes/assignProcess.php',";
echo "        dataType: 'JSON',";
echo "        data: {";
echo "            'role_type': 'root',";
echo "            'subject': 'search_users_by_role'";
echo "        },";
echo "        success: function(response) {";
echo "            if (response.status === 'ok') {";
echo "                document.getElementById('ajaxTestResult').innerHTML = '<div class=\"alert alert-success\">✅ AJAX exitoso: ' + response.msg + '</div>';";
echo "            } else {";
echo "                document.getElementById('ajaxTestResult').innerHTML = '<div class=\"alert alert-danger\">❌ AJAX error: ' + response.msg + '</div>';";
echo "            }";
echo "        },";
echo "        error: function(xhr, status, error) {";
echo "            document.getElementById('ajaxTestResult').innerHTML = '<div class=\"alert alert-danger\">❌ Error de conexión: ' + error + '</div>';";
echo "        }";
echo "    });";
echo "}";
echo "</script>";

echo "<button type='button' class='btn btn-primary' onclick='testAjaxSearch()'>Probar Búsqueda AJAX</button>";

echo "<h2>4. URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles</a></li>";
echo "<li><a href='http://localhost:8000/?view=director&action=dashboard' target='_blank'>Dashboard Director</a></li>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "</ul>";

echo "<h2>5. Instrucciones de Prueba</h2>";
echo "<ol>";
echo "<li>Ve al dashboard del director</li>";
echo "<li>Intenta buscar usuarios con rol 'Administrador'</li>";
echo "<li>Deberías recibir un mensaje de error indicando que no tienes permisos</li>";
echo "<li>Verifica que otras búsquedas (estudiantes, profesores, etc.) funcionen normalmente</li>";
echo "</ol>";

echo "<h2>6. Verificación de Código</h2>";
echo "<ul>";
echo "<li><strong>UserModel.php:</strong> " . (file_exists('../app/models/userModel.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "<li><strong>assignProcess.php:</strong> " . (file_exists('../app/processes/assignProcess.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "<li><strong>SessionManager.php:</strong> " . (file_exists('../app/library/SessionManager.php') ? '✅ Existe' : '❌ No existe') . "</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Test de restricción completado</p>";
?> 