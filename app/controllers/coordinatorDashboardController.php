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
} 