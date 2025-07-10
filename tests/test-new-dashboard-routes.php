<?php
// Script para probar todas las nuevas rutas de dashboard
require_once '../config.php';

echo "<h1>🧪 Prueba de Nuevas Rutas de Dashboard</h1>";

$routes = [
    'rootDashboard' => 'Root Dashboard',
    'directorDashboard' => 'Director Dashboard', 
    'coordinatorDashboard' => 'Coordinator Dashboard',
    'teacherDashboard' => 'Teacher Dashboard',
    'studentDashboard' => 'Student Dashboard',
    'parentDashboard' => 'Parent Dashboard',
    'treasurerDashboard' => 'Treasurer Dashboard'
];

echo "<h2>1. Verificación de Controladores</h2>";
$controllers = [
    'RootDashboardController.php',
    'DirectorDashboardController.php',
    'CoordinatorDashboardController.php', 
    'TeacherDashboardController.php',
    'StudentDashboardController.php',
    'ParentDashboardController.php',
    'TreasurerDashboardController.php'
];

foreach ($controllers as $controller) {
    $path = '../app/controllers/' . $controller;
    if (file_exists($path)) {
        echo "<p style='color: green;'>✅ $controller existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $controller NO existe</p>";
    }
}

echo "<h2>2. Verificación de Vistas</h2>";
$views = [
    'root/dashboard.php',
    'director/dashboard.php',
    'coordinator/dashboard.php',
    'teacher/dashboard.php', 
    'student/dashboard.php',
    'parent/dashboard.php',
    'treasurer/dashboard.php'
];

foreach ($views as $view) {
    $path = '../app/views/' . $view;
    if (file_exists($path)) {
        echo "<p style='color: green;'>✅ $view existe</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ $view NO existe (se creará automáticamente)</p>";
    }
}

echo "<h2>3. Enlaces de Prueba</h2>";
echo "<p>Haz clic en los siguientes enlaces para probar cada dashboard:</p>";
echo "<ul>";

foreach ($routes as $route => $name) {
    $url = "http://localhost:8000/?view=$route";
    echo "<li><a href='$url' target='_blank'>$name</a> - <code>$url</code></li>";
}

echo "</ul>";

echo "<h2>4. Verificación del Router</h2>";
echo "<p>El router debería mapear automáticamente:</p>";
echo "<ul>";
echo "<li><code>?view=rootDashboard</code> → <code>RootDashboardController</code></li>";
echo "<li><code>?view=directorDashboard</code> → <code>DirectorDashboardController</code></li>";
echo "<li><code>?view=coordinatorDashboard</code> → <code>CoordinatorDashboardController</code></li>";
echo "<li><code>?view=teacherDashboard</code> → <code>TeacherDashboardController</code></li>";
echo "<li><code>?view=studentDashboard</code> → <code>StudentDashboardController</code></li>";
echo "<li><code>?view=parentDashboard</code> → <code>ParentDashboardController</code></li>";
echo "<li><code>?view=treasurerDashboard</code> → <code>TreasurerDashboardController</code></li>";
echo "</ul>";

echo "<h2>5. Verificación de Redirecciones</h2>";
echo "<p>Después del login, los usuarios deberían ser redirigidos a:</p>";
echo "<ul>";
echo "<li><strong>Root:</strong> <code>?view=rootDashboard</code></li>";
echo "<li><strong>Director:</strong> <code>?view=directorDashboard</code></li>";
echo "<li><strong>Coordinator:</strong> <code>?view=coordinatorDashboard</code></li>";
echo "<li><strong>Teacher:</strong> <code>?view=teacherDashboard</code></li>";
echo "<li><strong>Student:</strong> <code>?view=studentDashboard</code></li>";
echo "<li><strong>Parent:</strong> <code>?view=parentDashboard</code></li>";
echo "<li><strong>Treasurer:</strong> <code>?view=treasurerDashboard</code></li>";
echo "</ul>";

echo "<h2>6. Próximos Pasos</h2>";
echo "<ol>";
echo "<li>✅ Crear controladores específicos para cada rol</li>";
echo "<li>✅ Actualizar router con nuevos mapeos</li>";
echo "<li>✅ Actualizar redirecciones en indexController</li>";
echo "<li>⚠️ Actualizar todos los sidebars para usar nuevas URLs</li>";
echo "<li>⚠️ Crear vistas de dashboard para roles que no las tengan</li>";
echo "<li>⚠️ Probar login y redirecciones</li>";
echo "</ol>";

echo "<h2>7. Comandos de Prueba</h2>";
echo "<p>Para probar manualmente:</p>";
echo "<ul>";
echo "<li>Login como root: <code>http://localhost:8000/?view=index&action=login</code></li>";
echo "<li>Login como director: <code>http://localhost:8000/?view=index&action=login</code></li>";
echo "<li>Verificar redirección automática después del login</li>";
echo "</ul>";
?> 