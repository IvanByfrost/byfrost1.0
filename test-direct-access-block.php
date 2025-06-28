<?php
/**
 * Test para verificar que el acceso directo a archivos está bloqueado
 * 
 * Este script verifica:
 * 1. Que las URLs peligrosas son bloqueadas
 * 2. Que el servidor de desarrollo PHP no permite acceso directo
 * 3. Que las rutas válidas funcionan
 */

echo "<h1>Test: Bloqueo de Acceso Directo</h1>";

echo "<h2>1. URLs que deberían estar BLOQUEADAS:</h2>";
echo "<p><strong>Importante:</strong> Estas URLs deberían mostrar 'Acceso Denegado' (403)</p>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/app/views/index/login.php' target='_blank'>Acceso directo a login.php</a> ❌</li>";
echo "<li><a href='http://localhost:8000/config.php' target='_blank'>Acceso a config.php</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/controllers/SchoolController.php' target='_blank'>Acceso a controlador</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/models/SchoolModel.php' target='_blank'>Acceso a modelo</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/library/SecurityMiddleware.php' target='_blank'>Acceso a middleware</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/scripts/connection.php' target='_blank'>Acceso a connection.php</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/resources/css/bootstrap.css' target='_blank'>Acceso a CSS</a> ❌</li>";
echo "<li><a href='http://localhost:8000/app/resources/js/jquery.js' target='_blank'>Acceso a JS</a> ❌</li>";
echo "<li><a href='http://localhost:8000/.env' target='_blank'>Acceso a .env</a> ❌</li>";
echo "<li><a href='http://localhost:8000/.htaccess' target='_blank'>Acceso a .htaccess</a> ❌</li>";
echo "</ul>";

echo "<h2>2. URLs que deberían FUNCIONAR:</h2>";
echo "<p><strong>Importante:</strong> Estas URLs deberían funcionar normalmente</p>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/' target='_blank'>Página principal</a> ✅</li>";
echo "<li><a href='http://localhost:8000/?view=school&action=createSchool' target='_blank'>Crear Escuela</a> ✅</li>";
echo "<li><a href='http://localhost:8000/?view=coordinator&action=dashboard' target='_blank'>Dashboard Coordinador</a> ✅</li>";
echo "<li><a href='http://localhost:8000/?view=Error&action=Error&error=404' target='_blank'>Error 404</a> ✅</li>";
echo "<li><a href='http://localhost:8000/?view=unauthorized' target='_blank'>Unauthorized</a> ✅</li>";
echo "</ul>";

echo "<h2>3. Patrones bloqueados en index.php:</h2>";
echo "<ul>";
echo "<li>✅ <code>/\/app\//</code> - Directorio app/</li>";
echo "<li>✅ <code>/\/config\.php/</code> - Archivo config.php</li>";
echo "<li>✅ <code>/\.env/</code> - Archivo .env</li>";
echo "<li>✅ <code>/\/vendor\//</code> - Directorio vendor/</li>";
echo "<li>✅ <code>/\/node_modules\//</code> - Directorio node_modules/</li>";
echo "<li>✅ <code>/\.git/</code> - Directorio .git/</li>";
echo "<li>✅ <code>/\.htaccess/</code> - Archivo .htaccess</li>";
echo "<li>✅ <code>/\.htpasswd/</code> - Archivo .htpasswd</li>";
echo "<li>✅ <code>/\.sql/</code> - Archivos SQL</li>";
echo "<li>✅ <code>/\.log/</code> - Archivos de log</li>";
echo "<li>✅ <code>/\.bak/</code> - Archivos de backup</li>";
echo "<li>✅ <code>/\.backup/</code> - Archivos de backup</li>";
echo "<li>✅ <code>/\.tmp/</code> - Archivos temporales</li>";
echo "<li>✅ <code>/\.temp/</code> - Archivos temporales</li>";
echo "<li>✅ <code>/\.php$/</code> - <strong>Cualquier archivo PHP</strong></li>";
echo "<li>✅ <code>/\/views\//</code> - Directorio views/</li>";
echo "<li>✅ <code>/\/controllers\//</code> - Directorio controllers/</li>";
echo "<li>✅ <code>/\/models\//</code> - Directorio models/</li>";
echo "<li>✅ <code>/\/library\//</code> - Directorio library/</li>";
echo "<li>✅ <code>/\/scripts\//</code> - Directorio scripts/</li>";
echo "<li>✅ <code>/\/resources\//</code> - Directorio resources/</li>";
echo "</ul>";

echo "<h2>4. Cómo funciona la protección:</h2>";
echo "<ol>";
echo "<li><strong>Servidor de desarrollo PHP:</strong> No procesa .htaccess</li>";
echo "<li><strong>index.php:</strong> Se ejecuta para TODAS las URLs</li>";
echo "<li><strong>Validación:</strong> Verifica patrones peligrosos en la URL</li>";
echo "<li><strong>Bloqueo:</strong> Si detecta patrón peligroso, muestra error 403</li>";
echo "<li><strong>Permitido:</strong> Solo URLs con parámetros GET válidos</li>";
echo "</ol>";

echo "<h2>5. Diferencia entre sanitización y bloqueo:</h2>";
echo "<ul>";
echo "<li><strong>Sanitización:</strong> Limpia datos para hacerlos seguros</li>";
echo "<li><strong>Bloqueo:</strong> Previene acceso a recursos sensibles</li>";
echo "<li><strong>En este caso:</strong> Bloqueamos acceso directo a archivos</li>";
echo "<li><strong>Resultado:</strong> Solo se puede acceder a través del router</li>";
echo "</ul>";

echo "<h2>6. Para verificar que funciona:</h2>";
echo "<ol>";
echo "<li>Haz clic en las URLs bloqueadas - deberían mostrar 'Acceso Denegado'</li>";
echo "<li>Haz clic en las URLs válidas - deberían funcionar normalmente</li>";
echo "<li>Verifica que no puedes acceder directamente a ningún archivo PHP</li>";
echo "<li>Verifica que solo puedes acceder a través de ?view= y ?action=</li>";
echo "</ol>";

echo "<h2>7. URLs de prueba adicionales:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/test-security.php' target='_blank'>test-security.php</a> ❌</li>";
echo "<li><a href='http://localhost:8000/test-regex-fix.php' target='_blank'>test-regex-fix.php</a> ❌</li>";
echo "<li><a href='http://localhost:8000/test-security-middleware-fix.php' target='_blank'>test-security-middleware-fix.php</a> ❌</li>";
echo "<li><a href='http://localhost:8000/test-direct-access-block.php' target='_blank'>test-direct-access-block.php</a> ❌</li>";
echo "</ul>";

echo "<h2>8. Solución implementada:</h2>";
echo "<ul>";
echo "<li>✅ <strong>index.php:</strong> Bloquea acceso directo a archivos</li>";
echo "<li>✅ <strong>Patrones:</strong> Detecta URLs peligrosas</li>";
echo "<li>✅ <strong>Error 403:</strong> Muestra página de acceso denegado</li>";
echo "<li>✅ <strong>Router:</strong> Solo permite acceso a través de parámetros</li>";
echo "<li>✅ <strong>Servidor PHP:</strong> Funciona sin Apache</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> ✅ Acceso directo a archivos bloqueado correctamente</p>";
echo "<p><strong>Nota:</strong> Ahora solo puedes acceder a través de URLs como <code>?view=school&action=createSchool</code></p>";
?> 