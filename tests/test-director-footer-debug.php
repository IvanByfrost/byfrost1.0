<?php
define('ROOT', dirname(__DIR__));

echo "<h1>🔍 Diagnóstico del Footer del Director Dashboard</h1>";

// Verificar archivos críticos
$criticalFiles = [
    'config.php' => ROOT . '/config.php',
    'dashHeader.php' => ROOT . '/app/views/layouts/dashHeader.php',
    'dashFooter.php' => ROOT . '/app/views/layouts/dashFooter.php',
    'directorDashboardController.php' => ROOT . '/app/controllers/directorDashboardController.php',
    'dashboard.php' => ROOT . '/app/views/director/dashboard.php'
];

echo "<h2>📁 Verificación de Archivos Críticos</h2>";
foreach ($criticalFiles as $name => $path) {
    if (file_exists($path)) {
        echo "<p style='color: green;'>✅ $name existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $name NO existe en: $path</p>";
    }
}

// Verificar constantes en config.php
echo "<h2>⚙️ Verificación de Constantes</h2>";
if (file_exists(ROOT . '/config.php')) {
    require_once ROOT . '/config.php';
    
    $constants = ['url', 'app', 'rq', 'ROOT'];
    foreach ($constants as $const) {
        if (defined($const)) {
            echo "<p style='color: green;'>✅ $const = " . constant($const) . "</p>";
        } else {
            echo "<p style='color: red;'>❌ $const NO está definida</p>";
        }
    }
} else {
    echo "<p style='color: red;'>❌ config.php no existe</p>";
}

// Verificar contenido del dashHeader.php
echo "<h2>📋 Contenido de dashHeader.php</h2>";
if (file_exists(ROOT . '/app/views/layouts/dashHeader.php')) {
    $headerContent = file_get_contents(ROOT . '/app/views/layouts/dashHeader.php');
    
    // Verificar si incluye config.php
    if (strpos($headerContent, 'config.php') !== false) {
        echo "<p style='color: green;'>✅ dashHeader.php incluye config.php</p>";
    } else {
        echo "<p style='color: red;'>❌ dashHeader.php NO incluye config.php</p>";
    }
    
    // Verificar si define las constantes
    if (strpos($headerContent, 'url') !== false) {
        echo "<p style='color: green;'>✅ dashHeader.php usa la constante url</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ dashHeader.php no usa la constante url</p>";
    }
} else {
    echo "<p style='color: red;'>❌ dashHeader.php no existe</p>";
}

// Verificar contenido del dashFooter.php
echo "<h2>📋 Contenido de dashFooter.php</h2>";
if (file_exists(ROOT . '/app/views/layouts/dashFooter.php')) {
    $footerContent = file_get_contents(ROOT . '/app/views/layouts/dashFooter.php');
    
    // Verificar si usa las constantes
    $constantsUsed = ['url', 'app', 'rq'];
    foreach ($constantsUsed as $const) {
        if (strpos($footerContent, $const) !== false) {
            echo "<p style='color: green;'>✅ dashFooter.php usa la constante $const</p>";
        } else {
            echo "<p style='color: red;'>❌ dashFooter.php NO usa la constante $const</p>";
        }
    }
    
    // Verificar si incluye config.php
    if (strpos($footerContent, 'config.php') !== false) {
        echo "<p style='color: green;'>✅ dashFooter.php incluye config.php</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ dashFooter.php NO incluye config.php directamente</p>";
    }
} else {
    echo "<p style='color: red;'>❌ dashFooter.php no existe</p>";
}

