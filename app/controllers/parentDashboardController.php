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

    /**
     * Carga una vista parcial vía AJAX para el dashboard de acudiente
     */
    public function loadPartial()
    {
        $view = htmlspecialchars($_POST['view']) ?? htmlspecialchars($_GET['view']) ?? '';
        $action = htmlspecialchars($_POST['action']) ?? htmlspecialchars($_GET['action']) ?? 'index';
        $force = isset(htmlspecialchars($_POST['force'])) || isset(htmlspecialchars($_GET['force']));

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($view)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=parentDashboard&action=loadPartial&view=modulo&action=vista</div>';
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