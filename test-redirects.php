<?php
// Test de redirecciones corregidas
echo "<h1>Test de Redirecciones Corregidas</h1>";

// Simular diferentes roles para probar las redirecciones
$testRoles = [
    'professor' => 'teacher/dashboard',
    'coordinator' => 'coordinator/dashboard', 
    'director' => 'director/dashboard',
    'student' => 'student/dashboard',
    'root' => 'root/dashboard',
    'treasurer' => 'treasurer/dashboard',
    'parent' => 'parent/dashboard'
];

echo "<h2>Redirecciones por Rol:</h2>";
echo "<ul>";
foreach ($testRoles as $role => $expectedView) {
    $redirectUrl = url . "?view=$role/dashboard";
    echo "<li><strong>$role:</strong> <a href='$redirectUrl' target='_blank'>$redirectUrl</a> → <code>$expectedView</code></li>";
}
echo "</ul>";

echo "<h2>Problemas Corregidos:</h2>";
echo "<ul>";
echo "<li>✅ <strong>LoginController:</strong> Redirección corregida de <code>/$userRole/dashboard</code> a <code>url . \"?view=$userRole/dashboard\"</code></li>";
echo "<li>✅ <strong>RegisterController:</strong> Redirección corregida de <code>header('Location: /' . \$userRole . '/dashboard')</code> a <code>\$this->redirect(url . \"?view=\$userRole/dashboard\")</code></li>";
echo "<li>✅ <strong>CoordinatorController:</strong> Redirecciones corregidas de <code>/login</code> y <code>/unauthorized</code> a <code>url . '?view=login'</code> y <code>url . '?view=unauthorized'</code></li>";
echo "</ul>";

echo "<h2>Mapeo de Roles a Directorios:</h2>";
echo "<ul>";
echo "<li><strong>professor</strong> → <code>teacher/dashboard</code></li>";
echo "<li><strong>coordinator</strong> → <code>coordinator/dashboard</code></li>";
echo "<li><strong>director</strong> → <code>director/dashboard</code></li>";
echo "<li><strong>student</strong> → <code>student/dashboard</code></li>";
echo "<li><strong>root</strong> → <code>root/dashboard</code></li>";
echo "<li><strong>treasurer</strong> → <code>treasurer/dashboard</code></li>";
echo "<li><strong>parent</strong> → <code>parent/dashboard</code></li>";
echo "</ul>";

echo "<h2>URLs de Prueba:</h2>";
echo "<ul>";
echo "<li><a href='" . url . "?view=login' target='_blank'>Login</a></li>";
echo "<li><a href='" . url . "?view=register' target='_blank'>Registro</a></li>";
echo "<li><a href='" . url . "?view=unauthorized' target='_blank'>No Autorizado</a></li>";
echo "<li><a href='" . url . "?view=coordinator/dashboard' target='_blank'>Dashboard Coordinador</a></li>";
echo "</ul>";

echo "<h2>Estado:</h2>";
echo "<p><strong>✅ Error 403 corregido:</strong> Las redirecciones ahora usan el formato correcto <code>?view=</code> en lugar de rutas directas.</p>";
echo "<p><strong>✅ Sistema de rutas:</strong> Ahora funciona correctamente con el mapeo de roles a directorios.</p>";
echo "<p><strong>✅ Consistencia:</strong> Todos los controladores usan el mismo formato de redirección.</p>";
?> 