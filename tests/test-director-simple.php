<?php
// Script de prueba simple para el dashboard del director
echo "<h1>🧪 Prueba Simple del Dashboard del Director</h1>";

// Definir ROOT si no está definido
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

// Verificar archivos básicos
$files = [
    'config.php' => 'config.php',
    'MainController.php' => 'app/controllers/MainController.php',
    'directorDashboardController.php' => 'app/controllers/directorDashboardController.php',
    'dashboard.php' => 'app/views/director/dashboard.php',
    'dashHeader.php' => 'app/views/layouts/dashHeader.php',
    'dashFooter.php' => 'app/views/layouts/dashFooter.php'
];

echo "<h2>📁 Verificación de Archivos</h2>";
foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "<p style='color: green;'>✅ $name existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $name NO existe</p>";
    }
}

// Cargar config.php
if (file_exists('config.php')) {
    require_once 'config.php';
    echo "<p style='color: green;'>✅ config.php cargado</p>";
    
    if (defined('url') && defined('app') && defined('rq')) {
        echo "<p style='color: green;'>✅ Constantes definidas:</p>";
        echo "<ul>";
        echo "<li>url = " . url . "</li>";
        echo "<li>app = " . app . "</li>";
        echo "<li>rq = " . rq . "</li>";
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ Faltan constantes</p>";
    }
} else {
    echo "<p style='color: red;'>❌ config.php no existe</p>";
}

// Probar carga del controlador
echo "<h2>🎮 Prueba del Controlador</h2>";
if (file_exists('app/controllers/MainController.php') && file_exists('app/controllers/directorDashboardController.php')) {
    try {
        require_once 'app/controllers/MainController.php';
        require_once 'app/controllers/directorDashboardController.php';
        
        if (class_exists('DirectorDashboardController')) {
            echo "<p style='color: green;'>✅ Clase DirectorDashboardController existe</p>";
            
            // Verificar herencia
            $reflection = new ReflectionClass('DirectorDashboardController');
            $parent = $reflection->getParentClass();
            if ($parent && $parent->getName() === 'MainController') {
                echo "<p style='color: green;'>✅ Hereda de MainController</p>";
            } else {
                echo "<p style='color: red;'>❌ NO hereda de MainController</p>";
            }
            
            // Verificar método
            if (method_exists('DirectorDashboardController', 'showDashboard')) {
                echo "<p style='color: green;'>✅ Método showDashboard existe</p>";
            } else {
                echo "<p style='color: red;'>❌ Método showDashboard NO existe</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Clase NO existe</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error al cargar controlador: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Archivos del controlador no existen</p>";
}

// Probar carga del footer
echo "<h2>📋 Prueba del Footer</h2>";
if (file_exists('app/views/layouts/dashFooter.php')) {
    try {
        // Simular las constantes si no están definidas
        if (!defined('url')) define('url', 'http://localhost:8000/');
        if (!defined('app')) define('app', 'app/');
        if (!defined('rq')) define('rq', 'resources/');
        
        ob_start();
        include 'app/views/layouts/dashFooter.php';
        $footerOutput = ob_get_clean();
        
        if (!empty($footerOutput)) {
            echo "<p style='color: green;'>✅ Footer se carga correctamente</p>";
            echo "<details><summary>Ver contenido del footer</summary><pre>" . htmlspecialchars(substr($footerOutput, 0, 500)) . "...</pre></details>";
        } else {
            echo "<p style='color: red;'>❌ Footer no genera contenido</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error al cargar footer: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ dashFooter.php no existe</p>";
}

echo "<h2>🔗 URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='/?view=directorDashboard' target='_blank'>Dashboard Director (controlador)</a></li>";
echo "<li><a href='/?view=director&action=dashboard' target='_blank'>Dashboard Director (alternativo)</a></li>";
echo "</ul>";

echo "<h2>💡 Estado Actual</h2>";
echo "<p>Si todo está verde arriba, el dashboard debería funcionar. Si hay errores rojos, necesitamos arreglarlos.</p>";
?> 