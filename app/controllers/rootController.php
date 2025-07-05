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

        // Cargar la vista del dashboard usando el método centralizado
        $this->loadView('root/dashboard');
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