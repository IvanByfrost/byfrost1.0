<?php
/**
 * Test completo para diagnosticar problemas de sesión, permisos, AJAX, BD y headers
 */

echo "<h1>Test: Diagnóstico Completo de Problemas</h1>";

// Incluir las dependencias necesarias
require_once '../config.php';
require_once '../app/library/SessionManager.php';
require_once '../app/scripts/connection.php';

echo "<h2>1. Diagnóstico de Sesión</h2>";

try {
    $sessionManager = new SessionManager();
    
    echo "<h3>Estado de la sesión:</h3>";
    echo "<ul>";
    echo "<li><strong>Session ID:</strong> " . session_id() . "</li>";
    echo "<li><strong>Session Status:</strong> " . (session_status() === PHP_SESSION_ACTIVE ? 'Activa' : 'Inactiva') . "</li>";
    echo "<li><strong>Session Name:</strong> " . session_name() . "</li>";
    echo "<li><strong>Session Save Path:</strong> " . session_save_path() . "</li>";
    echo "</ul>";
    
    if (!$sessionManager->isLoggedIn()) {
        echo "<div style='color: red;'>❌ No estás logueado</div>";
        echo "<p><a href='http://localhost:8000/?view=index&action=login' target='_blank'>Ir al Login</a></p>";
    } else {
        $user = $sessionManager->getCurrentUser();
        echo "<div style='color: green;'>✅ Logueado como: " . htmlspecialchars($user['email']) . "</div>";
        echo "<div><strong>Rol:</strong> " . htmlspecialchars($user['role'] ?? 'No definido') . "</div>";
        echo "<div><strong>User ID:</strong> " . htmlspecialchars($user['user_id'] ?? 'No definido') . "</div>";
        
        if (!$sessionManager->hasRole('root')) {
            echo "<div style='color: red;'>❌ No tienes rol de root</div>";
        } else {
            echo "<div style='color: green;'>✅ Tienes rol de root</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en SessionManager: " . $e->getMessage() . "</div>";
}

echo "<h2>2. Diagnóstico de Base de Datos</h2>";

try {
    $dbConn = getConnection();
    echo "<div style='color: green;'>✅ Conexión a BD exitosa</div>";
    
    // Probar consulta simple
    $stmt = $dbConn->query("SELECT COUNT(*) as total FROM users WHERE is_active = 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div>Total de usuarios activos: " . $result['total'] . "</div>";
    
    // Probar consulta de roles
    $stmt = $dbConn->query("SELECT COUNT(*) as total FROM user_roles WHERE is_active = 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div>Total de roles activos: " . $result['total'] . "</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en BD: " . $e->getMessage() . "</div>";
}

echo "<h2>3. Diagnóstico de Headers</h2>";

echo "<h3>Headers enviados:</h3>";
echo "<ul>";
echo "<li><strong>headers_sent():</strong> " . (headers_sent() ? 'SÍ' : 'NO') . "</li>";
echo "<li><strong>output_buffering:</strong> " . (ob_get_level() > 0 ? 'Activo' : 'Inactivo') . "</li>";
echo "</ul>";

if (headers_sent()) {
    echo "<div style='color: red;'>❌ Headers ya enviados - esto puede causar problemas</div>";
    echo "<div>Archivo: " . (headers_sent($file, $line) ? "$file:$line" : 'Desconocido') . "</div>";
} else {
    echo "<div style='color: green;'>✅ Headers no enviados</div>";
}

echo "<h2>4. Diagnóstico de Controlador</h2>";

try {
    require_once '../app/controllers/UserController.php';
    require_once '../app/models/UserModel.php';
    
    $userController = new UserController($dbConn);
    echo "<div style='color: green;'>✅ UserController creado correctamente</div>";
    
    // Verificar métodos disponibles
    $methods = get_class_methods($userController);
    echo "<div><strong>Métodos disponibles:</strong> " . implode(', ', $methods) . "</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en UserController: " . $e->getMessage() . "</div>";
}

echo "<h2>5. Diagnóstico de Modelo</h2>";

try {
    $userModel = new UserModel($dbConn);
    echo "<div style='color: green;'>✅ UserModel creado correctamente</div>";
    
    // Probar búsqueda
    $users = $userModel->searchUsersByDocument('CC', '1031180139');
    echo "<div>Búsqueda de prueba: " . count($users) . " usuarios encontrados</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en UserModel: " . $e->getMessage() . "</div>";
}

echo "<h2>6. Soluciones Implementadas</h2>";

echo "<h3>Problema de Sesión:</h3>";
echo "<ul>";
echo "<li>✅ Mejorado SessionManager con logging detallado</li>";
echo "<li>✅ Agregada verificación de sesión en cada petición</li>";
echo "<li>✅ Implementado manejo de sesión expirada</li>";
echo "</ul>";

echo "<h3>Problema de Permisos:</h3>";
echo "<ul>";
echo "<li>✅ Mejorado protectRoot() con verificación por pasos</li>";
echo "<li>✅ Agregado logging detallado de permisos</li>";
echo "<li>✅ Mensajes de error específicos por tipo de problema</li>";
echo "</ul>";

echo "<h3>Problema de JavaScript/AJAX:</h3>";
echo "<ul>";
echo "<li>✅ Detección automática de redirecciones</li>";
echo "<li>✅ Verificación de contenido de respuesta</li>";
echo "<li>✅ Manejo mejorado de errores</li>";
echo "<li>✅ Alertas específicas para problemas de sesión</li>";
echo "</ul>";

echo "<h3>Problema de Base de Datos:</h3>";
echo "<ul>";
echo "<li>✅ Consulta SQL corregida con todos los campos</li>";
echo "<li>✅ Manejo de errores mejorado</li>";
echo "<li>✅ Logging de consultas para debugging</li>";
echo "</ul>";

echo "<h3>Problema de Headers:</h3>";
echo "<ul>";
echo "<li>✅ Eliminados comentarios HTML de debug</li>";
echo "<li>✅ Verificación de headers antes de enviar</li>";
echo "<li>✅ Manejo de output buffering</li>";
echo "</ul>";

echo "<h2>7. Pruebas Recomendadas</h2>";

echo "<h3>1. Prueba de sesión persistente:</h3>";
echo "<ol>";
echo "<li>Ve a <a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a></li>";
echo "<li>Logueate como root</li>";
echo "<li>Ve a <a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard</a></li>";
echo "<li>Usa el menú → Usuarios → Asignar rol</li>";
echo "<li>Prueba buscar diferentes documentos</li>";
echo "</ol>";

echo "<h3>2. Prueba de AJAX:</h3>";
echo "<ol>";
echo "<li>Abre las herramientas de desarrollador (F12)</li>";
echo "<li>Ve a la pestaña Console</li>";
echo "<li>Realiza búsquedas y observa los logs</li>";
echo "<li>Verifica que no haya errores de JavaScript</li>";
echo "</ol>";

echo "<h3>3. Prueba de permisos:</h3>";
echo "<ol>";
echo "<li>Verifica que tu usuario tenga rol 'root' en la BD</li>";
echo "<li>Confirma que la sesión mantenga el rol</li>";
echo "<li>Prueba acceder sin estar logueado</li>";
echo "</ol>";

echo "<h2>8. URLs de Prueba Final</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignación de Roles</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139' target='_blank'>Búsqueda Directa</a></li>";
echo "</ul>";
?> 