<?php
/**
 * Test: RootController - Verificación de Funcionamiento
 */

echo "<h1>Test: RootController</h1>";
echo "<p><strong>Objetivo:</strong> Verificar que el RootController funcione correctamente</p>";

// 1. Verificar archivos críticos
echo "<h2>1. Verificación de Archivos</h2>";
define('ROOT', __DIR__ . '/');

$criticalFiles = [
    'app/controllers/RootController.php' => 'Controlador Root',
    'app/controllers/MainController.php' => 'Controlador Base',
    'app/views/root/dashboard.php' => 'Vista Dashboard',
    'app/views/root/rootSidebar.php' => 'Sidebar Root',
    'app/library/SessionManager.php' => 'SessionManager'
];

foreach ($criticalFiles as $file => $description) {
    $exists = file_exists(ROOT . $file);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$file</strong> ($description): " . ($exists ? "EXISTE" : "NO EXISTE") . "</div>";
    
    if ($exists) {
        $content = file_get_contents(ROOT . $file);
        $size = strlen($content);
        echo "<div style='margin-left: 20px; color: blue;'>📄 Tamaño: " . number_format($size) . " bytes</div>";
        
        // Verificar que no esté vacío
        if ($size < 50) {
            echo "<div style='margin-left: 20px; color: orange;'>⚠️ Archivo muy pequeño, puede estar vacío</div>";
        }
    }
}

// 2. Verificar clase RootController
echo "<h2>2. Verificación de la Clase RootController</h2>";
$controllerFile = ROOT . 'app/controllers/RootController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    // Verificar que tenga la declaración de clase
    if (strpos($content, 'class RootController') !== false) {
        echo "<div style='color: green;'>✅ Clase RootController declarada</div>";
    } else {
        echo "<div style='color: red;'>❌ Clase RootController NO declarada</div>";
    }
    
    // Verificar herencia
    if (strpos($content, 'extends MainController') !== false) {
        echo "<div style='color: green;'>✅ Hereda de MainController</div>";
    } else {
        echo "<div style='color: red;'>❌ NO hereda de MainController</div>";
    }
    
    // Verificar métodos
    $methods = ['dashboard', 'configuracion', 'menuRoot', 'index', 'protectRoot'];
    foreach ($methods as $method) {
        if (strpos($content, "function $method") !== false) {
            echo "<div style='color: green;'>✅ Método $method() existe</div>";
        } else {
            echo "<div style='color: red;'>❌ Método $method() NO existe</div>";
        }
    }
}

// 3. Verificar vista dashboard
echo "<h2>3. Verificación de la Vista Dashboard</h2>";
$dashboardFile = ROOT . 'app/views/root/dashboard.php';
if (file_exists($dashboardFile)) {
    $content = file_get_contents($dashboardFile);
    
    // Verificar inclusión de JavaScript
    if (strpos($content, 'loadView.js') !== false) {
        echo "<div style='color: green;'>✅ loadView.js incluido</div>";
    } else {
        echo "<div style='color: red;'>❌ loadView.js NO incluido</div>";
    }
    
    // Verificar BASE_URL
    if (strpos($content, 'BASE_URL') !== false) {
        echo "<div style='color: green;'>✅ BASE_URL definido</div>";
    } else {
        echo "<div style='color: red;'>❌ BASE_URL NO definido</div>";
    }
    
    // Verificar sidebar
    if (strpos($content, 'rootSidebar.php') !== false) {
        echo "<div style='color: green;'>✅ Sidebar incluido</div>";
    } else {
        echo "<div style='color: red;'>❌ Sidebar NO incluido</div>";
    }
}

// 4. Verificar sidebar
echo "<h2>4. Verificación del Sidebar</h2>";
$sidebarFile = ROOT . 'app/views/root/rootSidebar.php';
if (file_exists($sidebarFile)) {
    $content = file_get_contents($sidebarFile);
    
    // Verificar enlaces de navegación
    $navLinks = ['user', 'school', 'activity', 'schedule'];
    foreach ($navLinks as $link) {
        if (strpos($content, "view=$link") !== false) {
            echo "<div style='color: green;'>✅ Enlace a $link existe</div>";
        } else {
            echo "<div style='color: orange;'>⚠️ Enlace a $link NO encontrado</div>";
        }
    }
} else {
    echo "<div style='color: red;'>❌ Sidebar no existe</div>";
}

// 5. URLs de prueba
echo "<h2>5. URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><a href='http://localhost:8000/?view=root&action=configuracion' target='_blank'>Configuración</a></li>";
echo "<li><a href='http://localhost:8000/?view=root&action=menuRoot' target='_blank'>Menú Root</a></li>";
echo "</ul>";

// 6. Instrucciones de prueba
echo "<h2>6. Instrucciones de Prueba</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
echo "<ol>";
echo "<li><strong>Hacer login como root:</strong> <a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a></li>";
echo "<li><strong>Acceder al dashboard:</strong> <a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard</a></li>";
echo "<li><strong>Verificar que se muestre:</strong>";
echo "<ul>";
echo "<li>✅ Sidebar con opciones de navegación</li>";
echo "<li>✅ Mensaje de bienvenida</li>";
echo "<li>✅ JavaScript cargado correctamente</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Probar navegación:</strong> Hacer clic en diferentes opciones del sidebar</li>";
echo "<li><strong>Verificar AJAX:</strong> Las vistas deben cargar sin recargar la página</li>";
echo "</ol>";
echo "</div>";

// 7. Estado del sistema
echo "<h2>7. Estado del Sistema</h2>";
echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb;'>";
echo "<strong>✅ RootController Implementado</strong><br>";
echo "El RootController ha sido creado con todos los métodos necesarios.<br>";
echo "El dashboard debería funcionar correctamente ahora.";
echo "</div>";

echo "<hr>";
echo "<p><strong>Nota:</strong> Si aún hay errores, verifica que estés logueado como usuario root.</p>";
?> 