<?php
// Script de diagnóstico para el footer del director
echo "<h1>🔍 Diagnóstico del Footer del Director</h1>";

// Verificar archivos básicos
$files = [
    'config.php' => 'config.php',
    'dashHeader.php' => 'app/views/layouts/dashHeader.php',
    'dashFooter.php' => 'app/views/layouts/dashFooter.php',
    'directorDashboardController.php' => 'app/controllers/directorDashboardController.php',
    'dashboard.php' => 'app/views/director/dashboard.php'
];

echo "<h2>📁 Archivos Críticos</h2>";
foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "<p style='color: green;'>✅ $name existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $name NO existe</p>";
    }
}

// Verificar constantes
echo "<h2>⚙️ Constantes</h2>";
if (file_exists('config.php')) {
    require_once 'config.php';
    
    $constants = ['url', 'app', 'rq'];
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

// Verificar contenido del dashFooter
echo "<h2>📋 dashFooter.php</h2>";
if (file_exists('app/views/layouts/dashFooter.php')) {
    $footerContent = file_get_contents('app/views/layouts/dashFooter.php');
    
    // Verificar si usa las constantes
    if (strpos($footerContent, 'url') !== false) {
        echo "<p style='color: green;'>✅ Usa la constante url</p>";
    } else {
        echo "<p style='color: red;'>❌ NO usa la constante url</p>";
    }
    
    if (strpos($footerContent, 'app') !== false) {
        echo "<p style='color: green;'>✅ Usa la constante app</p>";
    } else {
        echo "<p style='color: red;'>❌ NO usa la constante app</p>";
    }
    
    if (strpos($footerContent, 'rq') !== false) {
        echo "<p style='color: green;'>✅ Usa la constante rq</p>";
    } else {
        echo "<p style='color: red;'>❌ NO usa la constante rq</p>";
    }
} else {
    echo "<p style='color: red;'>❌ dashFooter.php no existe</p>";
}

// Verificar el controlador
echo "<h2>🎮 Controlador</h2>";
if (file_exists('app/controllers/directorDashboardController.php')) {
    require_once 'app/controllers/directorDashboardController.php';
    
    if (class_exists('DirectorDashboardController')) {
        echo "<p style='color: green;'>✅ Clase existe</p>";
        
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
} else {
    echo "<p style='color: red;'>❌ Controlador no existe</p>";
}

// Probar carga del footer
echo "<h2>🧪 Prueba de Carga del Footer</h2>";
try {
    if (defined('url') && defined('app') && defined('rq')) {
        echo "<p style='color: green;'>✅ Todas las constantes están definidas</p>";
        
        // Intentar cargar el footer
        ob_start();
        include 'app/views/layouts/dashFooter.php';
        $footerOutput = ob_get_clean();
        
        if (!empty($footerOutput)) {
            echo "<p style='color: green;'>✅ Footer se carga correctamente</p>";
            echo "<details><summary>Ver contenido del footer</summary><pre>" . htmlspecialchars($footerOutput) . "</pre></details>";
        } else {
            echo "<p style='color: red;'>❌ Footer no genera contenido</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Faltan constantes para cargar el footer</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error al cargar footer: " . $e->getMessage() . "</p>";
}

echo "<h2>🔗 URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='/?view=directorDashboard' target='_blank'>Dashboard Director (controlador)</a></li>";
echo "<li><a href='/?view=director&action=dashboard' target='_blank'>Dashboard Director (alternativo)</a></li>";
echo "</ul>";

echo "<h2>💡 Solución Rápida</h2>";
echo "<p>Si nada funciona, vamos a arreglar el dashFooter.php para que funcione sin importar qué:</p>";
?> 