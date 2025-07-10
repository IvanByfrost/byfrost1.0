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
} 