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

    /**
     * Carga una vista parcial vía AJAX para el dashboard de tesorero
     */
    public function loadPartial()
    {
        $view = $_POST['view'] ?? $_GET['view'] ?? '';
        $action = $_POST['action'] ?? $_GET['action'] ?? 'index';
        $force = isset($_POST['force']) || isset($_GET['force']);

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($view)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=treasurerDashboard&action=loadPartial&view=modulo&action=vista</div>';
                return;
            }
            $viewPath = $view . '/' . $action;
            $fullPath = ROOT . "/app/views/{$viewPath}.php";
            if (!file_exists($fullPath)) {
                echo '<div class="alert alert-danger">Vista no encontrada: ' . htmlspecialchars($viewPath) . '</div>';
                return;
            }
            try {
                $this->loadPartialView($viewPath);
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error al cargar la vista: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            return;
        }
        if (empty($view)) {
            echo json_encode(['success' => false, 'message' => 'Vista no especificada']);
            return;
        }
        $viewPath = $view . '/' . $action;
        $fullPath = ROOT . "/app/views/{$viewPath}.php";
        if (!file_exists($fullPath)) {
            echo json_encode(['success' => false, 'message' => "Vista no encontrada: {$viewPath}"]);
            return;
        }
        try {
            $this->loadPartialView($viewPath);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al cargar la vista: ' . $e->getMessage()]);
        }
    }
} 