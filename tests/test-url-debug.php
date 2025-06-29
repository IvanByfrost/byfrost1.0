<?php
/**
 * Test para verificar las URLs generadas por el sistema
 */

require_once __DIR__ . '/../config.php';

echo "<h1>Test: Debug de URLs</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
    .warning { color: orange; }
    pre { background-color: #f5f5f5; padding: 10px; border-radius: 5px; }
</style>";

echo "<h2>Constantes de configuración:</h2>";
echo "<div class='info'>ROOT: " . ROOT . "</div>";
echo "<div class='info'>url: " . url . "</div>";
echo "<div class='info'>app: " . app . "</div>";

echo "<h2>URLs generadas:</h2>";
echo "<div class='info'>URL base: " . url . "</div>";
echo "<div class='info'>URL del index: " . url . "index.php</div>";
echo "<div class='info'>URL del JS: " . url . app . "resources/js/roleHistory.js</div>";

echo "<h2>Simulación de la URL AJAX:</h2>";
$ajaxUrl = url . "index.php";
echo "<div class='info'>URL AJAX completa: " . $ajaxUrl . "</div>";

echo "<h2>Parámetros de prueba:</h2>";
$testParams = [
    'controller' => 'User',
    'action' => 'showRoleHistory',
    'credential_type' => 'CC',
    'credential_number' => '1031180139',
    'ajax' => '1'
];

echo "<div class='info'>Parámetros: " . http_build_query($testParams) . "</div>";

$fullUrl = $ajaxUrl . "?" . http_build_query($testParams);
echo "<div class='info'>URL completa con parámetros: " . $fullUrl . "</div>";

echo "<h2>Test de acceso directo:</h2>";
echo "<p>Prueba acceder directamente a esta URL en tu navegador:</p>";
echo "<pre>" . htmlspecialchars($fullUrl) . "</pre>";

echo "<h2>Verificación del JavaScript:</h2>";
echo "<p>En la consola del navegador, ejecuta:</p>";
echo "<pre>";
echo "console.log('BYFROST_BASE_URL:', window.BYFROST_BASE_URL);\n";
echo "console.log('URL AJAX:', window.BYFROST_BASE_URL + 'index.php');\n";
echo "console.log('Formulario:', $('#roleHistoryForm').length);\n";
echo "console.log('Campos:', $('#credential_type').length, $('#credential_number').length);\n";
echo "</pre>";

echo "<h2>Debug paso a paso:</h2>";
echo "<ol>";
echo "<li>Abre la página de historial de roles</li>";
echo "<li>Abre la consola (F12)</li>";
echo "<li>Ejecuta los comandos de arriba</li>";
echo "<li>Completa el formulario y haz clic en Buscar</li>";
echo "<li>Ve a la pestaña Network y busca la petición AJAX</li>";
echo "<li>Revisa la URL de la petición</li>";
echo "</ol>";
?> 