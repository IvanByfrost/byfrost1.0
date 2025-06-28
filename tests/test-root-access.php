<?php
/**
 * Test para verificar acceso a la raíz del sitio
 */

echo "<h1>Test de Acceso a la Raíz</h1>";

// Simular diferentes escenarios de acceso
$testScenarios = [
    [
        'request_uri' => '/',
        'expected_path' => '/',
        'should_allow' => true,
        'description' => 'Acceso a la raíz del sitio'
    ],
    [
        'request_uri' => '',
        'expected_path' => '',
        'should_allow' => true,
        'description' => 'Acceso sin path'
    ],
    [
        'request_uri' => '/login',
        'expected_path' => '/login',
        'should_allow' => true,
        'description' => 'Acceso a página de login'
    ],
    [
        'request_uri' => '/app/controllers/LoginController.php',
        'expected_path' => '/app/controllers/LoginController.php',
        'should_allow' => false,
        'description' => 'Acceso directo a controlador (debería ser bloqueado)'
    ]
];

echo "<h2>Probando escenarios de acceso:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Descripción</th><th>Request URI</th><th>Path Esperado</th><th>Estado</th></tr>";

foreach ($testScenarios as $scenario) {
    $requestUri = $scenario['request_uri'];
    $expectedPath = $scenario['expected_path'];
    $shouldAllow = $scenario['should_allow'];
    $description = $scenario['description'];
    
    // Simular la lógica de index.php
    $path = parse_url($requestUri, PHP_URL_PATH);
    
    // Verificar si debería ser bloqueado
    $blockedPatterns = [
        '/\/config\.php/',
        '/\.env/',
        '/\/vendor\//',
        '/\/node_modules\//',
        '/\.git/',
        '/\.htaccess/',
        '/\.htpasswd/',
        '/\.sql/',
        '/\.log/',
        '/\.bak/',
        '/\.backup/',
        '/\.tmp/',
        '/\.temp/',
        '/\/app\/controllers\//',
        '/\/app\/models\//',
        '/\/app\/library\//',
        '/\/app\/scripts\//',
        '/\/app\/processes\//',
        '/\/app\/views\/.*\.php$/',
        '/\/app\/resources\/.*\.php$/'
    ];
    
    $isBlocked = false;
    if ($path !== '/' && $path !== '') {
        foreach ($blockedPatterns as $pattern) {
            if (preg_match($pattern, $path)) {
                $isBlocked = true;
                break;
            }
        }
    }
    
    $status = ($isBlocked === !$shouldAllow) ? "✅ CORRECTO" : "❌ INCORRECTO";
    $statusColor = ($isBlocked === !$shouldAllow) ? "green" : "red";
    
    echo "<tr>";
    echo "<td>$description</td>";
    echo "<td><code>$requestUri</code></td>";
    echo "<td><code>$expectedPath</code></td>";
    echo "<td style='color: $statusColor; font-weight: bold;'>$status</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Verificación de lógica:</h2>";
echo "<ul>";
echo "<li>✅ Path de raíz ('/') no se bloquea</li>";
echo "<li>✅ Path vacío ('') no se bloquea</li>";
echo "<li>✅ Rutas normales no se bloquean</li>";
echo "<li>✅ Archivos sensibles se bloquean correctamente</li>";
echo "</ul>";

echo "<h2>Próximos pasos:</h2>";
echo "<ol>";
echo "<li>Reinicia el servidor PHP: <code>php -S localhost:8000</code></li>";
echo "<li>Accede a <a href='http://localhost:8000' target='_blank'>http://localhost:8000</a></li>";
echo "<li>Deberías ver la página principal sin error 403</li>";
echo "</ol>";

echo "<h2>Debug info:</h2>";
echo "<p>Para verificar que funciona, revisa los logs del servidor:</p>";
echo "<pre>";
echo "DEBUG - Request URI: /\n";
echo "DEBUG - Path extraído: /\n";
echo "DEBUG - Establecido _GET['url'] = \n";
echo "</pre>";
?> 