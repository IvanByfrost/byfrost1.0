<?php
require_once 'app/library/SessionManager.php';
require_once 'app/library/SecurityMiddleware.php';
require_once 'app/models/payrollModel.php';

class TreasurerController {
    private $sessionManager;
    private $securityMiddleware;
    private $payrollModel;
    
    public function __construct() {
        $this->sessionManager = new SessionManager();
        $this->securityMiddleware = new SecurityMiddleware();
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
            
            $this->loadView('treasurer/dashboard', $data);
            
        } catch (Exception $e) {
            $this->loadView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Cargar vista
     */
    private function loadView($view, $data = []) {
        // Extraer variables del array de datos
        extract($data);
        
        // Incluir la vista
        $viewPath = ROOT . '/app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception('Vista no encontrada: ' . $view);
        }
    }
}
?> 