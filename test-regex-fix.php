<?php
/**
 * Test para verificar que las expresiones regulares funcionan correctamente
 * 
 * Este script verifica:
 * 1. Que las expresiones regulares tienen delimitadores correctos
 * 2. Que detectan patrones peligrosos correctamente
 * 3. Que no hay errores de sintaxis
 */

echo "<h1>Test: Corrección de Expresiones Regulares</h1>";

// Simular la validación de seguridad del index.php
$requestUri = '/app/views/index/login.php';
$path = parse_url($requestUri, PHP_URL_PATH);

echo "<h2>1. URL de prueba:</h2>";
echo "<p><strong>Request URI:</strong> " . htmlspecialchars($requestUri) . "</p>";
echo "<p><strong>Path extraído:</strong> " . htmlspecialchars($path) . "</p>";

// Lista de patrones peligrosos (corregidos)
$dangerousPatterns = [
    '/\/app\//',
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
    '/\.temp/'
];

echo "<h2>2. Patrones peligrosos (corregidos):</h2>";
echo "<ul>";
foreach ($dangerousPatterns as $pattern) {
    echo "<li><code>" . htmlspecialchars($pattern) . "</code></li>";
}
echo "</ul>";

echo "<h2>3. Pruebas de detección:</h2>";

$testPaths = [
    '/app/views/index/login.php' => 'Acceso directo a archivo PHP',
    '/config.php' => 'Acceso a config.php',
    '/app/controllers/SchoolController.php' => 'Acceso a controlador',
    '/app/models/SchoolModel.php' => 'Acceso a modelo',
    '/.env' => 'Acceso a .env',
    '/vendor/composer.json' => 'Acceso a vendor',
    '/node_modules/jquery/jquery.js' => 'Acceso a node_modules',
    '/.git/config' => 'Acceso a .git',
    '/.htaccess' => 'Acceso a .htaccess',
    '/backup.sql' => 'Acceso a archivo SQL',
    '/error.log' => 'Acceso a archivo log',
    '/file.bak' => 'Acceso a archivo backup',
    '/temp.tmp' => 'Acceso a archivo temporal',
    '/school/createSchool' => 'Ruta válida',
    '/coordinator/dashboard' => 'Ruta válida',
    '/index/login' => 'Ruta válida'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Ruta</th><th>Descripción</th><th>Detección</th><th>Estado</th></tr>";

foreach ($testPaths as $testPath => $description) {
    $detected = false;
    $detectedPattern = '';
    
    // Verificar si la URL contiene patrones peligrosos
    foreach ($dangerousPatterns as $pattern) {
        if (preg_match($pattern, $testPath)) {
            $detected = true;
            $detectedPattern = $pattern;
            break;
        }
    }
    
    $status = $detected ? '❌ BLOQUEADA' : '✅ PERMITIDA';
    $detection = $detected ? 'Detectado: ' . $detectedPattern : 'No detectado';
    
    echo "<tr>";
    echo "<td><code>" . htmlspecialchars($testPath) . "</code></td>";
    echo "<td>" . htmlspecialchars($description) . "</td>";
    echo "<td>" . htmlspecialchars($detection) . "</td>";
    echo "<td>" . $status . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>4. Verificación de sintaxis:</h2>";

$syntaxErrors = [];
foreach ($dangerousPatterns as $index => $pattern) {
    // Verificar que la expresión regular es válida
    if (@preg_match($pattern, 'test') === false) {
        $syntaxErrors[] = "Patrón " . ($index + 1) . ": " . $pattern;
    }
}

if (empty($syntaxErrors)) {
    echo "<p>✅ <strong>Todas las expresiones regulares son válidas</strong></p>";
} else {
    echo "<p>❌ <strong>Errores de sintaxis encontrados:</strong></p>";
    echo "<ul>";
    foreach ($syntaxErrors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
}

echo "<h2>5. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/app/views/index/login.php' target='_blank'>Acceso directo a archivo PHP</a> ❌</li>";
echo "<li><a href='http://localhost:8000/config.php' target='_blank'>Acceso a config.php</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/controllers/SchoolController.php' target='_blank'>Acceso a controlador</a> ❌</li>";
echo "<li><a href='http://localhost:8000/?view=school&action=createSchool' target='_blank'>Ruta válida</a> ✅</li>";
echo "<li><a href='http://localhost:8000/?view=coordinator&action=dashboard' target='_blank'>Ruta válida</a> ✅</li>";
echo "</ul>";

echo "<h2>6. Cambios realizados:</h2>";
echo "<ul>";
echo "<li>✅ Agregado delimitadores <code>/</code> a todas las expresiones regulares</li>";
echo "<li>✅ Escapado caracteres especiales con <code>\\</code></li>";
echo "<li>✅ Corregido <code>/app/</code> → <code>/\/app\//</code></li>";
echo "<li>✅ Corregido <code>/config.php</code> → <code>/\/config\.php/</code></li>";
echo "<li>✅ Corregido <code>/\.env</code> → <code>/\.env/</code></li>";
echo "</ul>";

echo "<h2>7. Para verificar que funciona:</h2>";
echo "<ol>";
echo "<li>Recarga cualquier página - no debería haber warnings de preg_match</li>";
echo "<li>Haz clic en las URLs peligrosas - deberían mostrar error 403</li>";
echo "<li>Haz clic en las URLs válidas - deberían funcionar normalmente</li>";
echo "<li>Verifica que no hay errores en el log de PHP</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Estado:</strong> ✅ Expresiones regulares corregidas y funcionales</p>";
?> 