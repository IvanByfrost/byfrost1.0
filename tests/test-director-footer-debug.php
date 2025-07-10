<?php
// Script de prueba para verificar el footer del director
require_once '../config.php';
require_once '../app/controllers/directorDashboardController.php';

echo "<h1>Prueba de Footer del Director Dashboard</h1>";

// Simular una sesión de director
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'director';
$_SESSION['user_name'] = 'Director Test';

try {
    // Crear conexión a la base de datos
    $dbConn = new PDO("mysql:host=localhost;dbname=byfrost", "root", "");
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>1. Verificación de Archivos</h2>";
    
    // Verificar que existe el archivo dashFooter.php
    $footerPath = ROOT . '/app/views/layouts/dashFooter.php';
    if (file_exists($footerPath)) {
        echo "<div style='color: green;'>✅ dashFooter.php existe en: $footerPath</div>";
    } else {
        echo "<div style='color: red;'>❌ dashFooter.php NO existe en: $footerPath</div>";
    }
    
    // Verificar que existe el dashboard del director
    $dashboardPath = ROOT . '/app/views/director/dashboard.php';
    if (file_exists($dashboardPath)) {
        echo "<div style='color: green;'>✅ dashboard.php existe en: $dashboardPath</div>";
    } else {
        echo "<div style='color: red;'>❌ dashboard.php NO existe en: $dashboardPath</div>";
    }
    
    echo "<h2>2. Verificación del Controlador</h2>";
    
    // Crear instancia del controlador
    $controller = new DirectorDashboardController($dbConn);
    
    // Verificar que el método loadDashboardView existe en MainController
    $reflection = new ReflectionClass('MainController');
    if ($reflection->hasMethod('loadDashboardView')) {
        echo "<div style='color: green;'>✅ Método loadDashboardView existe en MainController</div>";
    } else {
        echo "<div style='color: red;'>❌ Método loadDashboardView NO existe en MainController</div>";
    }
    
    echo "<h2>3. Verificación de Constantes</h2>";
    
    // Verificar constantes necesarias
    if (defined('ROOT')) {
        echo "<div style='color: green;'>✅ Constante ROOT definida: " . ROOT . "</div>";
    } else {
        echo "<div style='color: red;'>❌ Constante ROOT NO definida</div>";
    }
    
    if (defined('url')) {
        echo "<div style='color: green;'>✅ Constante url definida: " . url . "</div>";
    } else {
        echo "<div style='color: red;'>❌ Constante url NO definida</div>";
    }
    
    echo "<h2>4. Prueba de Carga del Dashboard</h2>";
    
    // Intentar cargar el dashboard
    echo "<p>Intentando cargar el dashboard del director...</p>";
    
    // Capturar la salida
    ob_start();
    
    try {
        $controller->showDashboard();
        $output = ob_get_clean();
        
        // Verificar si el footer está en la salida
        if (strpos($output, 'Byfrost &copy; 2026') !== false) {
            echo "<div style='color: green;'>✅ Footer encontrado en la salida</div>";
        } else {
            echo "<div style='color: red;'>❌ Footer NO encontrado en la salida</div>";
        }
        
        // Verificar si los scripts están cargados
        if (strpos($output, 'jquery-3.6.0.min.js') !== false) {
            echo "<div style='color: green;'>✅ Scripts de jQuery encontrados</div>";
        } else {
            echo "<div style='color: red;'>❌ Scripts de jQuery NO encontrados</div>";
        }
        
        // Mostrar las primeras 500 caracteres de la salida
        echo "<h3>Primeras 500 caracteres de la salida:</h3>";
        echo "<pre>" . htmlspecialchars(substr($output, 0, 500)) . "...</pre>";
        
    } catch (Exception $e) {
        $output = ob_get_clean();
        echo "<div style='color: red;'>❌ Error al cargar el dashboard: " . $e->getMessage() . "</div>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }
    
    echo "<h2>5. Verificación Manual del Footer</h2>";
    
    // Verificar contenido del dashFooter.php
    $footerContent = file_get_contents($footerPath);
    
    if (strpos($footerContent, 'Byfrost &copy; 2026') !== false) {
        echo "<div style='color: green;'>✅ Footer contiene el copyright correcto</div>";
    } else {
        echo "<div style='color: red;'>❌ Footer NO contiene el copyright correcto</div>";
    }
    
    if (strpos($footerContent, '</body>') !== false && strpos($footerContent, '</html>') !== false) {
        echo "<div style='color: green;'>✅ Footer tiene las etiquetas de cierre correctas</div>";
    } else {
        echo "<div style='color: red;'>❌ Footer NO tiene las etiquetas de cierre correctas</div>";
    }
    
    echo "<h2>6. Recomendaciones</h2>";
    echo "<ul>";
    echo "<li>El controlador debe usar loadDashboardView() que incluye automáticamente dashFooter.php</li>";
    echo "<li>Verificar que no hay errores de PHP que impidan la carga completa</li>";
    echo "<li>Limpiar el caché del navegador</li>";
    echo "<li>Verificar que todas las constantes están definidas correctamente</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>Error general: " . $e->getMessage() . "</div>";
}
?> 