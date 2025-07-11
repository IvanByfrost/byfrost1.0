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
        try {
            // Obtener estadísticas para el dashboard
            $stats = $this->getDashboardStats();
            
            // Cargar la vista del dashboard
            $this->loadDashboardView('teacher/dashboard', [
                'stats' => $stats,
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en dashboard de profesor: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
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
            ErrorHandler::logError("Error obteniendo estadísticas de profesor: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Evaluar estudiantes
     */
    public function assessStudents()
    {
        try {
            $this->loadDashboardView('teacher/assessStudent', [
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en evaluación de estudiantes: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Ver horario
     */
    public function viewSchedule()
    {
        try {
            $this->loadDashboardView('teacher/readSchedule', [
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en vista de horario: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    // El método loadPartial() se hereda del MainController
} 