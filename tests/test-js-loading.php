<?php
/**
 * Test para verificar la carga de JavaScript
 */

echo "<h1>Test: Carga de JavaScript</h1>";

// 1. Verificar archivos JavaScript
echo "<h2>1. Verificar archivos JavaScript:</h2>";
define('ROOT', __DIR__ . '/');

$jsFiles = [
    'app/resources/js/loadView.js' => ROOT . 'app/resources/js/loadView.js',
    'app/resources/js/assignRole.js' => ROOT . 'app/resources/js/assignRole.js'
];

foreach ($jsFiles as $name => $path) {
    $exists = file_exists($path);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$name</strong>: " . ($exists ? "EXISTE" : "NO EXISTE") . "</div>";
    
    if ($exists) {
        $content = file_get_contents($path);
        $size = strlen($content);
        echo "<div style='margin-left: 20px; color: blue;'>📄 Tamaño: " . number_format($size) . " bytes</div>";
    }
}

// 2. Verificar inclusión en la vista
echo "<h2>2. Verificar inclusión en la vista:</h2>";
$viewFile = ROOT . 'app/views/user/assignRole.php';
if (file_exists($viewFile)) {
    $viewContent = file_get_contents($viewFile);
    
    if (strpos($viewContent, 'assignRole.js') !== false) {
        echo "<div style='color: green;'>✅ assignRole.js incluido en la vista</div>";
    } else {
        echo "<div style='color: red;'>❌ assignRole.js NO incluido en la vista</div>";
    }
    
    if (strpos($viewContent, 'searchUserForm') !== false) {
        echo "<div style='color: green;'>✅ Formulario searchUserForm encontrado</div>";
    } else {
        echo "<div style='color: red;'>❌ Formulario searchUserForm NO encontrado</div>";
    }
    
    if (strpos($viewContent, 'method="GET"') !== false) {
        echo "<div style='color: orange;'>⚠️ Formulario aún tiene method='GET' - esto puede causar recarga</div>";
    } else {
        echo "<div style='color: green;'>✅ Formulario sin method='GET' - correcto para AJAX</div>";
    }
} else {
    echo "<div style='color: red;'>❌ Vista assignRole.php no encontrada</div>";
}

// 3. Verificar dashboard
echo "<h2>3. Verificar dashboard:</h2>";
$dashboardFile = ROOT . 'app/views/root/dashboard.php';
if (file_exists($dashboardFile)) {
    $dashboardContent = file_get_contents($dashboardFile);
    
    if (strpos($dashboardContent, 'loadView.js') !== false) {
        echo "<div style='color: green;'>✅ loadView.js incluido en el dashboard</div>";
    } else {
        echo "<div style='color: red;'>❌ loadView.js NO incluido en el dashboard</div>";
    }
    
    if (strpos($dashboardContent, 'BASE_URL') !== false) {
        echo "<div style='color: green;'>✅ BASE_URL definido en el dashboard</div>";
    } else {
        echo "<div style='color: red;'>❌ BASE_URL NO definido en el dashboard</div>";
    }
} else {
    echo "<div style='color: red;'>❌ Dashboard no encontrado</div>";
}

// 4. URLs de prueba
echo "<h2>4. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>Asignar Roles (Directo)</a></li>";
echo "</ul>";

// 5. Instrucciones de debug
echo "<h2>5. Instrucciones de debug:</h2>";
echo "<ol>";
echo "<li><strong>Abrir las herramientas de desarrollador</strong> (F12)</li>";
echo "<li><strong>Ir a la pestaña Console</strong></li>";
echo "<li><strong>Acceder al dashboard:</strong> <code>http://localhost:8000/?view=root&action=dashboard</code></li>";
echo "<li><strong>Ir a:</strong> Usuarios → Asignar rol</li>";
echo "<li><strong>Verificar en la consola:</strong>";
echo "<ul>";
echo "<li>✅ 'Inicializando sistema de asignación de roles...'</li>";
echo "<li>✅ 'Formulario de búsqueda encontrado, configurando eventos...'</li>";
echo "<li>✅ 'Cargando usuarios sin rol...'</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Probar búsqueda:</strong> Llenar formulario y hacer clic en Buscar</li>";
echo "<li><strong>Verificar en la consola:</strong>";
echo "<ul>";
echo "<li>✅ 'Formulario enviado, procesando búsqueda...'</li>";
echo "<li>✅ 'Buscando usuarios: [tipo] [número]'</li>";
echo "<li>✅ 'URL de búsqueda: [url]'</li>";
echo "<li>✅ 'Respuesta recibida: 200'</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 JavaScript listo para debug</p>";
echo "<p><strong>Nota:</strong> Si ves errores en la consola, compártelos para diagnosticar el problema.</p>";

// Test para verificar que el JavaScript se carga correctamente
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Carga de JavaScript</h2>";

