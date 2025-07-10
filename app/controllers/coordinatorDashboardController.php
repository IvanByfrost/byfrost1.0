<?php
require_once 'app/controllers/MainController.php';

class CoordinatorDashboardController extends MainController
{
    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
        
        // Verificar permisos de coordinator
        if (!$this->sessionManager->isLoggedIn() || $this->sessionManager->getUserRole() !== 'coordinator') {
            header('Location: ' . url . '?view=index&action=login');
            exit;
        }
    }

    /**
     * Dashboard principal de coordinator
     */
    public function dashboard()
    {
        // Obtener estadísticas para el dashboard
        $stats = $this->getDashboardStats();
        
        // Cargar la vista del dashboard
        $this->loadDashboardView('coordinator/dashboard', [
            'stats' => $stats,
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Obtener estadísticas para el dashboard
     */
    private function getDashboardStats()
    {
        try {
            $stats = [];
            
            // Total de estudiantes
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM students WHERE status = 'active'");
            $stmt->execute();
            $stats['totalStudents'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de profesores
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM teachers WHERE status = 'active'");
            $stmt->execute();
            $stats['totalTeachers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de materias
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM subjects WHERE status = 'active'");
            $stmt->execute();
            $stats['totalSubjects'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas de coordinator: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Gestión de estudiantes
     */
    public function studentManagement()
    {
        $this->loadDashboardView('coordinator/studentManagement', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Gestión de profesores
     */
    public function teacherManagement()
    {
        $this->loadDashboardView('coordinator/teacherManagement', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Gestión de materias
     */
    public function subjectManagement()
    {
        $this->loadDashboardView('coordinator/subjectManagement', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Carga una vista parcial vía AJAX para el dashboard de coordinador
     */
    public function loadPartial()
    {
        $view = htmlspecialchars($_POST['view']) ?? htmlspecialchars($_GET['view']) ?? '';
        $action = htmlspecialchars($_POST['action']) ?? htmlspecialchars($_GET['action']) ?? 'index';
        $force = isset(htmlspecialchars($_POST['force'])) || isset(htmlspecialchars($_GET['force']));

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($view)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=coordinatorDashboard&action=loadPartial&view=modulo&action=vista</div>';
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
                echo '<div class="alert alert-danger">Error al cargar la vista: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            return;
        }
        if (empty($view)) {
            echo json_encode(['success' => false, 'message' => 'Vista no especificada']);
            return;
        }
        $viewPath = $view . '/' . $action;
        $fullPath = ROOT . "/app/views/{$viewPath}.php";
        if (!file_exists($fullPath)) {
            echo json_encode(['success' => false, 'message' => "Vista no encontrada: {$viewPath}"]);
            return;
        }
        try {
            $this->loadPartialView($viewPath);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al cargar la vista: ' . $e->getMessage()]);
        }
    }
} 