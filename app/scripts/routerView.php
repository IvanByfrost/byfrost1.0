<?php
if (!defined('ROOT')) {
    // Desde app/scripts/routerView.php necesitamos subir 3 niveles para llegar al directorio raíz
    // __DIR__ = F:\xampp\htdocs\byfrost\app\scripts
    // dirname(__DIR__) = F:\xampp\htdocs\byfrost\app
    // dirname(dirname(__DIR__)) = F:\xampp\htdocs\byfrost
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SecurityMiddleware.php';

$view = $_GET['view'] ?? '';
$action = $_GET['action'] ?? '';

// Validar y sanitizar parámetros
$validation = SecurityMiddleware::validateGetParams($_GET);
if (!$validation) {
    http_response_code(400);
    die('Parámetros inválidos');
}

// Validar la vista
$viewValidation = SecurityMiddleware::validatePath($view);
if (!$viewValidation['valid']) {
    http_response_code(403);
    die('Vista no válida: ' . $viewValidation['error']);
}

$view = $viewValidation['sanitized'];

// Validar la acción
if (!empty($action)) {
    $actionValidation = SecurityMiddleware::validatePath($action);
    if (!$actionValidation['valid']) {
        http_response_code(403);
        die('Acción no válida: ' . $actionValidation['error']);
    }
    $action = $actionValidation['sanitized'];
}

// Debug: mostrar la ruta que se está construyendo
// echo "<!-- Debug: ROOT = " . ROOT . " -->";
// echo "<!-- Debug: view = " . htmlspecialchars($view) . " -->";
// echo "<!-- Debug: action = " . htmlspecialchars($action) . " -->";

// Seguridad extendida
if (
    empty($view) ||
    preg_match('/\.\.|\.env|config|\.htaccess/i', $view)
) {
    http_response_code(403);
    echo "<h2>Error 403</h2><p>Acceso denegado.</p>";
    echo "<p>Vista solicitada: <code>" . htmlspecialchars($view) . "</code></p>";
    exit;
}

// Mapeo de vistas a controladores
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

// Verificar si la vista tiene un controlador mapeado
if (isset($controllerMapping[$view])) {
    $controllerName = $controllerMapping[$view];
    $controllerPath = ROOT . "/app/controllers/{$controllerName}.php";
    
    // echo "<!-- Debug: Controlador mapeado: " . $controllerName . " -->";
    // echo "<!-- Debug: Ruta del controlador: " . $controllerPath . " -->";
    
    if (file_exists($controllerPath)) {
        // Cargar el controlador
        require_once $controllerPath;
        
        // Obtener la conexión a la base de datos
        require_once ROOT . '/app/scripts/connection.php';
        $dbConn = getConnection();
        
        // Instanciar el controlador
        $controller = new $controllerName($dbConn, null);
        
        // Si hay una acción específica, llamarla
        if (!empty($action)) {
            if (method_exists($controller, $action)) {
                // echo "<!-- Debug: Llamando método: " . $action . " -->";
                $controller->$action();
            } else {
                http_response_code(404);
                echo "<h2>Error 404</h2><p>La acción <code>" . htmlspecialchars($action) . "</code> no existe en el controlador <code>" . htmlspecialchars($controllerName) . "</code>.</p>";
                echo "<p>Métodos disponibles: <code>" . implode(', ', get_class_methods($controller)) . "</code></p>";
            }
        } else {
            // Si no hay acción, determinar la acción basada en la vista
            $defaultActions = [
                'login' => 'login',
                'register' => 'register',
                'contact' => 'contact',
                'about' => 'about',
                'plans' => 'plans',
                'faq' => 'faq',
                'forgotPassword' => 'forgotPassword',
                'resetPassword' => 'resetPassword',
                'completeProf' => 'completeProf'
            ];
            
            // Si la vista tiene una acción por defecto, usarla
            if (isset($defaultActions[$view])) {
                $defaultAction = $defaultActions[$view];
                if (method_exists($controller, $defaultAction)) {
                    // echo "<!-- Debug: Llamando acción por defecto: " . $defaultAction . " -->";
                    $controller->$defaultAction();
                } else {
                    http_response_code(404);
                    echo "<h2>Error 404</h2><p>La acción por defecto <code>" . htmlspecialchars($defaultAction) . "</code> no existe en el controlador <code>" . htmlspecialchars($controllerName) . "</code>.</p>";
                }
            } elseif ($controllerName === 'ErrorController' && $view === 'unauthorized') {
                // Para ErrorController con vista unauthorized, usar el método Error con 'unauthorized'
                if (method_exists($controller, 'Error')) {
                    $controller->Error('unauthorized');
                } else {
                    http_response_code(403);
                    echo "<h2>Error 403</h2><p>Acceso no autorizado.</p>";
                }
            } elseif (method_exists($controller, 'index')) {
                $controller->index();
            } elseif (method_exists($controller, 'dashboard')) {
                $controller->dashboard();
            } else {
                http_response_code(404);
                echo "<h2>Error 404</h2><p>No se encontró un método por defecto en el controlador <code>" . htmlspecialchars($controllerName) . "</code>.</p>";
            }
        }
    } else {
        http_response_code(404);
        echo "<h2>Error 404</h2><p>El controlador <code>" . htmlspecialchars($controllerName) . "</code> no existe.</p>";
        echo "<p>Ruta buscada: <code>" . htmlspecialchars($controllerPath) . "</code></p>";
    }
} else {
    // Si no hay controlador mapeado, intentar cargar como vista directa
    $viewPath = ROOT . "/app/views/" . $view . ".php";
    
    // echo "<!-- Debug: Intentando cargar como vista directa: " . $viewPath . " -->";
    
    if (file_exists($viewPath)) {
        // echo "<!-- Debug: Cargando vista: " . $viewPath . " -->";
        require_once $viewPath;
    } else {
        http_response_code(404);
        echo "<h2>Error 404</h2><p>La vista <code>" . htmlspecialchars($view) . "</code> no existe.</p>";
        echo "<p>Ruta buscada: <code>" . htmlspecialchars($viewPath) . "</code></p>";
        echo "<p>Vistas disponibles: <code>" . implode(', ', array_keys($controllerMapping)) . "</code></p>";
    }
}
