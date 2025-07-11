<?php
/**
 * Router - Sistema de Routing Unificado e Inteligente
 * 
 * Combina lo mejor de todos los sistemas:
 * - Detección automática de controladores
 * - Escalabilidad infinita
 * - Sin listas manuales
 * - Caché inteligente
 * - Seguridad robusta
 * - Mantenimiento cero
 */

class Router
{
    private $dbConn;
    private $sessionManager;
    private $controllersDir;
    private $viewsDir;
    private $cache = [];
    private $cacheExpiry = 300; // 5 minutos
    private $debug = false;
    
    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->dbConn = $dbConn;
        $this->sessionManager = new SessionManager();
        $this->controllersDir = ROOT . '/app/controllers/';
        $this->viewsDir = ROOT . '/app/views/';
    }
    
    /**
     * Sistema principal de routing unificado
     */
    public function processRoute($view, $action = null)
    {
        $this->log("Router: Procesando ruta - view: $view, action: $action");
        
        // Cache inteligente
        $cacheKey = "route_$view" . ($action ? "_$action" : '');
        $cachedResult = $this->getCache($cacheKey);
        
        if ($cachedResult !== null) {
            $this->log("Router cache hit para: $cacheKey");
            return $cachedResult;
        }
        
        // Detección automática completa
        $controllerInfo = $this->detectControllerIntelligently($view);
        
        if (!$controllerInfo) {
            $this->handleError(404, "Controlador no encontrado para la vista: $view");
            return;
        }
        
        // Ejecutar controlador
        $result = $this->executeController($controllerInfo, $action, $view);
        
        // Cache
        $this->setCache($cacheKey, $result);
        
        return $result;
    }
    
    /**
     * Detección inteligente de controladores - SIN LISTAS MANUALES
     */
    private function detectControllerIntelligently($view)
    {
        // 1. Extraer módulo si la vista tiene formato module/action
        $module = $view;
        if (strpos($view, '/') !== false) {
            $module = explode('/', $view)[0];
        }
        
        // 2. Búsqueda automática por convenciones
        $controllerName = $this->findControllerByConventions($module);
        if ($controllerName) {
            return [
                'controller' => $controllerName,
                'type' => 'convention',
                'view' => $view,
                'module' => $module
            ];
        }
        
        // 3. Búsqueda por similitud inteligente
        $controllerName = $this->findControllerBySimilarity($module);
        if ($controllerName) {
            return [
                'controller' => $controllerName,
                'type' => 'similarity',
                'view' => $view,
                'module' => $module
            ];
        }
        
        // 4. Búsqueda por patrones de nombres
        $controllerName = $this->findControllerByPatterns($module);
        if ($controllerName) {
            return [
                'controller' => $controllerName,
                'type' => 'pattern',
                'view' => $view,
                'module' => $module
            ];
        }
        
        return null;
    }
    
    /**
     * Búsqueda por convenciones estándar
     */
    private function findControllerByConventions($name)
    {
        $conventions = [
            ucfirst($name) . 'Controller',
            ucfirst($name) . 'DashboardController',
            strtolower($name) . 'Controller',
            $name . 'Controller',
            ucfirst($name) . 'MasterController',
            $name . 'MasterController'
        ];
        
        foreach ($conventions as $controllerName) {
            $filePath = $this->controllersDir . $controllerName . '.php';
            if (file_exists($filePath)) {
                $this->log("Router: Controlador encontrado por convención: $controllerName");
                return $controllerName;
            }
        }
        
        return null;
    }
    
    /**
     * Búsqueda por similitud inteligente
     */
    private function findControllerBySimilarity($name)
    {
        if (!is_dir($this->controllersDir)) {
            return null;
        }
        
        $files = scandir($this->controllersDir);
        $nameLower = strtolower($name);
        $bestMatch = null;
        $bestScore = 0;
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $controllerName = pathinfo($file, PATHINFO_FILENAME);
            $controllerLower = strtolower($controllerName);
            
            // Remover 'Controller' para comparación
            $controllerWithoutSuffix = str_replace('Controller', '', $controllerName);
            $controllerWithoutSuffixLower = strtolower($controllerWithoutSuffix);
            
            // Calcular similitud
            $score1 = similar_text($controllerLower, $nameLower, $percent1);
            $score2 = similar_text($controllerWithoutSuffixLower, $nameLower, $percent2);
            
            $maxScore = max($percent1, $percent2);
            
            if ($maxScore > $bestScore && $maxScore > 70) {
                $bestScore = $maxScore;
                $bestMatch = $controllerName;
            }
        }
        
        if ($bestMatch) {
            $this->log("Router: Controlador encontrado por similitud: $bestMatch (score: $bestScore%)");
        }
        
        return $bestMatch;
    }
    
    /**
     * Búsqueda por patrones de nombres
     */
    private function findControllerByPatterns($name)
    {
        if (!is_dir($this->controllersDir)) {
            return null;
        }
        
        $files = scandir($this->controllersDir);
        $nameLower = strtolower($name);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $controllerName = pathinfo($file, PATHINFO_FILENAME);
            $controllerLower = strtolower($controllerName);
            
            // Patrones de búsqueda
            $patterns = [
                // Contiene el nombre
                strpos($controllerLower, $nameLower) !== false,
                // Termina con el nombre
                str_ends_with($controllerLower, $nameLower),
                // Comienza con el nombre
                str_starts_with($controllerLower, $nameLower),
                // Sin sufijo contiene el nombre
                strpos(str_replace('controller', '', $controllerLower), $nameLower) !== false
            ];
            
            if (in_array(true, $patterns)) {
                $this->log("Router: Controlador encontrado por patrón: $controllerName");
                return $controllerName;
            }
        }
        
        return null;
    }
    
    /**
     * Ejecuta el controlador detectado
     */
    private function executeController($controllerInfo, $action, $view)
    {
        $controllerName = $controllerInfo['controller'];
        $controllerPath = $this->controllersDir . $controllerName . '.php';
        
        if (!file_exists($controllerPath)) {
            $this->handleError(404, "Controlador no encontrado: $controllerName");
            return;
        }
        
        // Cargar controlador
        require_once $controllerPath;
        
        // Instanciar controlador
        $controller = new $controllerName($this->dbConn);
        
        // Determinar acción automáticamente
        if (empty($action)) {
            $action = $this->detectDefaultAction($view, $controller);
        }
        
        // Verificar método
        if (!method_exists($controller, $action)) {
            $this->handleError(404, "Acción no encontrada: $action en $controllerName");
            return;
        }
        
        // Ejecutar
        try {
            $this->log("Ejecutando: $controllerName::$action()");
            return $controller->$action();
        } catch (Exception $e) {
            $this->log("Error ejecutando $controllerName::$action: " . $e->getMessage());
            $this->handleError(500, "Error interno del servidor");
        }
    }
    
    /**
     * Detecta acción por defecto automáticamente
     */
    private function detectDefaultAction($view, $controller)
    {
        // 1. Si la vista tiene formato module/action, usar la acción
        if (strpos($view, '/') !== false) {
            $parts = explode('/', $view);
            $action = $parts[1];
            if (method_exists($controller, $action)) {
                return $action;
            }
        }
        
        // 2. Buscar métodos comunes en el controlador
        $commonMethods = ['index', 'dashboard', 'main', 'home', 'default'];
        
        foreach ($commonMethods as $method) {
            if (method_exists($controller, $method)) {
                return $method;
            }
        }
        
        // 3. Detectar por convenciones del nombre del controlador
        $controllerName = get_class($controller);
        $controllerNameLower = strtolower($controllerName);
        
        // Si contiene 'Dashboard', usar 'dashboard'
        if (strpos($controllerNameLower, 'dashboard') !== false) {
            if (method_exists($controller, 'dashboard')) {
                return 'dashboard';
            }
        }
        
        // Si contiene 'Master', usar 'index'
        if (strpos($controllerNameLower, 'master') !== false) {
            if (method_exists($controller, 'index')) {
                return 'index';
            }
        }
        
        // 4. Usar 'index' como fallback
        return 'index';
    }
    
    /**
     * Genera mapeo de controladores automáticamente
     */
    public function generateControllerMapping()
    {
        $mapping = [];
        $controllers = $this->scanControllers();
        
        foreach ($controllers as $controller) {
            $viewName = $this->controllerToViewName($controller);
            $mapping[$viewName] = $controller;
        }
        
        // Forzar el mapeo correcto para root
        $mapping['root'] = 'RootDashboardController';
        return $mapping;
    }
    
    /**
     * Escanea controladores automáticamente
     */
    private function scanControllers()
    {
        $controllers = [];
        
        if (is_dir($this->controllersDir)) {
            $files = scandir($this->controllersDir);
            
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    $controllers[] = pathinfo($file, PATHINFO_FILENAME);
                }
            }
        }
        
        return $controllers;
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
        
        return strtolower($viewName);
    }
    
    /**
     * Construye URL automáticamente
     */
    public function buildLoadViewUrl($viewName)
    {
        $baseUrl = $this->getBaseUrl();
        
        // Detectar vistas de acción directa automáticamente
        $directActionViews = $this->detectDirectActionViews();
        
        if (in_array($viewName, $directActionViews)) {
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
     * Detecta vistas de acción directa automáticamente
     */
    private function detectDirectActionViews()
    {
        $directViews = [];
        
        // Escanear controladores para detectar métodos que no usan loadPartial
        $controllers = $this->scanControllers();
        
        foreach ($controllers as $controller) {
            $controllerPath = $this->controllersDir . $controller . '.php';
            
            if (file_exists($controllerPath)) {
                require_once $controllerPath;
                
                try {
                    $instance = new $controller($this->dbConn);
                    $methods = get_class_methods($instance);
                    
                    foreach ($methods as $method) {
                        // Métodos que probablemente son acciones directas
                        if (in_array($method, ['dashboard', 'index', 'main', 'home'])) {
                            $viewName = $this->controllerToViewName($controller);
                            $directViews[] = "$viewName/$method";
                        }
                    }
                } catch (Exception $e) {
                    // Ignorar errores de instanciación
                }
            }
        }
        
        return $directViews;
    }
    
    /**
     * Sistema de cache inteligente
     */
    private function getCache($key)
    {
        if (!isset($this->cache[$key])) {
            return null;
        }
        
        $cached = $this->cache[$key];
        if (time() - $cached['time'] > $this->cacheExpiry) {
            unset($this->cache[$key]);
            return null;
        }
        
        return $cached['data'];
    }
    
    private function setCache($key, $data)
    {
        $this->cache[$key] = [
            'data' => $data,
            'time' => time()
        ];
    }
    
    /**
     * Obtiene estadísticas del sistema
     */
    public function getSystemStats()
    {
        $controllers = $this->scanControllers();
        $views = $this->scanViews();
        
        return [
            'total_controllers' => count($controllers),
            'total_views' => count($views),
            'auto_detected' => count($controllers),
            'direct_action_views' => count($this->detectDirectActionViews()),
            'cache_size' => count($this->cache),
            'controllers_dir' => $this->controllersDir,
            'views_dir' => $this->viewsDir,
            'version' => 'Router - Completamente Automático'
        ];
    }
    
    /**
     * Escanea vistas automáticamente
     */
    private function scanViews()
    {
        $views = [];
        $this->scanDirectoryRecursive($this->viewsDir, $views);
        return $views;
    }
    
    /**
     * Escaneo recursivo de directorios
     */
    private function scanDirectoryRecursive($dir, &$views, $prefix = '')
    {
        if (!is_dir($dir)) return;
        
        $files = scandir($dir);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $path = $dir . '/' . $file;
            
            if (is_dir($path)) {
                $newPrefix = $prefix ? $prefix . '/' . $file : $file;
                $this->scanDirectoryRecursive($path, $views, $newPrefix);
            } elseif (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $viewName = $prefix ? $prefix . '/' . pathinfo($file, PATHINFO_FILENAME) : pathinfo($file, PATHINFO_FILENAME);
                $views[] = $viewName;
            }
        }
    }
    
    /**
     * Obtiene URL base
     */
    private function getBaseUrl()
    {
        return defined('url') ? url : 'http://localhost:8000/';
    }
    
    /**
     * Manejo de errores unificado
     */
    private function handleError($code, $message)
    {
        $this->log("Error $code: $message");
        
        if ($this->isAjaxRequest()) {
            http_response_code($code);
            echo json_encode(['success' => false, 'message' => $message]);
        } else {
            http_response_code($code);
            require_once ROOT . '/app/controllers/ErrorController.php';
            $error = new ErrorController($this->dbConn);
            $error->Error($code);
        }
    }
    
    /**
     * Verifica si es una petición AJAX
     */
    private function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Sistema de logging
     */
    private function log($message)
    {
        if ($this->debug) {
            error_log("[UnifiedSmartRouter] $message");
        }
    }
    
    /**
     * Activar/desactivar debug
     */
    public function setDebug($enabled)
    {
        $this->debug = $enabled;
    }
} 