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
    public function Index() 
    {
        $this->render('index', 'index');
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
            'root' => '?view=root&action=dashboard',
            'director' => '?view=director&action=dashboard',
            'coordinator' => '?view=coordinator&action=dashboard',
            'teacher' => '?view=teacher&action=dashboard',
            'student' => '?view=student&action=dashboard',
            'parent' => '?view=parent&action=dashboard'
        ];

        $url = $dashboardUrls[$role] ?? '?view=index&action=login';
        header('Location: ' . $url);
        exit;
    }
}