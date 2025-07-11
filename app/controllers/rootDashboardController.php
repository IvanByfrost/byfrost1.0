<?php
require_once 'app/controllers/MainController.php';

class RootDashboardController extends MainController
{
    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
        error_log('DASHBOARD ROOT - SESSION: ' . print_r($_SESSION, true));
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
            
            // Total de usuarios activos
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM users WHERE is_active = 1");
            $stmt->execute();
            $stats['totalUsers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total de escuelas activas
            $stmt = $this->dbConn->prepare("SELECT COUNT(*) as total FROM schools WHERE is_active = 1");
            $stmt->execute();
            $stats['totalSchools'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Usuarios por rol
            $stmt = $this->dbConn->prepare("
                SELECT ur.role_type as role, COUNT(*) as count 
                FROM users u
                JOIN user_roles ur ON u.user_id = ur.user_id
                WHERE u.is_active = 1 AND ur.is_active = 1
                GROUP BY ur.role_type
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
        $view = htmlspecialchars($_POST['view']) ?? htmlspecialchars($_GET['view']) ?? '';
        $action = htmlspecialchars($_POST['action']) ?? htmlspecialchars($_GET['action']) ?? 'index';
        $force = isset($_POST['force']) && htmlspecialchars($_POST['force']) || isset($_GET['force']) && htmlspecialchars($_GET['force']);

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