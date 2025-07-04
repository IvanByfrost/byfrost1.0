<?php
// Archivo de diagnóstico para el sistema de nómina
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir ROOT si no está definida
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}

echo "<h1>Diagnóstico del Sistema de Nómina</h1>";

// 1. Verificar configuración básica
echo "<h2>1. Configuración Básica</h2>";
echo "<p>ROOT: " . __DIR__ . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// 2. Verificar archivos críticos
echo "<h2>2. Archivos Críticos</h2>";

$criticalFiles = [
    'config.php',
    'app/controllers/payrollController.php',
    'app/models/payrollModel.php',
    'app/views/payroll/dashboard.php',
    'app/scripts/routerView.php',
    'app/library/SecurityMiddleware.php',
    'app/library/SessionManager.php'
];

foreach ($criticalFiles as $file) {
    $fullPath = ROOT . '/' . $file;
    $exists = file_exists($fullPath);
    $status = $exists ? "✅ EXISTE" : "❌ NO EXISTE";
    echo "<p>$status: $file</p>";
    if (!$exists) {
        echo "<p style='color: red; margin-left: 20px;'>Ruta completa: $fullPath</p>";
    }
}

// 3. Simular el flujo del router
echo "<h2>3. Simulación del Router</h2>";

// Simular parámetros GET
$_GET['view'] = 'payroll';
$_GET['action'] = 'dashboard';

echo "<p>Parámetros GET:</p>";
echo "<ul>";
echo "<li>view: " . ($_GET['view'] ?? 'NO DEFINIDO') . "</li>";
echo "<li>action: " . ($_GET['action'] ?? 'NO DEFINIDO') . "</li>";
echo "</ul>";

// 4. Verificar mapeo de controladores
echo "<h2>4. Mapeo de Controladores</h2>";

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
    'role' => 'RoleController',
    'payroll' => 'PayrollController',
    'index' => 'IndexController',
    'login' => 'IndexController',
    'register' => 'IndexController',
    'contact' => 'IndexController',
    'about' => 'IndexController',
    'plans' => 'IndexController',
    'faq' => 'IndexController',
    'forgotPassword' => 'IndexController',
    'resetPassword' => 'IndexController',
    'completeProf' => 'IndexController',
    'unauthorized' => 'ErrorController',
    'Error' => 'ErrorController'
];

$view = $_GET['view'];
echo "<p>Vista solicitada: <strong>$view</strong></p>";

if (isset($controllerMapping[$view])) {
    $controllerName = $controllerMapping[$view];
    $controllerPath = ROOT . "/app/controllers/{$controllerName}.php";
    
    echo "<p>✅ Controlador mapeado: <strong>$controllerName</strong></p>";
    echo "<p>Ruta del controlador: $controllerPath</p>";
    
    if (file_exists($controllerPath)) {
        echo "<p>✅ Archivo del controlador existe</p>";
        
        // Intentar cargar el controlador
        try {
            require_once $controllerPath;
            echo "<p>✅ Controlador cargado exitosamente</p>";
            
            // Verificar que la clase existe
            if (class_exists($controllerName)) {
                echo "<p>✅ Clase $controllerName existe</p>";
                
                // Verificar métodos
                $action = $_GET['action'];
                if (method_exists($controllerName, $action)) {
                    echo "<p>✅ Método $action existe en $controllerName</p>";
                } else {
                    echo "<p>❌ Método $action NO existe en $controllerName</p>";
                    
                    // Mostrar métodos disponibles
                    $methods = get_class_methods($controllerName);
                    echo "<p>Métodos disponibles: " . implode(', ', $methods) . "</p>";
                }
            } else {
                echo "<p>❌ Clase $controllerName NO existe</p>";
            }
        } catch (Exception $e) {
            echo "<p>❌ Error al cargar controlador: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>❌ Archivo del controlador NO existe</p>";
    }
} else {
    echo "<p>❌ Vista '$view' NO está mapeada a ningún controlador</p>";
    echo "<p>Vistas mapeadas: " . implode(', ', array_keys($controllerMapping)) . "</p>";
}

// 5. Verificar vistas
echo "<h2>5. Verificación de Vistas</h2>";

$payrollViews = [
    'payroll/dashboard',
    'payroll/employees',
    'payroll/periods',
    'payroll/absences',
    'payroll/overtime',
    'payroll/bonuses',
    'payroll/reports'
];

foreach ($payrollViews as $viewPath) {
    $fullPath = ROOT . '/app/views/' . $viewPath . '.php';
    $exists = file_exists($fullPath);
    $status = $exists ? "✅ EXISTE" : "❌ NO EXISTE";
    echo "<p>$status: $viewPath.php</p>";
}

// 6. Enlaces de prueba
echo "<h2>6. Enlaces de Prueba</h2>";
echo "<p><a href='index.php?view=payroll&action=dashboard'>Dashboard de Nómina</a></p>";
echo "<p><a href='index.php?view=payroll&action=employees'>Empleados</a></p>";
echo "<p><a href='index.php?view=payroll&action=periods'>Períodos</a></p>";
echo "<p><a href='index.php?view=payroll&action=absences'>Ausencias</a></p>";
echo "<p><a href='index.php?view=payroll&action=overtime'>Horas Extras</a></p>";
echo "<p><a href='index.php?view=payroll&action=bonuses'>Bonificaciones</a></p>";
echo "<p><a href='index.php?view=payroll&action=reports'>Reportes</a></p>";

// 7. Verificar base de datos
echo "<h2>7. Verificación de Base de Datos</h2>";

try {
    require_once ROOT . '/app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<p>✅ Conexión a base de datos exitosa</p>";
    
    // Verificar tablas de nómina
    $tables = ['employees', 'payroll_periods', 'payroll_records', 'absences', 'overtime', 'bonuses'];
    foreach ($tables as $table) {
        try {
            $stmt = $dbConn->query("SHOW TABLES LIKE '$table'");
            $exists = $stmt->rowCount() > 0;
            $status = $exists ? "✅ EXISTE" : "❌ NO EXISTE";
            echo "<p>$status: Tabla $table</p>";
        } catch (Exception $e) {
            echo "<p>❌ Error verificando tabla $table: " . $e->getMessage() . "</p>";
        }
    }
} catch (Exception $e) {
    echo "<p>❌ Error de conexión a base de datos: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Diagnóstico completado.</strong></p>";
?> 