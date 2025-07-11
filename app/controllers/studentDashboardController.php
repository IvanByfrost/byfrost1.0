<?php
require_once 'app/controllers/MainController.php';

class StudentDashboardController extends MainController
{
    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
        try {
            // Obtener estadísticas para el dashboard
            $stats = $this->getDashboardStats();
            
            // Cargar la vista del dashboard
            $this->loadDashboardView('student/dashboard', [
                'stats' => $stats,
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en dashboard de estudiante: " . $e->getMessage());
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
            ErrorHandler::logError("Error obteniendo estadísticas de estudiante: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Ver historial académico
     */
    public function academicHistory()
    {
        try {
            $this->loadDashboardView('student/academicHistory', [
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en historial académico: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Ver horario
     */
    public function viewSchedule()
    {
        try {
            $this->loadDashboardView('student/schedule', [
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en vista de horario: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    // El método loadPartial() se hereda del MainController
} 