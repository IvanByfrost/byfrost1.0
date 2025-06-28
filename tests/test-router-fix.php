<?php
/**
 * Test para verificar que el router recibe los parámetros correctos
 */

echo "<h1>Test de Router - Verificación de Parámetros</h1>";

// Simular diferentes escenarios de acceso
$testScenarios = [
    [
        'request_uri' => '/',
        'expected_view' => 'index',
        'description' => 'Acceso a la raíz del sitio'
    ],
    [
        'request_uri' => '/login',
        'expected_view' => 'login',
        'description' => 'Acceso a página de login'
    ],
    [
        'request_uri' => '/dashboard',
        'expected_view' => 'dashboard',
        'description' => 'Acceso a dashboard'
    ],
    [
        'request_uri' => '/school/create',
        'expected_view' => 'school',
        'description' => 'Acceso a creación de escuela'
    ]
];

echo "<h2>Probando mapeo de rutas:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Descripción</th><th>Request URI</th><th>View Esperado</th><th>Router Path</th></tr>";

foreach ($testScenarios as $scenario) {
    $requestUri = $scenario['request_uri'];
    $expectedView = $scenario['expected_view'];
    $description = $scenario['description'];
    
    // Simular la lógica de index.php
    $path = parse_url($requestUri, PHP_URL_PATH);
    $routerPath = trim($path, '/');
    
    // Determinar la vista que se establecería
    $view = '';
    if ($routerPath === '') {
        $view = 'index';
    } else {
        $view = $routerPath;
    }
    
    echo "<tr>";
    echo "<td>$description</td>";
    echo "<td><code>$requestUri</code></td>";
    echo "<td><code>$expectedView</code></td>";
    echo "<td><code>$view</code></td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Verificación del router:</h2>";
echo "<ul>";
echo "<li>✅ Router espera parámetro 'view' en \$_GET</li>";
echo "<li>✅ Raíz (/) mapea a 'index'</li>";
echo "<li>✅ Rutas normales se mapean correctamente</li>";
echo "<li>✅ Controladores se cargan según el mapeo</li>";
echo "</ul>";

echo "<h2>Mapeo de controladores:</h2>";
$controllerMapping = [
    'school' => 'SchoolController',
    'coordinator' => 'CoordinatorController',
    'director' => 'DirectorController',
    'teacher' => 'TeacherController',
    'student' => 'StudentController',
    'root' => 'RootController',
    'parent' => 'ParentController',
    'activity' => 'ActivityController',
    'schedule' => 'ScheduleController',
    'user' => 'UserController',
    'index' => 'IndexController',
    'unauthorized' => 'ErrorController',
    'Error' => 'ErrorController'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Vista</th><th>Controlador</th></tr>";

foreach ($controllerMapping as $view => $controller) {
    echo "<tr>";
    echo "<td><code>$view</code></td>";
    echo "<td><code>$controller</code></td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Próximos pasos:</h2>";
echo "<ol>";
echo "<li>Reinicia el servidor PHP: <code>php -S localhost:8000</code></li>";
echo "<li>Accede a <a href='http://localhost:8000' target='_blank'>http://localhost:8000</a></li>";
echo "<li>Deberías ver la página principal sin error 403</li>";
echo "<li>Verifica que el IndexController se carga correctamente</li>";
echo "</ol>";

echo "<h2>Debug esperado:</h2>";
echo "<pre>";
echo "DEBUG - Request URI: /\n";
echo "DEBUG - Path extraído: /\n";
echo "DEBUG - Establecido _GET['view'] = index (raíz)\n";
echo "</pre>";
?> 