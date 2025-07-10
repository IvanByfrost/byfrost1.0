<?php
require_once 'app/controllers/MainController.php';

class ParentDashboardController extends MainController
{
    public function __construct($dbConn)
    {
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
        // Obtener estadísticas para el dashboard
        $stats = $this->getDashboardStats();
        
        // Cargar la vista del dashboard
        $this->loadDashboardView('parent/dashboard', [
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
            error_log("Error obteniendo estadísticas de parent: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Ver progreso de hijos
     */
    public function childrenProgress()
    {
        $this->loadDashboardView('parent/childrenProgress', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }
} 