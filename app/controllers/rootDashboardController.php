<?php
require_once 'app/controllers/MainController.php';

class RootDashboardController extends MainController
{
    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
        
        // Verificar permisos de root
        if (!$this->sessionManager->isLoggedIn() || $this->sessionManager->getUserRole() !== 'root') {
            header('Location: ' . url . '?view=index&action=login');
            exit;
        }
    }

    /**
     * Dashboard principal de root
     */
    public function dashboard()
    {
        // Obtener estadísticas para el dashboard
        $stats = $this->getDashboardStats();
        
        // Cargar la vista del dashboard
        $this->loadDashboardView('root/dashboard', [
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
            
            // Total de usuarios
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM users WHERE status = 'active'");
            $stmt->execute();
            $stats['totalUsers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de escuelas
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM schools WHERE status = 'active'");
            $stmt->execute();
            $stats['totalSchools'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Usuarios por rol
            $stmt = $this->dbConn->prepare("
                SELECT role, COUNT(*) as count 
                FROM users 
                WHERE status = 'active' 
                GROUP BY role
            ");
            $stmt->execute();
            $stats['usersByRole'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas de root: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Gestión de usuarios
     */
    public function userManagement()
    {
        $this->loadDashboardView('root/userManagement', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Gestión de roles
     */
    public function roleManagement()
    {
        $this->loadDashboardView('root/roleManagement', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Configuración del sistema
     */
    public function systemSettings()
    {
        $this->loadDashboardView('root/systemSettings', [
            'currentUser' => $this->sessionManager->getCurrentUser()
        ]);
    }

    /**
     * Carga una vista parcial vía AJAX para el dashboard de root
     */
    public function loadPartial()
    {
        $view = $_POST['view'] ?? $_GET['view'] ?? '';
        $action = $_POST['action'] ?? $_GET['action'] ?? 'index';
        $force = isset($_POST['force']) || isset($_GET['force']);

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($view)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=rootDashboard&action=loadPartial&view=modulo&action=vista</div>';
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