<?php
/**
 * Test simple para verificar que el bloqueo está funcionando AHORA
 */

echo "<h1>Test: Verificación de Bloqueo</h1>";

echo "<h2>1. Prueba directa:</h2>";
echo "<p>Haz clic en este enlace para verificar si está bloqueado:</p>";
echo "<a href='http://localhost:8000/app/views/index/login.php' target='_blank' style='color: red; font-weight: bold;'>🔒 PROBAR BLOQUEO: app/views/index/login.php</a>";

echo "<h2>2. Resultado esperado:</h2>";
echo "<ul>";
echo "<li>✅ Si está bloqueado: Verás 'Acceso Denegado' con código 403</li>";
echo "<li>❌ Si NO está bloqueado: Verás el contenido del archivo PHP</li>";
echo "</ul>";

echo "<h2>3. Otras URLs para probar:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/config.php' target='_blank'>config.php</a></li>";
echo "<li><a href='http://localhost:8000/app/controllers/SchoolController.php' target='_blank'>SchoolController.php</a></li>";
echo "<li><a href='http://localhost:8000/.env' target='_blank'>.env</a></li>";
echo "<li><a href='http://localhost:8000/test-blocking-now.php' target='_blank'>Este mismo archivo</a></li>";
echo "</ul>";

echo "<h2>4. URLs que SÍ deberían funcionar:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/' target='_blank'>Página principal</a></li>";
echo "<li><a href='http://localhost:8000/?view=school&action=createSchool' target='_blank'>Crear Escuela</a></li>";
echo "</ul>";

echo "<h2>5. Instrucciones:</h2>";
echo "<ol>";
echo "<li>Haz clic en el primer enlace (app/views/index/login.php)</li>";
echo "<li>Si ves 'Acceso Denegado' → ✅ El bloqueo funciona</li>";
echo "<li>Si ves código PHP → ❌ El bloqueo NO funciona</li>";
echo "<li>Repite con las otras URLs</li>";
echo "</ol>";

echo "<h2>6. Si el bloqueo NO funciona:</h2>";
echo "<ul>";
echo "<li>Verifica que el servidor esté corriendo: <code>php -S localhost:8000</code></li>";
echo "<li>Verifica que index.php se esté ejecutando</li>";
echo "<li>Revisa los logs de error de PHP</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Verificando bloqueo...</p>";
?> 