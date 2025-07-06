<?php
/**
 * Test para verificar que el menú del director funciona correctamente
 */

echo "<h1>Test: Menú del Director</h1>";

// Incluir las dependencias necesarias
require_once '../config.php';
require_once '../app/library/SessionManager.php';
require_once '../app/scripts/connection.php';

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
        echo "<div class='alert alert-danger'>❌ Tu rol no es director</div>";
    } else {
        echo "<div class='alert alert-success'>✅ Tienes rol de director</div>";
    }
}

echo "<h2>2. Verificar Controlador</h2>";
$controllerPath = '../app/controllers/DirectorController.php';
if (file_exists($controllerPath)) {
    echo "<div class='alert alert-success'>✅ DirectorController existe</div>";
    
    // Verificar métodos del controlador
    require_once $controllerPath;
    $controller = new DirectorController($dbConn);
    $methods = get_class_methods($controller);
    
    echo "<p><strong>Métodos disponibles:</strong></p>";
    echo "<ul>";
    foreach ($methods as $method) {
        if ($method !== '__construct' && !str_starts_with($method, '_')) {
            echo "<li><code>$method</code></li>";
        }
    }
    echo "</ul>";
    
    if (method_exists($controller, 'menuDirector')) {
        echo "<div class='alert alert-success'>✅ Método menuDirector existe</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Método menuDirector NO existe</div>";
    }
    
    if (method_exists($controller, 'dashboard')) {
        echo "<div class='alert alert-success'>✅ Método dashboard existe</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Método dashboard NO existe</div>";
    }
} else {
    echo "<div class='alert alert-danger'>❌ DirectorController NO existe</div>";
}

echo "<h2>3. Verificar Vista</h2>";
$viewPath = '../app/views/director/menuDirector.php';
if (file_exists($viewPath)) {
    echo "<div class='alert alert-success'>✅ Vista menuDirector.php existe</div>";
    
    // Verificar contenido de la vista
    $content = file_get_contents($viewPath);
    if (strpos($content, 'Bienvenido al Dashboard del Director') !== false) {
        echo "<div class='alert alert-success'>✅ Vista contiene el contenido esperado</div>";
    } else {
        echo "<div class='alert alert-warning'>⚠️ Vista no contiene el contenido esperado</div>";
    }
} else {
    echo "<div class='alert alert-danger'>❌ Vista menuDirector.php NO existe</div>";
}

echo "<h2>4. URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=director&action=dashboard' target='_blank'>Dashboard Director</a></li>";
echo "<li><a href='http://localhost:8000/?view=director&action=menuDirector' target='_blank'>Menú Director (Directo)</a></li>";
echo "</ul>";

echo "<h2>5. Test de Router</h2>";
echo "<p>El router debería mapear automáticamente:</p>";
echo "<ul>";
echo "<li><code>view=director</code> → <code>DirectorController</code></li>";
echo "<li><code>action=menuDirector</code> → <code>menuDirector()</code></li>";
echo "</ul>";

echo "<h2>6. Instrucciones de Prueba</h2>";
echo "<ol>";
echo "<li>Haz clic en 'Dashboard Director' de arriba</li>";
echo "<li>Deberías ver el menú del director con las tarjetas</li>";
echo "<li>Si no funciona, revisa la consola del navegador (F12)</li>";
echo "<li>Comparte cualquier error que veas</li>";
echo "</ol>";

echo "<h2>7. Debug del Router</h2>";
echo "<p>Para ver el debug del router, abre las herramientas de desarrollador (F12) y revisa:</p>";
echo "<ul>";
echo "<li>Comentarios HTML con 'DEBUG ROUTER'</li>";
echo "<li>Errores en la consola</li>";
echo "<li>Respuesta del servidor</li>";
echo "</ul>";
?> 