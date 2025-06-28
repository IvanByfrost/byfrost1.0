<?php
/**
 * Test para debuggear problemas de URL y routing
 */

echo "<h1>🔍 Debug de URLs y Routing</h1>";

// Incluir dependencias
require_once '../config.php';
require_once '../app/scripts/connection.php';

echo "<h2>1. Información de la petición actual</h2>";
echo "<ul>";
echo "<li><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'No disponible') . "</li>";
echo "<li><strong>QUERY_STRING:</strong> " . ($_SERVER['QUERY_STRING'] ?? 'No disponible') . "</li>";
echo "<li><strong>HTTP_HOST:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'No disponible') . "</li>";
echo "<li><strong>SCRIPT_NAME:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'No disponible') . "</li>";
echo "</ul>";

echo "<h2>2. Parámetros GET</h2>";
echo "<pre>" . print_r($_GET, true) . "</pre>";

echo "<h2>3. Simulación de routing</h2>";

// Simular la lógica del router
$view = $_GET['view'] ?? '';
$action = $_GET['action'] ?? '';

echo "<div>View: <code>" . htmlspecialchars($view) . "</code></div>";
echo "<div>Action: <code>" . htmlspecialchars($action) . "</code></div>";

// Mapeo de controladores
$controllerMapping = [
    'school' => 'SchoolController',
    'coordinator' => 'CoordinatorController',
    'director' => 'DirectorController',
    'teacher' => 'TeacherController',
    'student' => 'StudentController',
    'root' => 'RootController',
    'parent' => 'ParentController',
    'activity' => 'ActivityController',
    'schedule' => 'ScheduleController',
    'user' => 'UserController',
    'index' => 'IndexController',
    'unauthorized' => 'ErrorController',
    'Error' => 'ErrorController'
];

if (isset($controllerMapping[$view])) {
    $controllerName = $controllerMapping[$view];
    echo "<div class='alert alert-success'>✅ Vista mapeada a: <strong>$controllerName</strong></div>";
    
    // Verificar si el controlador existe
    $controllerPath = "../app/controllers/{$controllerName}.php";
    if (file_exists($controllerPath)) {
        echo "<div>✅ Controlador existe: <code>$controllerPath</code></div>";
        
        // Verificar métodos del controlador
        require_once $controllerPath;
        $dbConn = getConnection();
        $controller = new $controllerName($dbConn);
        $methods = get_class_methods($controller);
        
        echo "<div>Métodos disponibles: <code>" . implode(', ', $methods) . "</code></div>";
        
        if (!empty($action)) {
            if (in_array($action, $methods)) {
                echo "<div class='alert alert-success'>✅ Acción '$action' existe en $controllerName</div>";
            } else {
                echo "<div class='alert alert-danger'>❌ Acción '$action' NO existe en $controllerName</div>";
            }
        } else {
            // Sin acción, verificar método por defecto
            if (in_array('index', $methods)) {
                echo "<div class='alert alert-success'>✅ Método por defecto: index()</div>";
            } elseif (in_array('dashboard', $methods)) {
                echo "<div class='alert alert-success'>✅ Método por defecto: dashboard()</div>";
            } else {
                echo "<div class='alert alert-warning'>⚠️ No hay método por defecto (index o dashboard)</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>❌ Controlador NO existe: <code>$controllerPath</code></div>";
    }
} else {
    echo "<div class='alert alert-danger'>❌ Vista '$view' NO está mapeada</div>";
    echo "<div>Vistas disponibles: <code>" . implode(', ', array_keys($controllerMapping)) . "</code></div>";
}

echo "<h2>4. URLs correctas para probar</h2>";
echo "<div class='alert alert-info'>URLs que SÍ funcionan:</div>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=index' target='_blank'>Página principal</a></li>";
echo "<li><a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a></li>";
echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
echo "<li><a href='http://localhost:8000/?view=coordinator&action=dashboard' target='_blank'>Dashboard Coordinador</a></li>";
echo "<li><a href='http://localhost:8000/?view=director&action=dashboard' target='_blank'>Dashboard Director</a></li>";
echo "<li><a href='http://localhost:8000/?view=user&action=assignRole' target='_blank'>AssignRole</a></li>";
echo "<li><a href='http://localhost:8000/?view=school&action=createSchool' target='_blank'>Crear Escuela</a></li>";
echo "</ul>";

echo "<h2>5. URLs que NO funcionan</h2>";
echo "<div class='alert alert-warning'>URLs que causan 404:</div>";
echo "<ul>";
echo "<li><code>/?view=dashboard</code> - 'dashboard' no está mapeado</li>";
echo "<li><code>/?view=admin</code> - 'admin' no está mapeado</li>";
echo "<li><code>/?view=user</code> - UserController no tiene método por defecto</li>";
echo "</ul>";

echo "<h2>6. Solución al problema</h2>";
echo "<div class='alert alert-success'>Para acceder a dashboards, usa:</div>";
echo "<ul>";
echo "<li><strong>Root:</strong> <code>/?view=root&action=dashboard</code></li>";
echo "<li><strong>Coordinador:</strong> <code>/?view=coordinator&action=dashboard</code></li>";
echo "<li><strong>Director:</strong> <code>/?view=director&action=dashboard</code></li>";
echo "<li><strong>Profesor:</strong> <code>/?view=teacher&action=dashboard</code></li>";
echo "<li><strong>Estudiante:</strong> <code>/?view=student&action=dashboard</code></li>";
echo "</ul>";

echo "<h2>7. Test de navegación</h2>";
echo "<div class='d-grid gap-2'>";
echo "<a href='http://localhost:8000/?view=index' class='btn btn-primary' target='_blank'>Página Principal</a>";
echo "<a href='http://localhost:8000/?view=root&action=dashboard' class='btn btn-secondary' target='_blank'>Dashboard Root</a>";
echo "<a href='http://localhost:8000/?view=user&action=assignRole' class='btn btn-info' target='_blank'>AssignRole</a>";
echo "</div>";

echo "<hr>";
echo "<p><strong>Estado:</strong> 🔍 Debug de URLs completado</p>";
?>

<style>
.alert {
    position: relative;
    padding: 1rem 1rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.375rem;
}

.alert-success {
    color: #0f5132;
    background-color: #d1e7dd;
    border-color: #badbcc;
}

.alert-danger {
    color: #842029;
    background-color: #f8d7da;
    border-color: #f5c2c7;
}

.alert-warning {
    color: #664d03;
    background-color: #fff3cd;
    border-color: #ffecb5;
}

.alert-info {
    color: #055160;
    background-color: #cff4fc;
    border-color: #b6effb;
}

.btn {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-primary {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-info {
    color: #fff;
    background-color: #0dcaf0;
    border-color: #0dcaf0;
}

.d-grid {
    display: grid;
}

.gap-2 {
    gap: 0.5rem;
}

pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    overflow-x: auto;
}
</style> 