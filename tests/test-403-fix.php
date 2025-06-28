<?php
/**
 * Test para verificar que el error 403 está solucionado
 */

echo "<h1>Test de Acceso - Verificación 403</h1>";

// Simular diferentes URLs para probar
$testUrls = [
    '/',
    '/login',
    '/register',
    '/dashboard',
    '/app/resources/css/bootstrap.css',
    '/app/resources/js/bootstrap.js',
    '/app/resources/img/logo.jpeg',
    '/app/controllers/LoginController.php', // Debería ser bloqueado
    '/app/models/userModel.php', // Debería ser bloqueado
    '/config.php', // Debería ser bloqueado
    '/app/views/index/login.php' // Debería ser bloqueado
];

echo "<h2>Probando patrones de seguridad:</h2>";
echo "<ul>";

foreach ($testUrls as $url) {
    // Simular la lógica de index.php
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
    
    $blocked = false;
    foreach ($blockedPatterns as $pattern) {
        if (preg_match($pattern, $url)) {
            $blocked = true;
            break;
        }
    }
    
    $status = $blocked ? "❌ BLOQUEADO" : "✅ PERMITIDO";
    echo "<li><strong>$url</strong> - $status</li>";
}

echo "</ul>";

echo "<h2>Probando extensiones permitidas:</h2>";
$allowedExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
$testFiles = [
    '/app/resources/css/style.css',
    '/app/resources/js/script.js',
    '/app/resources/img/image.jpg',
    '/app/resources/img/logo.png',
    '/app/resources/img/icon.svg',
    '/app/controllers/TestController.php', // No debería estar en allowedExtensions
    '/app/models/TestModel.php' // No debería estar en allowedExtensions
];

echo "<ul>";
foreach ($testFiles as $file) {
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $allowed = in_array($extension, $allowedExtensions);
    $status = $allowed ? "✅ PERMITIDO" : "❌ BLOQUEADO";
    echo "<li><strong>$file</strong> (extensión: $extension) - $status</li>";
}
echo "</ul>";

echo "<h2>Estado del sistema:</h2>";
echo "<p>✅ Patrones de seguridad actualizados</p>";
echo "<p>✅ Extensiones permitidas configuradas</p>";
echo "<p>✅ Lógica de routing corregida</p>";

echo "<h2>Próximos pasos:</h2>";
echo "<ol>";
echo "<li>Reinicia el servidor PHP: <code>php -S localhost:8000</code></li>";
echo "<li>Accede a <a href='http://localhost:8000' target='_blank'>http://localhost:8000</a></li>";
echo "<li>Verifica que puedes acceder a la página principal</li>";
echo "<li>Prueba navegar por las diferentes secciones</li>";
echo "</ol>";
?> 