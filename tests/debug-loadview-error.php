<?php
/**
 * Debug específico para identificar el error de loadView
 */

require_once __DIR__ . '/../config.php';

echo "<h1>Debug: Error de loadView</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
    .warning { color: orange; }
    pre { background-color: #f5f5f5; padding: 10px; border-radius: 5px; }
    .test-section { border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 5px; }
</style>";

echo "<div class='test-section'>";
echo "<h2>1. Verificación de archivos JavaScript</h2>";

// Verificar archivos necesarios
$files = [
    'app/resources/js/loadView.js' => 'loadView.js',
    'app/views/layouts/dashFooter.php' => 'dashFooter.php',
    'app/views/root/dashboard.php' => 'root/dashboard.php',
    'app/views/root/rootSidebar.php' => 'root/rootSidebar.php'
];

foreach ($files as $file => $description) {
    if (file_exists(ROOT . '/' . $file)) {
        echo "<div class='success'>✅ $description encontrado</div>";
    } else {
        echo "<div class='error'>❌ $description NO encontrado</div>";
    }
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>2. Verificación de configuración</h2>";
echo "<div class='info'>URL base: " . url . "</div>";
echo "<div class='info'>ROOT: " . ROOT . "</div>";
echo "<div class='info'>app: " . app . "</div>";
echo "<div class='info'>rq: " . rq . "</div>";
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>3. Test de carga de loadView.js</h2>";
echo "<div id='loadViewTest'>Cargando test...</div>";
?>

<script>
// Configurar BASE_URL para el test
const BASE_URL = '<?php echo url; ?>';
console.log('BASE_URL configurada para test:', BASE_URL);

// Función para probar loadView
function testLoadViewFunction() {
    const testDiv = document.getElementById('loadViewTest');
    
    try {
        // Verificar si loadView está disponible
        if (typeof loadView === 'function') {
            testDiv.innerHTML = '<div class="success">✅ Función loadView está disponible</div>';
            console.log('loadView disponible:', loadView);
            
            // Probar una llamada simple
            try {
                loadView('root/menuRoot');
                testDiv.innerHTML += '<div class="success">✅ loadView ejecutado sin errores</div>';
            } catch (callError) {
                testDiv.innerHTML += '<div class="error">❌ Error al ejecutar loadView: ' + callError.message + '</div>';
                console.error('Error ejecutando loadView:', callError);
            }
        } else {
            testDiv.innerHTML = '<div class="error">❌ Función loadView NO está disponible</div>';
            console.error('loadView no disponible:', typeof loadView);
        }
    } catch (error) {
        testDiv.innerHTML = '<div class="error">❌ Error verificando loadView: ' + error.message + '</div>';
        console.error('Error verificando loadView:', error);
    }
}

// Cargar loadView.js dinámicamente
function loadLoadViewScript() {
    const script = document.createElement('script');
    script.src = BASE_URL + 'app/resources/js/loadView.js';
    script.onload = function() {
        console.log('loadView.js cargado exitosamente');
        setTimeout(testLoadViewFunction, 100);
    };
    script.onerror = function() {
        document.getElementById('loadViewTest').innerHTML = '<div class="error">❌ Error cargando loadView.js</div>';
        console.error('Error cargando loadView.js');
    };
    document.head.appendChild(script);
}

// Ejecutar cuando la página esté lista
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, iniciando test...');
    loadLoadViewScript();
});
</script>

<?php
echo "<div class='test-section'>";
echo "<h2>4. Verificación de rutas</h2>";

// Verificar rutas específicas
$routes = [
    'index.php?view=root&action=dashboard' => 'Dashboard Root',
    'index.php?view=root&action=menuRoot' => 'Menu Root',
    'app/resources/js/loadView.js' => 'loadView.js'
];

foreach ($routes as $route => $description) {
    $fullUrl = url . $route;
    echo "<div class='info'>$description: <a href='$fullUrl' target='_blank'>$fullUrl</a></div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>5. Test de navegación</h2>";
echo "<p><a href='index.php?view=root&action=dashboard' target='_blank'>Abrir Dashboard Root</a></p>";
echo "<p><a href='tests/test-loadview-simple.php' target='_blank'>Abrir Test Simple</a></p>";
echo "</div>";
?> 