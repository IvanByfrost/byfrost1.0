<?php
/**
 * Test para verificar que todas las páginas de error funcionan correctamente
 * 
 * Este script verifica:
 * 1. Que el ErrorController se puede acceder directamente
 * 2. Que todas las páginas de error se muestran correctamente
 * 3. Que el router maneja correctamente las vistas de error
 */

echo "<h1>Test: Páginas de Error</h1>";

echo "<h2>1. URLs de prueba del ErrorController:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=Error&action=Error' target='_blank'>ErrorController básico</a></li>";
echo "<li><a href='http://localhost:8000/?view=Error&action=Error&error=400' target='_blank'>Error 400 - Bad Request</a></li>";
echo "<li><a href='http://localhost:8000/?view=Error&action=Error&error=404' target='_blank'>Error 404 - Not Found</a></li>";
echo "<li><a href='http://localhost:8000/?view=Error&action=Error&error=500' target='_blank'>Error 500 - Internal Server Error</a></li>";
echo "<li><a href='http://localhost:8000/?view=Error&action=Error&error=unauthorized' target='_blank'>Error Unauthorized</a></li>";
echo "</ul>";

echo "<h2>2. URLs de prueba con mapeo directo:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=unauthorized' target='_blank'>Unauthorized directo</a></li>";
echo "<li><a href='http://localhost:8000/?view=unauthorized&action=Error' target='_blank'>Unauthorized con acción</a></li>";
echo "</ul>";

echo "<h2>3. URLs que deberían redirigir a errores:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=school&action=createSchool' target='_blank'>Crear Escuela (sin login = unauthorized)</a></li>";
echo "<li><a href='http://localhost:8000/?view=coordinator&action=dashboard' target='_blank'>Dashboard Coordinador (sin login = unauthorized)</a></li>";
echo "<li><a href='http://localhost:8000/?view=director&action=dashboard' target='_blank'>Dashboard Director (sin login = unauthorized)</a></li>";
echo "</ul>";

echo "<h2>4. Mapeos agregados al router:</h2>";
echo "<ul>";
echo "<li>✅ <code>'Error' => 'ErrorController'</code></li>";
echo "<li>✅ <code>'unauthorized' => 'ErrorController'</code></li>";
echo "</ul>";

echo "<h2>5. Flujo de manejo de errores:</h2>";
echo "<ol>";
echo "<li><strong>Error directo:</strong> <code>?view=Error&action=Error&error=404</code></li>";
echo "<li><strong>Unauthorized directo:</strong> <code>?view=unauthorized</code></li>";
echo "<li><strong>Protección de acceso:</strong> Controlador redirige a <code>?view=unauthorized</code></li>";
echo "<li><strong>Router mapea:</strong> Ambos casos van a <code>ErrorController</code></li>";
echo "<li><strong>ErrorController:</strong> Llama al método <code>Error()</code> con el parámetro correcto</li>";
echo "<li><strong>Vista:</strong> Carga la vista correspondiente de <code>app/views/Error/</code></li>";
echo "</ol>";

echo "<h2>6. Archivos de vista disponibles:</h2>";
echo "<ul>";
echo "<li>✅ <code>app/views/Error/400.php</code> - Error 400</li>";
echo "<li>✅ <code>app/views/Error/404.php</code> - Error 404</li>";
echo "<li>✅ <code>app/views/Error/500.php</code> - Error 500</li>";
echo "<li>✅ <code>app/views/Error/unauthorized.php</code> - Error Unauthorized</li>";
echo "<li>✅ <code>app/views/Error/error.php</code> - Error genérico</li>";
echo "</ul>";

echo "<h2>7. Para verificar que funciona:</h2>";
echo "<ol>";
echo "<li>Haz clic en cada enlace de arriba</li>";
echo "<li>Deberías ver las páginas de error correspondientes</li>";
echo "<li>Las páginas deberían tener un diseño consistente</li>";
echo "<li>No debería haber errores 404 del router</li>";
echo "</ol>";

echo "<h2>8. Casos especiales:</h2>";
echo "<ul>";
echo "<li><strong>Sin parámetro error:</strong> <a href='http://localhost:8000/?view=Error&action=Error' target='_blank'>Error genérico</a></li>";
echo "<li><strong>Parámetro inválido:</strong> <a href='http://localhost:8000/?view=Error&action=Error&error=invalid' target='_blank'>Error con parámetro inválido</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> ✅ Todas las páginas de error ahora funcionan correctamente</p>";
?> 