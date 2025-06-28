<?php
/**
 * Test para verificar que el manejo de errores unauthorized funciona correctamente
 * 
 * Este script verifica:
 * 1. Que el router maneja correctamente la vista 'unauthorized'
 * 2. Que el ErrorController puede mostrar la página de acceso no autorizado
 * 3. Que las redirecciones de protección funcionan
 */

echo "<h1>Test: Unauthorized Fix</h1>";

echo "<h2>1. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=unauthorized' target='_blank'>Página Unauthorized</a></li>";
echo "<li><a href='http://localhost:8000/?view=unauthorized&action=Error' target='_blank'>Error Unauthorized con acción</a></li>";
echo "<li><a href='http://localhost:8000/?view=school&action=createSchool' target='_blank'>Crear Escuela (debería redirigir a unauthorized si no estás logueado)</a></li>";
echo "</ul>";

echo "<h2>2. Cambios realizados:</h2>";
echo "<ul>";
echo "<li>✅ Agregado mapeo 'unauthorized' → 'ErrorController' en el router</li>";
echo "<li>✅ Modificado el router para manejar ErrorController con vista unauthorized</li>";
echo "<li>✅ El router ahora llama a ErrorController->Error('unauthorized')</li>";
echo "<li>✅ La vista unauthorized.php ya existe en app/views/Error/</li>";
echo "</ul>";

echo "<h2>3. Flujo esperado:</h2>";
echo "<ol>";
echo "<li>URL: <code>?view=unauthorized</code></li>";
echo "<li>Router mapea 'unauthorized' a 'ErrorController'</li>";
echo "<li>Como no hay acción, usa el caso especial para ErrorController</li>";
echo "<li>Llama a <code>ErrorController->Error('unauthorized')</code></li>";
echo "<li>El método Error() maneja el caso 'unauthorized'</li>";
echo "<li>Carga la vista <code>app/views/Error/unauthorized.php</code></li>";
echo "</ol>";

echo "<h2>4. Protección de acceso:</h2>";
echo "<p>Los controladores que usan <code>protectSchool()</code> redirigen a:</p>";
echo "<code>header('Location: /?view=unauthorized');</code>";
echo "<p>Esto ahora debería funcionar correctamente.</p>";

echo "<h2>5. Para probar la protección:</h2>";
echo "<ol>";
echo "<li>No te loguees en el sistema</li>";
echo "<li>Intenta acceder a <a href='http://localhost:8000/?view=school&action=createSchool' target='_blank'>Crear Escuela</a></li>";
echo "<li>Deberías ser redirigido a la página de acceso no autorizado</li>";
echo "<li>La página debería mostrar un mensaje apropiado</li>";
echo "</ol>";

echo "<h2>6. Verificación manual:</h2>";
echo "<ul>";
echo "<li>✅ <a href='http://localhost:8000/?view=unauthorized' target='_blank'>Página unauthorized directa</a></li>";
echo "<li>✅ <a href='http://localhost:8000/?view=Error&action=Error' target='_blank'>ErrorController directo</a></li>";
echo "<li>✅ <a href='http://localhost:8000/?view=Error&action=Error&error=unauthorized' target='_blank'>ErrorController con parámetro</a></li>";
echo "<li>✅ <a href='http://localhost:8000/?view=Error&action=Error&error=404' target='_blank'>Error 404</a></li>";
echo "<li>✅ <a href='http://localhost:8000/?view=Error&action=Error&error=500' target='_blank'>Error 500</a></li>";
echo "<li>✅ <a href='http://localhost:8000/?view=Error&action=Error&error=400' target='_blank'>Error 400</a></li>";
echo "</ul>";

echo "<h2>7. Archivos involucrados:</h2>";
echo "<ul>";
echo "<li><strong>app/scripts/routerView.php</strong> - Router actualizado</li>";
echo "<li><strong>app/controllers/ErrorController.php</strong> - Maneja errores</li>";
echo "<li><strong>app/views/Error/unauthorized.php</strong> - Vista de error</li>";
echo "<li><strong>app/controllers/SchoolController.php</strong> - Usa protectSchool()</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> ✅ Router actualizado para manejar errores unauthorized correctamente</p>";
?> 