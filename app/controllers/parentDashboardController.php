<?php
require_once 'app/controllers/MainController.php';

class ParentDashboardController extends MainController
{
    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct($dbConn);
        
        // Verificar permisos de parent
        if (!$this->sessionManager->isLoggedIn() || $this->sessionManager->getUserRole() !== 'parent') {
            header('Location: ' . url . '?view=index&action=login');
            exit;
        }
    }

    /**
     * Dashboard principal de parent
     */
    public function dashboard()
    {
        try {
            // Obtener estadísticas para el dashboard
            $stats = $this->getDashboardStats();
            
            // Cargar la vista del dashboard
            $this->loadDashboardView('parent/dashboard', [
                'stats' => $stats,
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en dashboard de acudiente: " . $e->getMessage());
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
            $parentId = $this->sessionManager->getCurrentUser()['id'];
            
            // Total de hijos
            $stmt = $this->dbConn->prepare("
                SELECT COUNT(*) as total 
                FROM students 
                WHERE parent_id = ? AND status = 'active'
            ");
            $stmt->execute([$parentId]);
            $stats['totalChildren'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return $stats;
            
        } catch (Exception $e) {
            ErrorHandler::logError("Error obteniendo estadísticas de acudiente: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Ver progreso de hijos
     */
    public function childrenProgress()
    {
        try {
            $this->loadDashboardView('parent/childrenProgress', [
                'currentUser' => $this->sessionManager->getCurrentUser()
            ]);
        } catch (Exception $e) {
            ErrorHandler::logError("Error en progreso de hijos: " . $e->getMessage());
            $this->loadView('Error/500', ['error' => $e->getMessage()]);
        }
    }

    // El método loadPartial() se hereda del MainController
} 