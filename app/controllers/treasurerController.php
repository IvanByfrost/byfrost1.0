<?php
require_once 'app/library/SessionManager.php';
require_once 'app/library/SecurityMiddleware.php';
require_once 'app/models/payrollModel.php';

require_once 'app/controllers/MainController.php';

class TreasurerController extends MainController {
    private $payrollModel;
    
    public function __construct($dbConn) {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct($dbConn);
        $this->payrollModel = new PayrollModel();
    }
    
    /**
     * Dashboard del tesorero
     */
    public function dashboard() {
        if (!$this->sessionManager->hasRole('treasurer')) {
            header('Location: index.php?controller=error&action=unauthorized');
            exit;
        }
        
        try {
            // Obtener estadísticas básicas
            $stats = [
                'total_employees' => 0,
                'total_payroll' => 0,
                'active_periods' => 0,
                'reports_generated' => 0
            ];
            
            $data = [
                'stats' => $stats,
                'page_title' => 'Dashboard del Tesorero'
            ];
            
            $this->loadDashboardView('treasurer/dashboard', $data);
            
        } catch (Exception $e) {
            $this->loadDashboardView('Error/error', ['error' => $e->getMessage()]);
        }
    }
}
?> 