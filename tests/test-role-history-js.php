<?php
/**
 * Test para verificar que el JavaScript del historial de roles se carga correctamente
 */

require_once __DIR__ . '/../config.php';

echo "<h1>Test: JavaScript del Historial de Roles</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
    .warning { color: orange; }
    pre { background-color: #f5f5f5; padding: 10px; border-radius: 5px; }
</style>";

// Verificar que el archivo roleHistory.js existe
$jsFile = ROOT . '/app/resources/js/roleHistory.js';
if (file_exists($jsFile)) {
    echo "<div class='success'>✅ Archivo roleHistory.js encontrado</div>";
    
    // Leer el contenido del archivo
    $jsContent = file_get_contents($jsFile);
    
    // Verificar que contiene el código necesario
    $checks = [
        'roleHistoryForm' => 'ID del formulario',
        'searchResultsCard' => 'ID del contenedor de resultados',
        'searchResultsContainer' => 'ID del contenedor interno',
        'BYFROST_BASE_URL' => 'Variable global de URL',
        'controller: \'User\'' => 'Controlador correcto',
        'action: \'showRoleHistory\'' => 'Acción correcta',
        'ajax: 1' => 'Parámetro AJAX'
    ];
    
    echo "<h2>Verificaciones del JavaScript:</h2>";
    foreach ($checks as $check => $description) {
        if (strpos($jsContent, $check) !== false) {
            echo "<div class='success'>✅ $description encontrado</div>";
        } else {
            echo "<div class='error'>❌ $description NO encontrado</div>";
        }
    }
    
    // Mostrar el contenido del archivo
    echo "<h2>Contenido del archivo roleHistory.js:</h2>";
    echo "<pre>" . htmlspecialchars($jsContent) . "</pre>";
    
} else {
    echo "<div class='error'>❌ Archivo roleHistory.js NO encontrado en: $jsFile</div>";
}

// Verificar que el dashFooter incluye el archivo
$dashFooterFile = ROOT . '/app/views/layouts/dashFooter.php';
if (file_exists($dashFooterFile)) {
    echo "<h2>Verificación del dashFooter:</h2>";
    $footerContent = file_get_contents($dashFooterFile);
    
    if (strpos($footerContent, 'roleHistory.js') !== false) {
        echo "<div class='success'>✅ roleHistory.js está incluido en dashFooter.php</div>";
    } else {
        echo "<div class='error'>❌ roleHistory.js NO está incluido en dashFooter.php</div>";
    }
    
    if (strpos($footerContent, 'BYFROST_BASE_URL') !== false) {
        echo "<div class='success'>✅ Variable BYFROST_BASE_URL está definida en dashFooter.php</div>";
    } else {
        echo "<div class='error'>❌ Variable BYFROST_BASE_URL NO está definida en dashFooter.php</div>";
    }
} else {
    echo "<div class='error'>❌ Archivo dashFooter.php NO encontrado</div>";
}

echo "<hr>";
echo "<h2>Instrucciones para probar:</h2>";
echo "<ol>";
echo "<li>Abre la página de historial de roles en el navegador</li>";
echo "<li>Abre la consola del navegador (F12)</li>";
echo "<li>Verifica que no hay errores de JavaScript</li>";
echo "<li>Completa el formulario y haz clic en 'Buscar'</li>";
echo "<li>En la pestaña 'Red' (Network) verifica que se hace la petición AJAX</li>";
echo "</ol>";

echo "<h2>Debug en consola:</h2>";
echo "<p>Agrega esto en la consola del navegador para verificar:</p>";
echo "<pre>";
echo "console.log('Formulario encontrado:', $('#roleHistoryForm').length);\n";
echo "console.log('URL base:', window.BYFROST_BASE_URL);\n";
echo "console.log('jQuery cargado:', typeof $ !== 'undefined');\n";
echo "</pre>";
?> 