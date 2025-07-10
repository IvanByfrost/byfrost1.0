<?php
require_once 'app/controllers/MainController.php';

class TreasurerDashboardController extends MainController
{
    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
        
        // Verificar permisos de treasurer
        if (!$this->sessionManager->isLoggedIn() || $this->sessionManager->getUserRole() !== 'treasurer') {
            header('Location: ' . url . '?view=index&action=login');
            exit;
        }
    }

    /**
     * Dashboard principal de treasurer
     */
    public function dashboard()
    {
        // Obtener estadísticas para el dashboard
        $stats = $this->getDashboardStats();
        
        // Cargar la vista del dashboard
        $this->loadDashboardView('treasurer/dashboard', [
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
            
            // Total de pagos
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM student_payments");
            $stmt->execute();
            $stats['totalPayments'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de pagos pendientes
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM student_payments WHERE status = 'pending'");
            $stmt->execute();
            $stats['pendingPayments'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de pagos completados
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM student_payments WHERE status = 'completed'");
            $stmt->execute();
            $stats['completedPayments'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas de treasurer: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Gestión de pagos
     */
    public function paymentManagement()
    {
        $this->loadDashboardView('treasurer/paymentManagement', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Reportes financieros
     */
    public function financialReports()
    {
        $this->loadDashboardView('treasurer/financialReports', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }
} 