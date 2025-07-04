<?php
require_once 'MainController.php';

class RootController extends MainController
{
    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
    }

    /**
     * Dashboard principal del administrador root
     */
    public function dashboard()
    {
        error_log('DEBUG: Entrando a RootController::dashboard()');
        echo '<!-- DEBUG: Entrando a RootController::dashboard() -->';
        // Verificar que el usuario esté logueado y sea root
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
            header('Location: /?view=index&action=login');
            exit;
        }

        if (!$this->sessionManager->hasRole('root')) {
            header('Location: /?view=unauthorized');
            exit;
        }

        // Cargar la vista del dashboard con el header específico
        $this->loadDashboardView('root/dashboard');
    }

    /**
     * Carga una vista del dashboard con el header específico
     */
    private function loadDashboardView($viewPath, $data = [])
    {
        $viewPath = ROOT . "/app/views/{$viewPath}.php";
        
        if (file_exists($viewPath)) {
            extract($data);
            require ROOT . '/app/views/layouts/head.php';
            require ROOT . '/app/views/layouts/dashHeader.php'; // Header específico del dashboard
            require $viewPath;
            require ROOT . '/app/views/layouts/dashFooter.php'; // Footer específico del dashboard
        } else {
            // Redirigir a la página de error 404
            http_response_code(404);
            require_once ROOT . '/app/controllers/errorController.php';
            $error = new ErrorController($this->dbConn);
            $error->Error('404');
        }
    }

    /**
     * Página de configuración del sistema
     */
    public function configuration()
    {
        $this->protectRoot();
        $this->loadView('root/configuration');
    }

    /**
     * Menú principal del root
     */
    public function menuRoot()
    {
        $this->protectRoot();
        $this->loadPartialView('root/menuRoot');
    }

    /**
     * Método por defecto
     */
    public function index()
    {
        $this->dashboard();
    }

    /**
     * Protección de acceso solo para root
     */
    private function protectRoot() 
    {
        if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn() || !$this->sessionManager->hasRole('root')) {
            header('Location: /?view=unauthorized');
            exit;
        }
    }
}