// 1. Verificar que el archivo JS existe
$jsFile = '../app/resources/js/assignRole.js';
if (file_exists($jsFile)) {
    echo "✓ assignRole.js existe<br>";
    
    $content = file_get_contents($jsFile);
    
    // Verificar que tiene el contenido básico
    if (strpos($content, 'initializeAssignRole') !== false) {
        echo "✓ Tiene función initializeAssignRole()<br>";
    } else {
        echo "✗ NO tiene función initializeAssignRole()<br>";
    }
    
    if (strpos($content, 'handleSearchSubmit') !== false) {
        echo "✓ Tiene función handleSearchSubmit()<br>";
    } else {
        echo "✗ NO tiene función handleSearchSubmit()<br>";
    }
    
    if (strpos($content, 'searchUsersByDocument') !== false) {
        echo "✓ Tiene función searchUsersByDocument()<br>";
    } else {
        echo "✗ NO tiene función searchUsersByDocument()<br>";
    }
    
    // Verificar que tiene la inicialización automática
    if (strpos($content, 'DOMContentLoaded') !== false) {
        echo "✓ Tiene inicialización automática<br>";
    } else {
        echo "✗ NO tiene inicialización automática<br>";
    }
    
} else {
    echo "✗ assignRole.js NO existe<br>";
}

// 2. Verificar que la vista incluye el JavaScript
$viewFile = '../app/views/user/assignRole.php';
if (file_exists($viewFile)) {
    echo "<h3>Verificación de la Vista</h3>";
    
    $content = file_get_contents($viewFile);
    
    // Verificar que incluye el script
    if (strpos($content, 'assignRole.js') !== false) {
        echo "✓ Incluye assignRole.js<br>";
        
        // Extraer la línea donde se incluye
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if (strpos($line, 'assignRole.js') !== false) {
                echo "Línea: " . htmlspecialchars(trim($line)) . "<br>";
                break;
            }
        }
    } else {
        echo "✗ NO incluye assignRole.js<br>";
    }
    
    // Verificar que tiene el formulario con ID correcto
    if (strpos($content, 'id="searchUserForm"') !== false) {
        echo "✓ Formulario tiene ID correcto<br>";
    } else {
        echo "✗ Formulario NO tiene ID correcto<br>";
    }
    
    // Verificar que tiene onsubmit
    if (strpos($content, 'onsubmit="return false;"') !== false) {
        echo "✓ Formulario previene envío por defecto<br>";
    } else {
        echo "✗ Formulario NO previene envío por defecto<br>";
    }
    
} else {
    echo "✗ assignRole.php NO existe<br>";
}

// 3. Crear una página de prueba simple
echo "<h3>Página de Prueba Simple</h3>";
echo "Crea este archivo HTML para probar el JavaScript:<br>";
echo "<textarea style='width: 100%; height: 200px;'>";
echo "&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Test JavaScript&lt;/title&gt;
    &lt;script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"&gt;&lt;/script&gt;
    &lt;script src=\"app/resources/js/assignRole.js\"&gt;&lt;/script&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;Test de JavaScript&lt;/h1&gt;
    &lt;form id=\"searchUserForm\" method=\"POST\" action=\"#\" onsubmit=\"return false;\"&gt;
        &lt;select id=\"credential_type\" name=\"credential_type\"&gt;
            &lt;option value=\"\"&gt;Seleccionar tipo&lt;/option&gt;
            &lt;option value=\"CC\"&gt;Cédula de Ciudadanía&lt;/option&gt;
        &lt;/select&gt;
        &lt;input type=\"text\" id=\"credential_number\" name=\"credential_number\" placeholder=\"Número\"&gt;
        &lt;button type=\"submit\"&gt;Buscar&lt;/button&gt;
    &lt;/form&gt;
    &lt;div id=\"results\"&gt;&lt;/div&gt;
    
    &lt;script&gt;
        console.log('Página cargada');
        // Verificar si las funciones están disponibles
        if (typeof initializeAssignRole === 'function') {
            console.log('✓ initializeAssignRole está disponible');
        } else {
            console.log('✗ initializeAssignRole NO está disponible');
        }
        
        if (typeof searchUsersByDocument === 'function') {
            console.log('✓ searchUsersByDocument está disponible');
        } else {
            console.log('✗ searchUsersByDocument NO está disponible');
        }
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;";
echo "</textarea>";

echo "<h3>Pasos para Diagnosticar</h3>";
echo "1. Abre la página de asignación de roles<br>";
echo "2. Presiona F12 para abrir herramientas de desarrollador<br>";
echo "3. Ve a la pestaña Console<br>";
echo "4. Recarga la página<br>";
echo "5. Dime qué mensajes o errores aparecen en la consola<br>";

echo "<h3>Posibles Problemas</h3>";
echo "- El archivo JavaScript no se está cargando<br>";
echo "- Hay un error de sintaxis en el JavaScript<br>";
echo "- jQuery no está disponible<br>";
echo "- La URL del archivo JavaScript es incorrecta<br>";
echo "- El formulario no tiene el ID correcto<br>";

echo "<br><strong>¡Dime qué aparece en la consola del navegador!</strong>";
?> 