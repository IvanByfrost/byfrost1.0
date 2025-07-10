<?php
require_once 'app/controllers/MainController.php';

class StudentDashboardController extends MainController
{
    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
        
        // Verificar permisos de student
        if (!$this->sessionManager->isLoggedIn() || $this->sessionManager->getUserRole() !== 'student') {
            header('Location: ' . url . '?view=index&action=login');
            exit;
        }
    }

    /**
     * Dashboard principal de student
     */
    public function dashboard()
    {
        // Obtener estadísticas para el dashboard
        $stats = $this->getDashboardStats();
        
        // Cargar la vista del dashboard
        $this->loadDashboardView('student/dashboard', [
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
            $studentId = $this->sessionManager->getCurrentUser()['id'];
            
            // Total de materias inscritas
            $stmt = $this->dbConn->prepare("
                SELECT COUNT(*) as total 
                FROM student_subjects 
                WHERE student_id = ?
            ");
            $stmt->execute([$studentId]);
            $stats['totalSubjects'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Promedio general
            $stmt = $this->dbConn->prepare("
                SELECT AVG(score) as average 
                FROM student_scores 
                WHERE student_id = ?
            ");
            $stmt->execute([$studentId]);
            $stats['averageScore'] = $stmt->fetch(PDO::FETCH_ASSOC)['average'];
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas de student: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Ver historial académico
     */
    public function academicHistory()
    {
        $this->loadDashboardView('student/academicHistory', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Ver horario
     */
    public function viewSchedule()
    {
        $this->loadDashboardView('student/schedule', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Carga una vista parcial vía AJAX para el dashboard de estudiante
     */
    public function loadPartial()
    {
        $view = $_POST['view'] ?? $_GET['view'] ?? '';
        $action = $_POST['action'] ?? $_GET['action'] ?? 'index';
        $force = isset($_POST['force']) || isset($_GET['force']);

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($view)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=studentDashboard&action=loadPartial&view=modulo&action=vista</div>';
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