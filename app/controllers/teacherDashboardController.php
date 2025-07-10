<?php
require_once 'app/controllers/MainController.php';

class TeacherDashboardController extends MainController
{
    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct($dbConn);
        
        // Verificar permisos de teacher
        if (!$this->sessionManager->isLoggedIn() || $this->sessionManager->getUserRole() !== 'teacher') {
            header('Location: ' . url . '?view=index&action=login');
            exit;
        }
    }

    /**
     * Dashboard principal de teacher
     */
    public function dashboard()
    {
        // Obtener estadísticas para el dashboard
        $stats = $this->getDashboardStats();
        
        // Cargar la vista del dashboard
        $this->loadDashboardView('teacher/dashboard', [
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
            $teacherId = $this->sessionManager->getCurrentUser()['id'];
            
            // Total de materias asignadas
            $stmt = $this->dbConn->prepare("
                SELECT COUNT(*) as total 
                FROM teacher_subjects 
                WHERE teacher_id = ?
            ");
            $stmt->execute([$teacherId]);
            $stats['totalSubjects'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de estudiantes
            $stmt = $this->dbConn->prepare("
                SELECT COUNT(DISTINCT s.id) as total 
                FROM students s
                JOIN student_subjects ss ON s.id = ss.student_id
                JOIN teacher_subjects ts ON ss.subject_id = ts.subject_id
                WHERE ts.teacher_id = ? AND s.status = 'active'
            ");
            $stmt->execute([$teacherId]);
            $stats['totalStudents'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas de teacher: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Evaluar estudiantes
     */
    public function assessStudents()
    {
        $this->loadDashboardView('teacher/assessStudent', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Ver horario
     */
    public function viewSchedule()
    {
        $this->loadDashboardView('teacher/readSchedule', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Carga una vista parcial vía AJAX para el dashboard de docente
     */
    public function loadPartial()
    {
        $view = htmlspecialchars($_POST['view']) ?? htmlspecialchars($_GET['view']) ?? '';
        $action = htmlspecialchars($_POST['action']) ?? htmlspecialchars($_GET['action']) ?? 'index';
        $force = isset(_POST['force']) && htmlspecialchars(_POST['force']) || isset(_GET['force']) && htmlspecialchars(_GET['force']);

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($view)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=teacherDashboard&action=loadPartial&view=modulo&action=vista</div>';
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