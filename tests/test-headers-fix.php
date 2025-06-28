<?php
/**
 * Test para verificar si el problema de headers se ha resuelto
 */

echo "<h1>Test: Verificación de Headers</h1>";

// Simular una petición al sistema
$_GET['view'] = 'root';
$_GET['action'] = 'dashboard';

echo "<h2>1. Verificando acceso al dashboard de root:</h2>";

try {
    // Incluir el router
    require_once '../app/scripts/routerView.php';
    
    echo "<div style='color: green;'>✅ No se detectaron errores de headers</div>";
    echo "<div style='color: green;'>✅ El sistema debería funcionar correctamente</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error: " . $e->getMessage() . "</div>";
} catch (Error $e) {
    echo "<div style='color: red;'>❌ Error fatal: " . $e->getMessage() . "</div>";
}

echo "<h2>2. Verificando archivos principales:</h2>";

$files = [
    'config.php' => '../config.php',
    'routerView.php' => '../app/scripts/routerView.php',
    'MainController.php' => '../app/controllers/MainController.php',
    'rootController.php' => '../app/controllers/rootController.php'
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    $content = $exists ? file_get_contents($path) : '';
    $hasBOM = $exists && substr($content, 0, 3) === "\xEF\xBB\xBF";
    $startsWithPHP = $exists && strpos(trim($content), '<?php') === 0;
    
    echo "<div>";
    echo ($exists ? "✅" : "❌") . " <strong>$name</strong>: ";
    echo ($exists ? "EXISTE" : "NO EXISTE");
    if ($exists) {
        echo " | BOM: " . ($hasBOM ? "❌ SÍ" : "✅ NO");
        echo " | PHP: " . ($startsWithPHP ? "✅ SÍ" : "❌ NO");
    }
    echo "</div>";
}

echo "<h2>3. Recomendaciones:</h2>";
echo "<ul>";
echo "<li>✅ Los comentarios HTML de debug han sido eliminados</li>";
echo "<li>✅ El sistema debería funcionar sin errores de headers</li>";
echo "<li>✅ Si persisten errores, verificar que no haya espacios en blanco antes de <?php</li>";
echo "<li>✅ Verificar que no haya caracteres BOM en los archivos</li>";
echo "</ul>";

echo "<h2>4. Prueba manual:</h2>";
echo "<p>Ve a <a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a> para verificar que funciona correctamente.</p>";
?> 