// Verificar el controlador del director
echo "<h2>🎮 Verificación del Controlador</h2>";
if (file_exists(ROOT . '/app/controllers/directorDashboardController.php')) {
    require_once ROOT . '/app/controllers/directorDashboardController.php';
    
    if (class_exists('DirectorDashboardController')) {
        echo "<p style='color: green;'>✅ Clase DirectorDashboardController existe</p>";
        
        // Verificar herencia
        $reflection = new ReflectionClass('DirectorDashboardController');
        $parent = $reflection->getParentClass();
        if ($parent && $parent->getName() === 'MainController') {
            echo "<p style='color: green;'>✅ DirectorDashboardController hereda de MainController</p>";
        } else {
            echo "<p style='color: red;'>❌ DirectorDashboardController NO hereda de MainController</p>";
        }
        
        // Verificar método showDashboard
        if (method_exists('DirectorDashboardController', 'showDashboard')) {
            echo "<p style='color: green;'>✅ Método showDashboard existe</p>";
        } else {
            echo "<p style='color: red;'>❌ Método showDashboard NO existe</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Clase DirectorDashboardController NO existe</p>";
    }
} else {
    echo "<p style='color: red;'>❌ directorDashboardController.php no existe</p>";
}

// Verificar el dashboard view
echo "<h2>📄 Verificación del Dashboard View</h2>";
if (file_exists(ROOT . '/app/views/director/dashboard.php')) {
    $dashboardContent = file_get_contents(ROOT . '/app/views/director/dashboard.php');
    
    // Verificar si incluye config.php
    if (strpos($dashboardContent, 'config.php') !== false) {
        echo "<p style='color: green;'>✅ dashboard.php incluye config.php</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ dashboard.php NO incluye config.php</p>";
    }
    
    // Verificar si incluye dashHeader
    if (strpos($dashboardContent, 'dashHeader.php') !== false) {
        echo "<p style='color: green;'>✅ dashboard.php incluye dashHeader.php</p>";
    } else {
        echo "<p style='color: red;'>❌ dashboard.php NO incluye dashHeader.php</p>";
    }
    
    // Verificar si incluye dashFooter
    if (strpos($dashboardContent, 'dashFooter.php') !== false) {
        echo "<p style='color: green;'>✅ dashboard.php incluye dashFooter.php</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ dashboard.php NO incluye dashFooter.php directamente</p>";
    }
} else {
    echo "<p style='color: red;'>❌ dashboard.php no existe</p>";
}

// Simular carga del dashboard
echo "<h2>🧪 Simulación de Carga</h2>";
echo "<p>Probando carga del dashboard del director...</p>";

try {
    // Definir constantes necesarias
    if (!defined('ROOT')) {
        define('ROOT', dirname(__DIR__));
    }
    
    if (file_exists(ROOT . '/config.php')) {
        require_once ROOT . '/config.php';
        echo "<p style='color: green;'>✅ config.php cargado correctamente</p>";
        
        // Verificar constantes después de cargar config.php
        if (defined('url') && defined('app') && defined('rq')) {
            echo "<p style='color: green;'>✅ Todas las constantes están definidas después de cargar config.php</p>";
            echo "<p>url = " . url . "</p>";
            echo "<p>app = " . app . "</p>";
            echo "<p>rq = " . rq . "</p>";
        } else {
            echo "<p style='color: red;'>❌ Algunas constantes NO están definidas después de cargar config.php</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ No se pudo cargar config.php</p>";
    }
    
    // Probar carga del dashHeader
    if (file_exists(ROOT . '/app/views/layouts/dashHeader.php')) {
        echo "<p style='color: green;'>✅ dashHeader.php existe y se puede cargar</p>";
    } else {
        echo "<p style='color: red;'>❌ dashHeader.php no existe</p>";
    }
    
    // Probar carga del dashFooter
    if (file_exists(ROOT . '/app/views/layouts/dashFooter.php')) {
        echo "<p style='color: green;'>✅ dashFooter.php existe y se puede cargar</p>";
    } else {
        echo "<p style='color: red;'>❌ dashFooter.php no existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error durante la simulación: " . $e->getMessage() . "</p>";
}

echo "<h2>🔗 URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=directorDashboard' target='_blank'>Dashboard Director (vía controlador)</a></li>";
echo "<li><a href='http://localhost:8000/?view=director&action=dashboard' target='_blank'>Dashboard Director (vía director controller)</a></li>";
echo "</ul>";

echo "<h2>💡 Recomendaciones</h2>";
echo "<ul>";
echo "<li>Si el footer no aparece, verifica que las constantes url, app, rq estén definidas</li>";
echo "<li>El dashboard debe cargarse a través del controlador DirectorDashboardController</li>";
echo "<li>El controlador debe usar loadDashboardView() que incluye automáticamente dashFooter.php</li>";
echo "<li>Si cargas el dashboard directamente, asegúrate de incluir config.php antes de dashFooter.php</li>";
echo "</ul>";
?> 