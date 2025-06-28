<?php
/**
 * Test para verificar el acceso correcto a la funcionalidad de asignación de roles
 */

echo "<h1>Test: Acceso a Asignación de Roles</h1>";

echo "<h2>1. URLs Correctas:</h2>";
echo "<ul>";
echo "<li><strong>Página principal:</strong> <a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>http://localhost:8000/?view=user&action=assignRole</a></li>";
echo "<li><strong>Búsqueda específica:</strong> <a href='http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139' target='_blank'>http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139</a></li>";
echo "<li><strong>Dashboard root:</strong> <a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>http://localhost:8000/?view=root&action=dashboard</a></li>";
echo "</ul>";

echo "<h2>2. URLs Incorrectas (Bloqueadas):</h2>";
echo "<ul>";
echo "<li><strong>Acceso directo a vista:</strong> <a href='http://localhost:8000/app/views/user/assignRole.php' target='_blank'>http://localhost:8000/app/views/user/assignRole.php</a> ❌ Debería estar bloqueado</li>";
echo "<li><strong>Acceso directo a controlador:</strong> <a href='http://localhost:8000/app/controllers/UserController.php' target='_blank'>http://localhost:8000/app/controllers/UserController.php</a> ❌ Debería estar bloqueado</li>";
echo "</ul>";

echo "<h2>3. Verificación de Archivos:</h2>";

$files = [
    'UserController.php' => '../app/controllers/UserController.php',
    'assignRole.php' => '../app/views/user/assignRole.php',
    'assignRole.js' => '../app/resources/js/assignRole.js',
    'assignProcess.php' => '../app/processes/assignProcess.php'
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$name</strong>: " . ($exists ? "EXISTE" : "NO EXISTE") . "</div>";
}

echo "<h2>4. Instrucciones:</h2>";
echo "<ol>";
echo "<li>Primero, asegúrate de estar logueado como usuario con rol 'root'</li>";
echo "<li>Ve a <a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a></li>";
echo "<li>Una vez logueado, accede a la funcionalidad usando las URLs correctas</li>";
echo "<li>Prueba la búsqueda de usuarios y la asignación de roles</li>";
echo "</ol>";

echo "<h2>5. Flujo de Prueba:</h2>";
echo "<ol>";
echo "<li>Login como root</li>";
echo "<li>Ir a asignación de roles</li>";
echo "<li>Buscar usuario por documento</li>";
echo "<li>Asignar rol desde el modal</li>";
echo "<li>Verificar que el rol se asigne correctamente</li>";
echo "</ol>";
?> 