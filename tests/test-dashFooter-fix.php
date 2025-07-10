<?php
require_once '../app/scripts/connection.php';
require_once '../app/library/SessionManager.php';

echo "<h2>🔍 PRUEBA DE DASHFOOTER</h2>";

// Inicializar SessionManager
$sessionManager = new SessionManager();

if ($sessionManager->isLoggedIn()) {
    echo "<h3>✅ Usuario logueado</h3>";
    
    $userRole = $sessionManager->getUserRole();
    echo "<p><strong>Rol del usuario:</strong> " . htmlspecialchars($userRole) . "</p>";
    
    // Simular el código del dashFooter
    echo "<h3>🔧 Simulación del dashFooter:</h3>";
    
    // Definir las constantes necesarias
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
    
    // Simular la lógica del dashFooter
    echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 10px 0;'>";
    echo "<h4>Footer del Dashboard:</h4>";
    
    // Obtener el rol del usuario de forma independiente
    if (!isset($userRole)) {
        $sessionManager = new SessionManager();
        $userRole = $sessionManager->getUserRole();
    }
    
    echo "<p><strong>Rol detectado:</strong> " . htmlspecialchars($userRole) . "</p>";
    
    if ($userRole === 'director') {
        echo "<p style='color: green;'>✅ Se cargará directorDashboard.js</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ No se cargará directorDashboard.js (rol: $userRole)</p>";
    }
    
    echo "<p><strong>Scripts que se cargarían:</strong></p>";
    echo "<ul>";
    echo "<li>✅ Swiper</li>";
    echo "<li>✅ jQuery</li>";
    echo "<li>✅ SweetAlert2</li>";
    echo "<li>✅ Bootstrap</li>";
    echo "<li>✅ Lucide</li>";
    echo "<li>✅ onlyNumber.js</li>";
    echo "<li>✅ toggles.js</li>";
    echo "<li>✅ loadView.js</li>";
    echo "<li>✅ sessionHandler.js</li>";
    echo "<li>✅ userSearch.js</li>";
    echo "<li>✅ createSchool.js</li>";
    echo "<li>✅ userManagement.js</li>";
    echo "<li>✅ roleManagement.js</li>";
    echo "<li>✅ Uploadpicture.js</li>";
    echo "<li>✅ User.js</li>";
    echo "<li>✅ Principal.js</li>";
    echo "<li>✅ app.js</li>";
    echo "<li>✅ profileSettings.js</li>";
    echo "<li>✅ payrollManagement.js</li>";
    if ($userRole === 'director') {
        echo "<li>✅ directorDashboard.js</li>";
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
?> 