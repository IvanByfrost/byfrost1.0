<?php
/**
 * MainController - ByFrost
 * Controlador principal que maneja la lógica común de todos los controladores
 */

class MainController {
    protected $dbConn;
    protected $view;
    protected $sessionManager;

    public function __construct($dbConn, $view = null)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->dbConn = $dbConn;
        $this->view = $view;
        
        // Inicializar SessionManager para todos los controladores hijos
        require_once ROOT . '/app/library/SessionManager.php';
        $this->sessionManager = new SessionManager();
    }

    /**
     * Renderiza una vista con layout completo
     * 
     * @param string $folder Carpeta de la vista
     * @param string $file Archivo de la vista
     * @param array $data Datos a pasar a la vista
     * @return void
     */
    protected function render($folder, $file = 'index', $data = [])
    {
        $viewPath = ROOT . "/app/views/{$folder}/{$file}.php";
        
        if (file_exists($viewPath)) {
            extract($data);
            require ROOT . '/app/views/layouts/head.php';
            require ROOT . '/app/views/layouts/header.php';
            require $viewPath;
            require ROOT . '/app/views/layouts/footer.php';
        } else {
            // Redirigir a la página de error 404
            http_response_code(404);
            require_once ROOT . '/app/controllers/ErrorController.php';
            $error = new ErrorController($this->dbConn);
            $error->Error('404');
        }
    }
    
    /**
     * Renderiza una vista parcial sin header y footer
     * Útil para cargar contenido en dashboards o AJAX
     */
    protected function renderPartial($folder, $file = 'index', $data = [])
    {
        $viewPath = ROOT . "/app/views/{$folder}/{$file}.php";
        
        if (file_exists($viewPath)) {
            extract($data);
            require $viewPath;
        } else {
            echo "Vista parcial no encontrada: $viewPath";
        }
    }
    
    /**
     * Renderiza solo el contenido de la vista sin ningún layout
     * Retorna el contenido como string
     */
    protected function renderContent($folder, $file = 'index', $data = [])
    {
        $viewPath = ROOT . "/app/views/{$folder}/{$file}.php";
        
        if (file_exists($viewPath)) {
            extract($data);
            ob_start();
            require $viewPath;
            $content = ob_get_clean();
            return $content;
        } else {
            return "Vista no encontrada: $viewPath";
        }
    }
    
    /**
     * Redirige a una URL específica
     * 
     * @param string $url URL de destino
     * @return void
     */
    protected function redirect($url) 
    {
        if (!headers_sent()) {
            header("Location: " . $url);
            exit();
        } else {
            echo "<script>window.location.href = '" . htmlspecialchars($url) . "';</script>";
            echo "<noscript><meta http-equiv='refresh' content='0;url=" . htmlspecialchars($url) . "'></noscript>";
        }
    }

    /**
     * Carga una vista específica con datos
     * 
     * @param string $viewPath Ruta de la vista (ej: 'school/consultSchool')
     * @param array $data Datos a pasar a la vista
     * @return void
     */
    protected function loadView($viewPath, $data = [])
    {
        $viewPath = ROOT . "/app/views/{$viewPath}.php";
        
        if (file_exists($viewPath)) {
            extract($data);
            require ROOT . '/app/views/layouts/head.php';
            require ROOT . '/app/views/layouts/header.php';
            require $viewPath;
            require ROOT . '/app/views/layouts/footer.php';
        } else {
            // Redirigir a la página de error 404
            http_response_code(404);
            require_once ROOT . '/app/controllers/ErrorController.php';
            $error = new ErrorController($this->dbConn);
            $error->Error('404');
        }
    }

    /**
     * Verifica si la petición es AJAX
     * 
     * @return bool
     */
    protected function isAjaxRequest()
    {
        // Método 1: Verificar HTTP_X_REQUESTED_WITH
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        
        // Método 2: Verificar si es una petición fetch moderna
        if (!empty($_SERVER['HTTP_ACCEPT']) && 
            strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            return true;
        }
        
        // Método 3: Verificar si hay parámetros específicos de AJAX
        if ((isset($_POST['ajax']) && htmlspecialchars($_POST['ajax'])) || 
            (isset($_GET['ajax']) && htmlspecialchars($_GET['ajax']))) {
            return true;
        }
        
        // Método 4: Verificar Content-Type para peticiones POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
            !empty($_SERVER['CONTENT_TYPE']) && 
            strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            return true;
        }
        
        // Método 5: Verificar si la acción es loadPartial (indicador de AJAX)
        if (isset($_GET['action']) && htmlspecialchars($_GET['action']) === 'loadPartial') {
            return true;
        }
        
        // Método 6: Verificar si hay parámetro partialView (indicador de AJAX)
        if (isset($_GET['partialView']) && htmlspecialchars($_GET['partialView'])) {
            return true;
        }
        
        return false;
    }

    /**
     * Verifica si estamos en un contexto de dashboard
     * 
     * @return bool
     */
    protected function isDashboardContext()
    {
        // Verificar si el usuario está logueado y tiene un rol de dashboard
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
            return false;
        }
        
        $userRole = $this->sessionManager->getUserRole();
        $dashboardRoles = ['root', 'director', 'coordinator', 'treasurer', 'professor', 'student', 'parent'];
        
        return in_array($userRole, $dashboardRoles);
    }

    /**
     * Envía una respuesta JSON
     * 
     * @param bool $success Indica si la operación fue exitosa
     * @param string $message Mensaje de respuesta
     * @param array $data Datos adicionales
     * @return void
     */
    protected function sendJsonResponse($success, $message, $data = [])
    {
        require_once __DIR__ . '/../library/HeaderManager.php';
        
        if (HeaderManager::sendJsonHeaders()) {
            echo json_encode([
                'success' => $success,
                'message' => $message,
                'data' => $data
            ]);
        } else {
            // Fallback si no se pueden enviar headers
            echo json_encode([
                'success' => false,
                'message' => 'Error: No se pueden enviar headers',
                'data' => []
            ]);
        }
        exit;
    }

    /**
     * Carga una vista parcial sin header y footer
     * Útil para cargar contenido en dashboards o AJAX
     * 
     * @param string $viewPath Ruta de la vista (ej: 'school/consultSchool')
     * @param array $data Datos a pasar a la vista
     * @return void
     */
    protected function loadPartialView($viewPath, $data = [])
    {
        $viewPath = ROOT . "/app/views/{$viewPath}.php";
        
        if (file_exists($viewPath)) {
            extract($data);
            require $viewPath;
        } else {
            echo "Vista parcial no encontrada: $viewPath";
        }
    }

    /**
     * Renderiza una vista de dashboard con layouts de dashboard
     */
    protected function loadDashboardView($viewPath, $data = [])
    {
        $viewPath = ROOT . "/app/views/{$viewPath}.php";
        if (file_exists($viewPath)) {
            extract($data);
            require ROOT . '/app/views/layouts/head.php';
            require ROOT . '/app/views/layouts/dashHeader.php';
            require $viewPath;
            require ROOT . '/app/views/layouts/dashFooter.php';
        } else {
            http_response_code(404);
            require_once ROOT . '/app/controllers/ErrorController.php';
            $error = new ErrorController($this->dbConn);
            $error->Error('404');
        }
    }

    /**
     * Método unificado para cargar vistas parciales
     * Heredado por todos los dashboards
     */
    protected function loadPartial()
    {
        try {
            $view = htmlspecialchars($_POST['view'] ?? $_GET['view'] ?? '');
            $action = htmlspecialchars($_POST['action'] ?? $_GET['action'] ?? 'index');
            $force = isset($_POST['force']) && htmlspecialchars($_POST['force']) || isset($_GET['force']) && htmlspecialchars($_GET['force']);

            // Si no es AJAX y no se fuerza, mostrar mensaje de ayuda
            if (!$this->isAjaxRequest() && !$force) {
                if (empty($view)) {
                    echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=dashboard&action=loadPartial&view=modulo&action=vista</div>';
                    return;
                }
                
                $viewPath = $view . '/' . $action;
                $fullPath = ROOT . "/app/views/{$viewPath}.php";
                
                if (!file_exists($fullPath)) {
                    echo '<div class="alert alert-danger">Vista no encontrada: ' . htmlspecialchars($viewPath) . '</div>';
                    return;
                }
                
                try {
                    $this->loadPartialView($viewPath);
                } catch (Exception $e) {
                    ErrorHandler::logError("Error cargando vista parcial: " . $e->getMessage());
                    echo '<div class="alert alert-danger">Error al cargar la vista: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
                return;
            }

            // Para peticiones AJAX
            if (empty($view)) {
                $this->sendJsonResponse(false, 'Vista no especificada');
                return;
            }
            
            $viewPath = $view . '/' . $action;
            $fullPath = ROOT . "/app/views/{$viewPath}.php";
            
            if (!file_exists($fullPath)) {
                $this->sendJsonResponse(false, "Vista no encontrada: {$viewPath}");
                return;
            }
            
            try {
                $this->loadPartialView($viewPath);
            } catch (Exception $e) {
                ErrorHandler::logError("Error cargando vista parcial AJAX: " . $e->getMessage());
                $this->sendJsonResponse(false, 'Error al cargar la vista: ' . $e->getMessage());
            }
            
        } catch (Exception $e) {
            ErrorHandler::logError("Error en loadPartial: " . $e->getMessage());
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'Error interno del servidor');
            } else {
                echo '<div class="alert alert-danger">Error interno del servidor</div>';
            }
        }
    }
}
?>
