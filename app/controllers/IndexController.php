<?php
require_once 'app/controllers/MainController.php';

class IndexController extends MainController
{
    public function __construct($dbConn)
    {
        parent::__construct($dbConn);      
    }

    /**
     * Página principal
     */
    public function index() 
    {
        // Si el usuario está logueado, redirigir al dashboard correspondiente
        if (isset($this->sessionManager) && $this->sessionManager->isLoggedIn()) {
            $role = $this->sessionManager->getUserRole();
            $this->redirectToDashboard($role);
            return;
        }
        
        // Si no está logueado, mostrar la página principal
        $this->render('index', 'index');
    }

    /**
     * Dashboard por defecto (cuando se accede sin especificar vista)
     */
    public function dashboard() 
    {
        // Si el usuario está logueado, redirigir al dashboard correspondiente
        if (isset($this->sessionManager) && $this->sessionManager->isLoggedIn()) {
            $role = $this->sessionManager->getUserRole();
            $this->redirectToDashboard($role);
            return;
        }
        
        // Si no está logueado, redirigir al login
        header('Location: ' . url . '?view=index&action=login');
        exit;
    }

    /**
     * Página de login
     */
    public function login() 
    {
        // Si ya está logueado, redirigir al dashboard correspondiente
        if (isset($this->sessionManager) && $this->sessionManager->isLoggedIn()) {
            $role = $this->sessionManager->getUserRole();
            $this->redirectToDashboard($role);
            return;
        }
        
        $this->render('index', 'login');
    }

    /**
     * Página de registro
     */
    public function register() 
    {
        $this->render('index', 'register');
    }

    /**
     * Página de contacto
     */
    public function contact() 
    {
        $this->render('index', 'contact');
    }

    /**
     * Página about
     */
    public function about() 
    {
        $this->render('index', 'about');
    }

    /**
     * Página de planes
     */
    public function plans() 
    {
        $this->render('index', 'plans');
    }

    /**
     * Página FAQ
     */
    public function faq() 
    {
        $this->render('index', 'faq');
    }

    /**
     * Página de forgot password
     */
    public function forgotPassword() 
    {
        $this->render('index', 'forgotPassword');
    }

    /**
     * Página de reset password
     */
    public function resetPassword() 
    {
        $this->render('index', 'resetPassword');
    }

    /**
     * Página de complete profile
     */
    public function completeProf() 
    {
        $this->render('index', 'completeProf');
    }

    /**
     * Redirige al dashboard correspondiente según el rol
     */
    private function redirectToDashboard($role) 
    {
        $dashboardUrls = [
            'root' => '?view=rootDashboard',
            'director' => '?view=directorDashboard',
            'coordinator' => '?view=coordinatorDashboard',
            'teacher' => '?view=teacherDashboard',
            'student' => '?view=studentDashboard',
            'parent' => '?view=parentDashboard',
            'treasurer' => '?view=treasurerDashboard'
        ];

        $url = $dashboardUrls[$role] ?? '?view=index&action=login';
        header('Location: ' . $url);
        exit;
    }

    /**
     * Carga una vista parcial vía AJAX
     * Útil para cargar contenido en dashboards sin header y footer
     */
    public function loadPartial()
    {
        // Obtener parámetros
        $view = $_POST['view'] ?? $_GET['view'] ?? '';
        $action = $_POST['action'] ?? $_GET['action'] ?? 'index';
        $partialView = $_POST['partialView'] ?? $_GET['partialView'] ?? '';
        $force = isset($_POST['force']) || isset($_GET['force']); // Permitir forzar la carga
        
        // Debug
        error_log("DEBUG loadPartial - view: $view, action: $action, partialView: $partialView");
        
        // Verificar que sea una petición AJAX o esté forzada
        if (!$this->isAjaxRequest() && !$force) {
            // Si no es AJAX, mostrar un mensaje de error más informativo
            if (empty($view)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=index&action=loadPartial&view=viewname&action=accionname</div>';
                return;
            }
            
            // Si se especifica una vista, cargarla directamente
            $viewPath = $view . '/' . $action;
            $fullPath = ROOT . "/app/views/{$viewPath}.php";
            
            if (!file_exists($fullPath)) {
                echo '<div class="alert alert-danger">Vista no encontrada: ' . htmlspecialchars($viewPath) . '</div>';
                return;
            }
            
            // Cargar la vista parcial directamente
            try {
                $this->loadPartialView($viewPath);
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error al cargar la vista: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            return;
        }
        
        // Si no hay vista especificada, intentar usar partialView
        if (empty($view) && !empty($partialView)) {
            $view = $partialView;
        }
        
        if (empty($view)) {
            $this->sendJsonResponse(false, 'Vista no especificada');
            return;
        }
        
        // Construir la ruta de la vista
        $viewPath = $view . '/' . $action;
        
        // Verificar que el archivo existe
        $fullPath = ROOT . "/app/views/{$viewPath}.php";
        
        error_log("DEBUG loadPartial - Intentando cargar: $fullPath");
        
        if (!file_exists($fullPath)) {
            $this->sendJsonResponse(false, "Vista no encontrada: {$viewPath}");
            return;
        }
        
        // Cargar la vista parcial
        try {
            $this->loadPartialView($viewPath);
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al cargar la vista: ' . $e->getMessage());
        }
    }
}