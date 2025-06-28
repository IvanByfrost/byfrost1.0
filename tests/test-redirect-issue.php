<?php
/**
 * Test para diagnosticar el problema de redirección
 */

echo "<h1>Test: Diagnóstico de Redirección</h1>";

// Incluir las dependencias necesarias
require_once '../config.php';
require_once '../app/library/SessionManager.php';
require_once '../app/scripts/connection.php';

echo "<h2>1. Verificación de Sesión:</h2>";

try {
    $sessionManager = new SessionManager();
    
    if (!$sessionManager->isLoggedIn()) {
        echo "<div style='color: red;'>❌ No estás logueado</div>";
        echo "<p>Este es el problema principal. Ve a <a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a> primero.</p>";
    } else {
        $user = $sessionManager->getCurrentUser();
        echo "<div style='color: green;'>✅ Logueado como: " . htmlspecialchars($user['email']) . "</div>";
        
        if (!$sessionManager->hasRole('root')) {
            echo "<div style='color: red;'>❌ Tu rol no tiene permisos para asignar roles</div>";
            echo "<p>Necesitas rol de <strong>root</strong> para acceder a esta funcionalidad</p>";
        } else {
            echo "<div style='color: green;'>✅ Tienes permisos de root</div>";
        }
    }
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en SessionManager: " . $e->getMessage() . "</div>";
}

echo "<h2>2. Pruebas de Búsqueda:</h2>";

// Documentos de prueba
$testDocuments = [
    ['type' => 'CC', 'number' => '1031180139', 'description' => 'Documento que funciona'],
    ['type' => 'CC', 'number' => '1234567890', 'description' => 'Documento que redirige'],
    ['type' => 'TI', 'number' => '987654321', 'description' => 'Tarjeta de identidad'],
    ['type' => 'CE', 'number' => '1111111111', 'description' => 'Cédula de extranjería']
];

foreach ($testDocuments as $doc) {
    $url = "http://localhost:8000/?view=user&action=assignRole&credential_type={$doc['type']}&credential_number={$doc['number']}";
    echo "<div>";
    echo "<strong>{$doc['description']}:</strong> ";
    echo "<a href='$url' target='_blank'>$url</a>";
    echo "</div>";
}

echo "<h2>3. Verificación del Controlador:</h2>";

try {
    require_once '../app/controllers/UserController.php';
    require_once '../app/models/UserModel.php';
    
    $dbConn = getConnection();
    $userController = new UserController($dbConn);
    
    echo "<div style='color: green;'>✅ UserController creado correctamente</div>";
    
    // Simular parámetros GET
    $_GET['credential_type'] = 'CC';
    $_GET['credential_number'] = '1031180139';
    
    echo "<div>Parámetros simulados: " . print_r($_GET, true) . "</div>";
    
    // Verificar si la condición de búsqueda se cumple
    if (isset($_GET['credential_type']) && isset($_GET['credential_number']) && !empty($_GET['credential_number'])) {
        echo "<div style='color: green;'>✅ Condición de búsqueda cumplida</div>";
    } else {
        echo "<div style='color: red;'>❌ Condición de búsqueda NO cumplida</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en UserController: " . $e->getMessage() . "</div>";
}

echo "<h2>4. Verificación del Modelo:</h2>";

try {
    $userModel = new UserModel($dbConn);
    
    // Probar búsqueda con documento que funciona
    $users = $userModel->searchUsersByDocument('CC', '1031180139');
    echo "<div style='color: green;'>✅ Búsqueda con CC 1031180139: " . count($users) . " usuarios encontrados</div>";
    
    // Probar búsqueda con documento que redirige
    $users = $userModel->searchUsersByDocument('CC', '1234567890');
    echo "<div style='color: green;'>✅ Búsqueda con CC 1234567890: " . count($users) . " usuarios encontrados</div>";
    
    if (empty($users)) {
        echo "<div style='color: orange;'>⚠️ No se encontraron usuarios con CC 1234567890 (esto es normal)</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error en UserModel: " . $e->getMessage() . "</div>";
}

echo "<h2>5. Análisis del Problema:</h2>";

echo "<h3>Posibles causas de redirección:</h3>";
echo "<ul>";
echo "<li><strong>Problema de sesión:</strong> La sesión se pierde entre búsquedas</li>";
echo "<li><strong>Problema de permisos:</strong> El rol no se mantiene</li>";
echo "<li><strong>Problema de JavaScript:</strong> El AJAX no funciona correctamente</li>";
echo "<li><strong>Problema de base de datos:</strong> Error en la consulta</li>";
echo "<li><strong>Problema de headers:</strong> Headers ya enviados</li>";
echo "</ul>";

echo "<h2>6. Soluciones Recomendadas:</h2>";

echo "<h3>1. Verificar sesión:</h3>";
echo "<ul>";
echo "<li>Asegúrate de estar logueado como root</li>";
echo "<li>Verifica que la sesión no expire</li>";
echo "<li>Revisa los logs de error del servidor</li>";
echo "</ul>";

echo "<h3>2. Usar navegación desde dashboard:</h3>";
echo "<ul>";
echo "<li>Ve a <a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li>Usa el menú lateral → Usuarios → Asignar rol</li>";
echo "<li>Esto mantiene el contexto de la sesión</li>";
echo "</ul>";

echo "<h3>3. Verificar JavaScript:</h3>";
echo "<ul>";
echo "<li>Abre las herramientas de desarrollador (F12)</li>";
echo "<li>Ve a la pestaña Console</li>";
echo "<li>Busca errores de JavaScript</li>";
echo "<li>Verifica que el AJAX funcione correctamente</li>";
echo "</ul>";

echo "<h2>7. URLs de Prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignación de Roles (directo)</a></li>";
echo "<li><a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a></li>";
echo "</ul>";
?> 