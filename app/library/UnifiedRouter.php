<?php
/**
 * Sistema de Routing Unificado para ByFrost
 * 
 * Este sistema coordina todos los sistemas de routing existentes:
 * - loadView.js (JavaScript)
 * - MainController (PHP)
 * - routerView.php (Router principal)
 * - index.php (Punto de entrada)
 */

class UnifiedRouter
{
    private $dbConn;
    private $sessionManager;
    
    // Mapeo de controladores
    private $controllerMapping = [
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
        'Error' => 'ErrorController',
        'school' => 'SchoolController',
        'user' => 'UserController',
        'payroll' => 'payrollController',
        'activity' => 'activityController',
        'student' => 'StudentController',
        'director' => 'DirectorDashboardController',
        'root' => 'RootController',
        'coordinator' => 'CoordinatorController',
        'teacher' => 'TeacherController',
        'parent' => 'ParentController',
        'treasurer' => 'TreasurerController',
        'academicAverages' => 'AcademicAveragesController'
    ];
    
    // Acciones por defecto
    private $defaultActions = [
        'index' => 'index',
        'login' => 'login',
        'register' => 'register',
        'school' => 'index',
        'user' => 'index',
        'payroll' => 'dashboard',
        'activity' => 'index',
        'student' => 'index',
        'director' => 'dashboard',
        'root' => 'dashboard',
        'coordinator' => 'dashboard',
        'teacher' => 'dashboard',
        'parent' => 'dashboard',
        'treasurer' => 'dashboard'
    ];
    
    // Vistas que requieren acción directa (no loadPartial)
    private $directActionViews = [
        'school/consultSchool',
        'user/consultUser',
        'user/assignRole',
        'user/roleHistory',
        'payroll/dashboard',
        'activity/dashboard',
        'student/academicHistory'
    ];
    
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
        $this->sessionManager = new SessionManager();
    }
    
    /**
     * Procesa una ruta y ejecuta la acción correspondiente
     */
    public function processRoute($view, $action = null)
    {
        error_log("UnifiedRouter: Procesando ruta - view: $view, action: $action");
        
        // Si no hay acción, usar la acción por defecto
        if (empty($action) && isset($this->defaultActions[$view])) {
            $action = $this->defaultActions[$view];
        }
        
        // Determinar el controlador
        $controllerName = $this->getControllerName($view);
        if (!$controllerName) {
            $this->handleError(404, "Controlador no encontrado para la vista: $view");
            return;
        }
        
        // Cargar y ejecutar el controlador
        $this->executeController($controllerName, $action, $view);
    }
    
    /**
     * Determina el nombre del controlador basado en la vista
     */
    private function getControllerName($view)
    {
        // Si hay un mapeo directo, usarlo
        if (isset($this->controllerMapping[$view])) {
            return $this->controllerMapping[$view];
        }
        
        // Si la vista tiene formato module/action, extraer el módulo
        if (strpos($view, '/') !== false) {
            $module = explode('/', $view)[0];
            if (isset($this->controllerMapping[$module])) {
                return $this->controllerMapping[$module];
            }
        }
        
        // Intentar con la primera letra en mayúscula
        $capitalizedView = ucfirst($view);
        $controllerName = $capitalizedView . 'Controller';
        
        if (file_exists(ROOT . "/app/controllers/$controllerName.php")) {
            return $controllerName;
        }
        
        return null;
    }
    
    /**
     * Ejecuta el controlador con la acción especificada
     */
    private function executeController($controllerName, $action, $view)
    {
        $controllerPath = ROOT . "/app/controllers/$controllerName.php";
        
        if (!file_exists($controllerPath)) {
            $this->handleError(404, "Controlador no encontrado: $controllerName");
            return;
        }
        
        // Cargar el controlador
        require_once $controllerPath;
        
        // Instanciar el controlador
        $controller = new $controllerName($this->dbConn);
        
        // Verificar si el método existe
        if (!method_exists($controller, $action)) {
            $this->handleError(404, "Acción no encontrada: $action en $controllerName");
            return;
        }
        
        // Ejecutar la acción
        try {
            $controller->$action();
        } catch (Exception $e) {
            error_log("Error ejecutando $controllerName::$action: " . $e->getMessage());
            $this->handleError(500, "Error interno del servidor");
        }
    }
    
    /**
     * Construye una URL para loadView.js
     */
    public function buildLoadViewUrl($viewName)
    {
        $baseUrl = $this->getBaseUrl();
        
        // Si la vista está en la lista de acciones directas
        if (in_array($viewName, $this->directActionViews)) {
            if (strpos($viewName, '/') !== false) {
                list($module, $action) = explode('/', $viewName);
                return "$baseUrl?view=$module&action=$action";
            }
        }
        
        // Vista con parámetros
        if (strpos($viewName, '?') !== false) {
            list($view, $params) = explode('?', $viewName);
            if (strpos($view, '/') !== false) {
                list($module, $partialView) = explode('/', $view);
                return "$baseUrl?view=$module&action=loadPartial&partialView=$partialView&$params";
            }
        }
        
        // Vista con módulo explícito
        if (strpos($viewName, '/') !== false) {
            list($module, $partialView) = explode('/', $viewName);
            return "$baseUrl?view=$module&action=loadPartial&partialView=$partialView";
        }
        
        // Vista simple
        return "$baseUrl?view=$viewName";
    }
    
    /**
     * Obtiene la URL base de la aplicación
     */
    private function getBaseUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $path = dirname($_SERVER['SCRIPT_NAME']);
        return "$protocol://$host$path";
    }
    
    /**
     * Maneja errores de routing
     */
    private function handleError($code, $message)
    {
        http_response_code($code);
        
        if ($this->isAjaxRequest()) {
            echo json_encode([
                'success' => false,
                'error' => $code,
                'message' => $message
            ]);
        } else {
            require_once ROOT . '/app/controllers/errorController.php';
            $error = new ErrorController($this->dbConn);
            $error->Error($code);
        }
    }
    
    /**
     * Verifica si es una petición AJAX
     */
    private function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * Obtiene la lista de vistas que requieren acción directa
     */
    public function getDirectActionViews()
    {
        return $this->directActionViews;
    }
    
    /**
     * Verifica si una vista requiere acción directa
     */
    public function requiresDirectAction($viewName)
    {
        return in_array($viewName, $this->directActionViews);
    }
    
    /**
     * Obtiene el mapeo de controladores para el router
     */
    public function getControllerMapping()
    {
        return $this->controllerMapping;
    }
} 