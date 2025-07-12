<?php
require_once 'app/controllers/MainController.php';

class IndexController extends MainController
{
    public function __construct($dbConn)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct($dbConn);
    }

    public function index()
    {
        if (isset($this->sessionManager) && $this->sessionManager->isLoggedIn()) {
            $role = $this->sessionManager->getUserRole();
            $this->redirectToDashboard($role);
            return;
        }

        $this->render('index', 'index');
    }

    public function dashboard()
    {
        if (isset($this->sessionManager) && $this->sessionManager->isLoggedIn()) {
            $role = $this->sessionManager->getUserRole();
            $this->redirectToDashboard($role);
            return;
        }

        header('Location: ' . url . '?view=index&action=login');
        exit;
    }

    public function login()
    {
        if (isset($this->sessionManager) && $this->sessionManager->isLoggedIn()) {
            $role = $this->sessionManager->getUserRole();
            $this->redirectToDashboard($role);
            return;
        }

        $this->render('index', 'login');
    }

    public function register() { $this->render('index', 'register'); }
    public function contact() { $this->render('index', 'contact'); }
    public function about() { $this->render('index', 'about'); }
    public function plans() { $this->render('index', 'plans'); }
    public function faq() { $this->render('index', 'faq'); }
    public function forgotPassword() { $this->render('index', 'forgotPassword'); }
    public function resetPassword() { $this->render('index', 'resetPassword'); }
    public function completeProf() { $this->render('index', 'completeProf'); }

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

    public function loadPartial()
    {
        $view = isset($_POST['view']) ? htmlspecialchars($_POST['view']) :
               (isset($_GET['view']) ? htmlspecialchars($_GET['view']) : '');

        $action = isset($_POST['action']) ? htmlspecialchars($_POST['action']) :
                  (isset($_GET['action']) ? htmlspecialchars($_GET['action']) : 'index');

        $partialView = isset($_POST['partialView']) ? htmlspecialchars($_POST['partialView']) :
                       (isset($_GET['partialView']) ? htmlspecialchars($_GET['partialView']) : '');

        $force = (isset($_POST['force']) && htmlspecialchars($_POST['force'])) ||
                 (isset($_GET['force']) && htmlspecialchars($_GET['force']));

        error_log("DEBUG loadPartial - view: $view, action: $action, partialView: $partialView");

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($view)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=index&action=loadPartial&view=viewname&action=accionname</div>';
                return;
            }

            $viewPath = rtrim($view, '/') . '/' . $action;
            $fullPath = ROOT . "/app/views/{$viewPath}.php";

            if (!file_exists($fullPath)) {
                echo '<div class="alert alert-danger">Vista no encontrada: ' . htmlspecialchars($viewPath) . '</div>';
                return;
            }

            try {
                $this->loadPartialView($viewPath);
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error al cargar la vista: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            return;
        }

        if (empty($view) && !empty($partialView)) {
            $view = $partialView;
        }

        if (empty($view)) {
            $this->sendJsonResponse(false, 'Vista no especificada');
            return;
        }

        $viewPath = rtrim($view, '/') . '/' . $action;
        $fullPath = ROOT . "/app/views/{$viewPath}.php";

        error_log("DEBUG loadPartial - Intentando cargar: $fullPath");

        if (!file_exists($fullPath)) {
            $this->sendJsonResponse(false, "Vista no encontrada: {$viewPath}");
            return;
        }

        try {
            $this->loadPartialView($viewPath);
            exit;
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Error al cargar la vista: ' . $e->getMessage());
        }
    }
}
