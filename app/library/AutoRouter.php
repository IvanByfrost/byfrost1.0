<?php
/**
 * Sistema de Routing Automático para ByFrost
 * 
 * Detecta automáticamente controladores y genera mapeos dinámicamente
 * Escalable para proyectos que crecen constantemente
 */

class AutoRouter
{
    private $dbConn;
    private $sessionManager;
    private $controllersDir;
    
    // Mapeo especial para casos que no siguen convención
    private $specialMapping = [
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
    
    // Vistas que requieren acción directa
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
        $this->controllersDir = ROOT . '/app/controllers/';
    }
    
    /**
     * Genera el mapeo de controladores automáticamente
     */
    public function generateControllerMapping()
    {
        $mapping = [];
        
        // Escanear directorio de controladores
        if (is_dir($this->controllersDir)) {
            $files = scandir($this->controllersDir);
            
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    $controllerName = pathinfo($file, PATHINFO_FILENAME);
                    $viewName = $this->controllerToViewName($controllerName);
                    
                    // Solo agregar si no está en el mapeo especial
                    if (!isset($this->specialMapping[$viewName])) {
                        $mapping[$viewName] = $controllerName;
                    }
                }
            }
        }
        
        // Combinar con mapeo especial
        return array_merge($mapping, $this->specialMapping);
    }
    
    /**
     * Convierte nombre de controlador a nombre de vista
     */
    private function controllerToViewName($controllerName)
    {
        // Remover 'Controller' del final
        $viewName = str_replace('Controller', '', $controllerName);
        
        // Convertir a camelCase si es PascalCase
        if (preg_match('/^[A-Z][a-zA-Z]*$/', $viewName)) {
            $viewName = lcfirst($viewName);
        }
        
        // Casos especiales
        $specialCases = [
            'DirectorDashboard' => 'director',
            'RootDashboard' => 'root',
            'CoordinatorDashboard' => 'coordinator',
            'TeacherDashboard' => 'teacher',
            'StudentDashboard' => 'student',
            'ParentDashboard' => 'parent',
            'TreasurerDashboard' => 'treasurer',
            'AcademicAverages' => 'academicAverages'
        ];
        
        if (isset($specialCases[$viewName])) {
            return $specialCases[$viewName];
        }
        
        return strtolower($viewName);
    }
    
    /**
     * Procesa una ruta automáticamente
     */
    public function processRoute($view, $action = null)
    {
        error_log("AutoRouter: Procesando ruta - view: $view, action: $action");
        
        // Generar mapeo automáticamente
        $controllerMapping = $this->generateControllerMapping();
        
        // Determinar el controlador
        $controllerName = $this->getControllerName($view, $controllerMapping);
        if (!$controllerName) {
            $this->handleError(404, "Controlador no encontrado para la vista: $view");
            return;
        }
        
        // Cargar y ejecutar el controlador
        $this->executeController($controllerName, $action, $view);
    }
    
    /**
     * Determina el nombre del controlador
     */
    private function getControllerName($view, $controllerMapping)
    {
        // Si hay un mapeo directo, usarlo
        if (isset($controllerMapping[$view])) {
            return $controllerMapping[$view];
        }
        
        // Si la vista tiene formato module/action, extraer el módulo
        if (strpos($view, '/') !== false) {
            $module = explode('/', $view)[0];
            if (isset($controllerMapping[$module])) {
                return $controllerMapping[$module];
            }
        }
        
        return null;
    }
    
    /**
     * Ejecuta el controlador
     */
    private function executeController($controllerName, $action, $view)
    {
        $controllerPath = $this->controllersDir . $controllerName . '.php';
        
        if (!file_exists($controllerPath)) {
            $this->handleError(404, "Controlador no encontrado: $controllerName");
            return;
        }
        
        // Cargar el controlador
        require_once $controllerPath;
        
        // Instanciar el controlador
        $controller = new $controllerName($this->dbConn);
        
        // Si no hay acción, usar acción por defecto
        if (empty($action)) {
            $action = $this->getDefaultAction($view);
        }
        
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
     * Obtiene la acción por defecto para una vista
     */
    private function getDefaultAction($view)
    {
        $defaultActions = [
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
        
        return $defaultActions[$view] ?? 'index';
    }
    
    /**
     * Construye URL para loadView.js
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
     * Obtiene la URL base
     */
    private function getBaseUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $path = dirname($_SERVER['SCRIPT_NAME']);
        return "$protocol://$host$path";
    }
    
    /**
     * Maneja errores
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
     * Verifica si es petición AJAX
     */
    private function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * Obtiene lista de vistas de acción directa
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
     * Obtiene estadísticas del sistema
     */
    public function getSystemStats()
    {
        $controllerMapping = $this->generateControllerMapping();
        
        return [
            'total_controllers' => count($controllerMapping),
            'auto_detected' => count($controllerMapping) - count($this->specialMapping),
            'special_mappings' => count($this->specialMapping),
            'direct_action_views' => count($this->directActionViews),
            'controllers_dir' => $this->controllersDir
        ];
    }
} 