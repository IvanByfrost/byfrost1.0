<?php
require_once 'app/controllers/MainController.php';

class CoordinatorDashboardController extends MainController
{
    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
        try {
            // Obtener estadísticas para el dashboard
            $stats = $this->getDashboardStats();
            
            // Cargar la vista del dashboard
            $this->loadDashboardView('coordinator/dashboard', [
                'stats' => $stats,
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en dashboard de coordinador: " . $e->getMessage());
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
            
            // Total de estudiantes
            $stmt = $this->dbConn->prepare("
                SELECT COUNT(*) as total 
                FROM users u
                JOIN user_roles ur ON u.user_id = ur.user_id
                WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1
            ");
            $stmt->execute();
            $stats['totalStudents'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de profesores
            $stmt = $this->dbConn->prepare("
                SELECT COUNT(*) as total 
                FROM users u
                JOIN user_roles ur ON u.user_id = ur.user_id
                WHERE ur.role_type = 'professor' AND u.is_active = 1 AND ur.is_active = 1
            ");
            $stmt->execute();
            $stats['totalTeachers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de materias
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM subjects WHERE is_active = 1");
            $stmt->execute();
            $stats['totalSubjects'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return $stats;
            
        } catch (Exception $e) {
            ErrorHandler::logError("Error obteniendo estadísticas de coordinador: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Gestión de estudiantes
     */
    public function studentManagement()
    {
        try {
            $this->loadDashboardView('coordinator/studentManagement', [
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en gestión de estudiantes: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Gestión de profesores
     */
    public function teacherManagement()
    {
        try {
            $this->loadDashboardView('coordinator/teacherManagement', [
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en gestión de profesores: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Gestión de materias
     */
    public function subjectManagement()
    {
        try {
            $this->loadDashboardView('coordinator/subjectManagement', [
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en gestión de materias: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    // El método loadPartial() se hereda del MainController
} 