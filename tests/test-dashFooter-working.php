<?php
require_once '../app/scripts/connection.php';
require_once '../app/library/SessionManager.php';

echo "<h2>🔍 PRUEBA DE DASHFOOTER FUNCIONANDO</h2>";

// Simular el contexto del dashboard
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}
if (!defined('url')) {
    define('url', 'http://localhost:8000/');
}
if (!defined('app')) {
    define('app', 'app/');
}
if (!defined('rq')) {
    define('rq', 'resources/');
}

// Inicializar SessionManager
$sessionManager = new SessionManager();

if ($sessionManager->isLoggedIn()) {
    echo "<h3>✅ Usuario logueado</h3>";
    
    $userRole = $sessionManager->getUserRole();
    echo "<p><strong>Rol del usuario:</strong> " . htmlspecialchars($userRole) . "</p>";
    
    // Simular la inclusión del dashFooter
    echo "<h3>🔧 Simulación del dashFooter:</h3>";
    
    // Definir las variables que usa el dashFooter
    $baseUrl = defined('url') ? url : 'http://localhost:8000/';
    $appPath = defined('app') ? app : 'app/';
    $rqPath = defined('rq') ? rq : 'resources/';
    
    echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 10px 0;'>";
    echo "<h4>Variables del dashFooter:</h4>";
    echo "<p><strong>baseUrl:</strong> " . htmlspecialchars($baseUrl) . "</p>";
    echo "<p><strong>appPath:</strong> " . htmlspecialchars($appPath) . "</p>";
    echo "<p><strong>rqPath:</strong> " . htmlspecialchars($rqPath) . "</p>";
    echo "<p><strong>userRole:</strong> " . htmlspecialchars($userRole) . "</p>";
    
    // Verificar que los archivos JS existen
    $jsFiles = [
        'onlyNumber.js',
        'toggles.js',
        'loadView.js',
        'sessionHandler.js',
        'userSearch.js',
        'createSchool.js',
        'userManagement.js',
        'roleManagement.js',
        'Uploadpicture.js',
        'User.js',
        'Principal.js',
        'app.js',
        'profileSettings.js',
        'payrollManagement.js'
    ];
    
    if ($userRole === 'director') {
        $jsFiles[] = 'directorDashboard.js';
    }
    
    echo "<h4>Verificación de archivos JS:</h4>";
    foreach ($jsFiles as $jsFile) {
        $filePath = ROOT . '/app/resources/js/' . $jsFile;
        if (file_exists($filePath)) {
            echo "<p style='color: green;'>✅ " . htmlspecialchars($jsFile) . "</p>";
        } else {
            echo "<p style='color: red;'>❌ " . htmlspecialchars($jsFile) . " (NO EXISTE)</p>";
        }
    }
    
    echo "<h4>Scripts que se cargarían:</h4>";
    echo "<ul>";
    echo "<li>✅ Swiper</li>";
    echo "<li>✅ jQuery</li>";
    echo "<li>✅ SweetAlert2</li>";
    echo "<li>✅ Bootstrap</li>";
    echo "<li>✅ Lucide</li>";
    
    foreach ($jsFiles as $jsFile) {
        $filePath = ROOT . '/app/resources/js/' . $jsFile;
        if (file_exists($filePath)) {
            echo "<li>✅ " . htmlspecialchars($jsFile) . "</li>";
        } else {
            echo "<li style='color: red;'>❌ " . htmlspecialchars($jsFile) . "</li>";
        }
    }
    echo "</ul>";
    
    echo "</div>";
    
} else {
    echo "<h3>❌ Usuario no está logueado</h3>";
    echo "<p>Para probar esta funcionalidad, primero debes iniciar sesión.</p>";
    echo "<p><a href='../app/views/index/login.php'>Ir al login</a></p>";
}

echo "<h3>🔧 Información adicional:</h3>";
echo "<p><strong>Session Status:</strong> " . session_status() . "</p>";
echo "<p><strong>Session ID:</strong> " . (session_id() ?: 'No iniciada') . "</p>";
echo "<p><strong>ROOT:</strong> " . (defined('ROOT') ? ROOT : 'No definido') . "</p>";
echo "<p><strong>url:</strong> " . (defined('url') ? url : 'No definido') . "</p>";
echo "<p><strong>app:</strong> " . (defined('app') ? app : 'No definido') . "</p>";
echo "<p><strong>rq:</strong> " . (defined('rq') ? rq : 'No definido') . "</p>";
?> 