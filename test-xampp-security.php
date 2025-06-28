<?php
/**
 * Test de seguridad para XAMPP
 * 
 * Este script verifica que las medidas de seguridad funcionan en Apache/XAMPP
 */

echo "<h1>Test: Seguridad en XAMPP</h1>";

echo "<h2>1. URLs que deberían estar BLOQUEADAS (403 Forbidden):</h2>";
echo "<p><strong>Importante:</strong> Estas URLs deberían mostrar '403 Forbidden'</p>";
echo "<ul>";
echo "<li><a href='http://localhost/byfrost/app/views/index/login.php' target='_blank'>app/views/index/login.php</a> ❌</li>";
echo "<li><a href='http://localhost/byfrost/config.php' target='_blank'>config.php</a> ❌</li>";
echo "<li><a href='http://localhost/byfrost/app/controllers/SchoolController.php' target='_blank'>SchoolController.php</a> ❌</li>";
echo "<li><a href='http://localhost/byfrost/app/models/SchoolModel.php' target='_blank'>SchoolModel.php</a> ❌</li>";
echo "<li><a href='http://localhost/byfrost/app/library/SecurityMiddleware.php' target='_blank'>SecurityMiddleware.php</a> ❌</li>";
echo "<li><a href='http://localhost/byfrost/.env' target='_blank'>.env</a> ❌</li>";
echo "<li><a href='http://localhost/byfrost/.htaccess' target='_blank'>.htaccess</a> ❌</li>";
echo "</ul>";

echo "<h2>2. URLs que deberían FUNCIONAR:</h2>";
echo "<p><strong>Importante:</strong> Estas URLs deberían funcionar normalmente</p>";
echo "<ul>";
echo "<li><a href='http://localhost/byfrost/' target='_blank'>Página principal</a> ✅</li>";
echo "<li><a href='http://localhost/byfrost/?view=school&action=createSchool' target='_blank'>Crear Escuela</a> ✅</li>";
echo "<li><a href='http://localhost/byfrost/?view=coordinator&action=dashboard' target='_blank'>Dashboard Coordinador</a> ✅</li>";
echo "<li><a href='http://localhost/byfrost/?view=Error&action=Error&error=404' target='_blank'>Error 404</a> ✅</li>";
echo "<li><a href='http://localhost/byfrost/?view=unauthorized' target='_blank'>Unauthorized</a> ✅</li>";
echo "</ul>";

echo "<h2>3. Reglas de .htaccess implementadas:</h2>";
echo "<ul>";
echo "<li>✅ <code>RewriteBase /byfrost/</code> - Base del directorio</li>";
echo "<li>✅ <code>RewriteRule ^(app/|config\.php|\.env|\.htaccess|\.htpasswd|\.sql|\.log|\.bak|\.backup|\.tmp|\.temp) - [F,L]</code> - Bloquea archivos sensibles</li>";
echo "<li>✅ <code>RewriteRule ^(app/.*\.php)$ - [F,L]</code> - Bloquea archivos PHP en app/</li>";
echo "<li>✅ <code>RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]</code> - Redirige todo a index.php</li>";
echo "<li>✅ <code>Options -Indexes</code> - Deshabilita listado de directorios</li>";
echo "<li>✅ Headers de seguridad - XSS, clickjacking, etc.</li>";
echo "</ul>";

echo "<h2>4. Cómo funciona en XAMPP:</h2>";
echo "<ol>";
echo "<li><strong>Apache:</strong> Procesa el archivo .htaccess</li>";
echo "<li><strong>RewriteRules:</strong> Bloquean acceso directo a archivos sensibles</li>";
echo "<li><strong>index.php:</strong> Recibe todas las URLs válidas</li>";
echo "<li><strong>Router:</strong> Procesa las rutas con parámetros GET</li>";
echo "</ol>";

echo "<h2>5. Para verificar que funciona:</h2>";
echo "<ol>";
echo "<li>Haz clic en las URLs bloqueadas - deberían mostrar '403 Forbidden'</li>";
echo "<li>Haz clic en las URLs válidas - deberían funcionar normalmente</li>";
echo "<li>Verifica que no puedes acceder directamente a ningún archivo en app/</li>";
echo "<li>Verifica que solo puedes acceder a través de ?view= y ?action=</li>";
echo "</ol>";

echo "<h2>6. Si hay problemas:</h2>";
echo "<ul>";
echo "<li><strong>Error 500:</strong> Verifica que mod_rewrite esté habilitado en Apache</li>";
echo "<li><strong>Error 404:</strong> Verifica que la ruta /byfrost/ sea correcta</li>";
echo "<li><strong>Acceso directo:</strong> Verifica que .htaccess esté siendo leído</li>";
echo "<li><strong>Logs:</strong> Revisa los logs de error de Apache</li>";
echo "</ul>";

echo "<h2>7. Verificación de mod_rewrite:</h2>";
echo "<p>Para verificar que mod_rewrite está habilitado:</p>";
echo "<ol>";
echo "<li>Abre XAMPP Control Panel</li>";
echo "<li>Haz clic en 'Config' de Apache</li>";
echo "<li>Selecciona 'httpd.conf'</li>";
echo "<li>Busca la línea: <code>LoadModule rewrite_module modules/mod_rewrite.so</code></li>";
echo "<li>Si está comentada (con #), descoméntala</li>";
echo "<li>Reinicia Apache</li>";
echo "</ol>";

echo "<h2>8. URLs de prueba adicionales:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost/byfrost/test-xampp-security.php' target='_blank'>Este archivo de prueba</a> ❌</li>";
echo "<li><a href='http://localhost/byfrost/test-security.php' target='_blank'>test-security.php</a> ❌</li>";
echo "<li><a href='http://localhost/byfrost/test-regex-fix.php' target='_blank'>test-regex-fix.php</a> ❌</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Verificando seguridad en XAMPP...</p>";
echo "<p><strong>URL base:</strong> <code>http://localhost/byfrost/</code></p>";
?> 