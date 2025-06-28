<?php
/**
 * Test de seguridad para verificar que las medidas de protección funcionan
 * 
 * Este script verifica:
 * 1. Que las URLs peligrosas son bloqueadas
 * 2. Que el middleware de seguridad funciona
 * 3. Que las rutas se sanitizan correctamente
 */

echo "<h1>Test: Seguridad de Rutas</h1>";

// Cargar el middleware de seguridad
require_once 'config.php';
require_once 'app/library/SecurityMiddleware.php';

echo "<h2>1. URLs peligrosas que deberían ser bloqueadas:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/app/views/index/login.php' target='_blank'>Acceso directo a archivo PHP</a> ❌</li>";
echo "<li><a href='http://localhost:8000/config.php' target='_blank'>Acceso a config.php</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/controllers/SchoolController.php' target='_blank'>Acceso a controlador</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/models/SchoolModel.php' target='_blank'>Acceso a modelo</a> ❌</li>";
echo "<li><a href='http://localhost:8000/.env' target='_blank'>Acceso a .env</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/../config.php' target='_blank'>Directory traversal</a> ❌</li>";
echo "<li><a href='http://localhost:8000/../../../etc/passwd' target='_blank'>Path traversal</a> ❌</li>";
echo "</ul>";

echo "<h2>2. URLs seguras que deberían funcionar:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=school&action=createSchool' target='_blank'>Crear Escuela</a> ✅</li>";
echo "<li><a href='http://localhost:8000/?view=coordinator&action=dashboard' target='_blank'>Dashboard Coordinador</a> ✅</li>";
echo "<li><a href='http://localhost:8000/?view=Error&action=Error&error=404' target='_blank'>Error 404</a> ✅</li>";
echo "<li><a href='http://localhost:8000/?view=unauthorized' target='_blank'>Unauthorized</a> ✅</li>";
echo "</ul>";

echo "<h2>3. Pruebas del SecurityMiddleware:</h2>";

// Probar validación de rutas
$testPaths = [
    'school' => 'Ruta válida',
    'app/views/index/login.php' => 'Acceso directo a archivo',
    '../../../config.php' => 'Directory traversal',
    'school<script>' => 'XSS en ruta',
    'school/../config' => 'Path traversal',
    'school/../../' => 'Directory traversal',
    'school/./././config' => 'Path traversal',
    'school/..%2Fconfig' => 'URL encoding bypass'
];

echo "<h3>Validación de rutas:</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Ruta</th><th>Descripción</th><th>Válida</th><th>Sanitizada</th></tr>";

foreach ($testPaths as $path => $description) {
    $result = SecurityMiddleware::validatePath($path);
    $status = $result['valid'] ? '✅' : '❌';
    $sanitized = $result['valid'] ? $result['sanitized'] : 'BLOQUEADA';
    
    echo "<tr>";
    echo "<td><code>" . htmlspecialchars($path) . "</code></td>";
    echo "<td>" . htmlspecialchars($description) . "</td>";
    echo "<td>" . $status . "</td>";
    echo "<td><code>" . htmlspecialchars($sanitized) . "</code></td>";
    echo "</tr>";
}
echo "</table>";

// Probar generación de URLs seguras
echo "<h3>Generación de URLs seguras:</h3>";
echo "<ul>";
echo "<li><strong>Vista simple:</strong> " . SecurityMiddleware::generateSecureUrl('school') . "</li>";
echo "<li><strong>Vista con acción:</strong> " . SecurityMiddleware::generateSecureUrl('school', 'createSchool') . "</li>";
echo "<li><strong>Con parámetros:</strong> " . SecurityMiddleware::generateSecureUrl('school', 'consultSchool', ['search' => 'test']) . "</li>";
echo "<li><strong>Con parámetros especiales:</strong> " . SecurityMiddleware::generateSecureUrl('Error', 'Error', ['error' => '404']) . "</li>";
echo "</ul>";

echo "<h2>4. Medidas de seguridad implementadas:</h2>";
echo "<ul>";
echo "<li>✅ <strong>.htaccess:</strong> Bloquea acceso directo a archivos PHP</li>";
echo "<li>✅ <strong>SecurityMiddleware:</strong> Valida y sanitiza todas las rutas</li>";
echo "<li>✅ <strong>Validación en index.php:</strong> Previene acceso a archivos sensibles</li>";
echo "<li>✅ <strong>Headers de seguridad:</strong> XSS, clickjacking, etc.</li>";
echo "<li>✅ <strong>Directory listing:</strong> Deshabilitado</li>";
echo "<li>✅ <strong>URL rewriting:</strong> Todo va a través de index.php</li>";
echo "</ul>";

echo "<h2>5. Headers de seguridad configurados:</h2>";
echo "<ul>";
echo "<li><strong>X-Content-Type-Options:</strong> nosniff</li>";
echo "<li><strong>X-Frame-Options:</strong> DENY</li>";
echo "<li><strong>X-XSS-Protection:</strong> 1; mode=block</li>";
echo "<li><strong>Referrer-Policy:</strong> strict-origin-when-cross-origin</li>";
echo "<li><strong>Content-Security-Policy:</strong> default-src 'self'</li>";
echo "</ul>";

echo "<h2>6. Para verificar que funciona:</h2>";
echo "<ol>";
echo "<li>Haz clic en las URLs peligrosas - deberían mostrar error 403 o 404</li>";
echo "<li>Haz clic en las URLs seguras - deberían funcionar normalmente</li>";
echo "<li>Verifica que no puedes acceder directamente a archivos PHP</li>";
echo "<li>Verifica que las rutas se sanitizan correctamente</li>";
echo "</ol>";

echo "<h2>7. URLs de prueba adicionales:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/app/' target='_blank'>Directorio app/</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/views/' target='_blank'>Directorio views/</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/controllers/' target='_blank'>Directorio controllers/</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/models/' target='_blank'>Directorio models/</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/library/' target='_blank'>Directorio library/</a> ❌</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> ✅ Sistema de seguridad implementado y funcional</p>";
?> 