<?php
// Test para diagnosticar problemas en la página de login
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Diagnóstico - Página de Login</h2>";

// 1. Verificar configuración
echo "<h3>1. Verificación de Configuración</h3>";
if (file_exists('../config.php')) {
    echo "✓ config.php existe<br>";
    require_once '../config.php';
    
    if (defined('url')) {
        echo "✓ Constante 'url' definida: " . url . "<br>";
    } else {
        echo "✗ Constante 'url' NO definida<br>";
    }
    
    if (defined('app')) {
        echo "✓ Constante 'app' definida: " . app . "<br>";
    } else {
        echo "✗ Constante 'app' NO definida<br>";
    }
    
    if (defined('rq')) {
        echo "✓ Constante 'rq' definida: " . rq . "<br>";
    } else {
        echo "✗ Constante 'rq' NO definida<br>";
    }
} else {
    echo "✗ config.php NO existe<br>";
}

// 2. Verificar archivos de layout
echo "<h3>2. Verificación de Archivos de Layout</h3>";
$headFile = '../app/views/layouts/head.php';
$headerFile = '../app/views/layouts/header.php';

if (file_exists($headFile)) {
    echo "✓ head.php existe<br>";
} else {
    echo "✗ head.php NO existe<br>";
}

if (file_exists($headerFile)) {
    echo "✓ header.php existe<br>";
} else {
    echo "✗ header.php NO existe<br>";
}

// 3. Verificar archivo de login
echo "<h3>3. Verificación de Archivo de Login</h3>";
$loginFile = '../app/views/index/login.php';
if (file_exists($loginFile)) {
    echo "✓ login.php existe<br>";
    
    // Verificar contenido del archivo
    $content = file_get_contents($loginFile);
    if (strpos($content, 'require_once') !== false) {
        echo "✓ Contiene require_once<br>";
    } else {
        echo "✗ NO contiene require_once<br>";
    }
    
    if (strpos($content, 'url') !== false) {
        echo "✓ Contiene referencia a 'url'<br>";
    } else {
        echo "✗ NO contiene referencia a 'url'<br>";
    }
} else {
    echo "✗ login.php NO existe<br>";
}

// 4. Verificar rutas de recursos
echo "<h3>4. Verificación de Rutas de Recursos</h3>";
$cssPath = '../app/resources/css/';
$jsPath = '../app/resources/js/';
$imgPath = '../app/resources/img/';

if (is_dir($cssPath)) {
    echo "✓ Directorio CSS existe<br>";
    $cssFiles = glob($cssPath . '*.css');
    echo "Archivos CSS encontrados: " . count($cssFiles) . "<br>";
} else {
    echo "✗ Directorio CSS NO existe<br>";
}

if (is_dir($jsPath)) {
    echo "✓ Directorio JS existe<br>";
    $jsFiles = glob($jsPath . '*.js');
    echo "Archivos JS encontrados: " . count($jsFiles) . "<br>";
} else {
    echo "✗ Directorio JS NO existe<br>";
}

if (is_dir($imgPath)) {
    echo "✓ Directorio IMG existe<br>";
    $imgFiles = glob($imgPath . '*.{jpg,jpeg,png,svg,webp}', GLOB_BRACE);
    echo "Archivos de imagen encontrados: " . count($imgFiles) . "<br>";
} else {
    echo "✗ Directorio IMG NO existe<br>";
}

// 5. Simular carga de la página
echo "<h3>5. Simulación de Carga de Página</h3>";
echo "Intentando cargar la página de login...<br>";

// Capturar cualquier salida
ob_start();
try {
    if (file_exists($loginFile)) {
        // Definir constantes necesarias
        if (!defined('ROOT')) {
            define('ROOT', dirname(__DIR__));
        }
        
        // Incluir el archivo
        include $loginFile;
        
        $output = ob_get_contents();
        echo "✓ Página cargada exitosamente<br>";
        echo "Longitud del contenido: " . strlen($output) . " caracteres<br>";
        
        // Verificar si hay errores en el contenido
        if (strpos($output, 'Warning') !== false || strpos($output, 'Error') !== false) {
            echo "⚠ Se detectaron warnings o errores en el contenido<br>";
        }
        
        if (strpos($output, 'url') !== false && strpos($output, '<?php echo url') === false) {
            echo "⚠ Posible problema: 'url' aparece sin ser procesado por PHP<br>";
        }
        
    } else {
        echo "✗ No se pudo cargar la página<br>";
    }
} catch (Exception $e) {
    echo "✗ Error al cargar la página: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "✗ Error fatal al cargar la página: " . $e->getMessage() . "<br>";
}

ob_end_clean();

echo "<h3>6. Recomendaciones</h3>";
echo "Si hay problemas, verifica:<br>";
echo "- Que config.php esté siendo incluido correctamente<br>";
echo "- Que las constantes url, app, rq estén definidas<br>";
echo "- Que no haya espacios o caracteres BOM antes de <?php<br>";
echo "- Que las rutas de los archivos CSS/JS/IMG sean correctas<br>";
echo "- Que el servidor web esté configurado correctamente<br>";

echo "<br><strong>Test completado.</strong>";
?> 