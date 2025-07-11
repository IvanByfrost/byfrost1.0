<?php
require_once ROOT . '/app/models/rootModel.php';

class RoleController extends MainController {

    private $model;

    public function __construct($dbConn, $view = null)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct($dbConn);
        $this->view = $view;
        $this->model = new RootModel();
    }

    // Muestra todos los roles disponibles
    public function index() {
        try {
            $roles = $this->model->getAllRoleTypes();
            include ROOT . '/app/views/role/listRoles.php';
        } catch (Exception $e) {
            error_log("Error en RoleController::index: " . $e->getMessage());
            include ROOT . '/app/views/Error/500.php';
        }
    }

    // Muestra los permisos actuales para un role_type
    public function edit($role_type = null) {
        try {
            // Si no se proporciona role_type, mostrar selector
            if (!$role_type) {
                $role_type = htmlspecialchars($_GET['role_type']) ?? null;
            }
            
            if ($role_type) {
                $permissions = $this->model->getPermissionsByRole($role_type);
            }
            
            include ROOT . '/app/views/role/editRole.php';
        } catch (Exception $e) {
            error_log("Error en RoleController::edit: " . $e->getMessage());
            include ROOT . '/app/views/Error/500.php';
        }
    }

    // Actualiza los permisos del role_type
    public function update($role_type = null) {
        try {
            if (!$role_type) {
                $role_type = htmlspecialchars($_POST['role_type']) ?? null;
            }
            
            if (!$role_type) {
                throw new Exception("Tipo de rol no especificado");
            }
            
            $data = [
                'can_create' => isset($_POST['can_create']) && htmlspecialchars($_POST['can_create']) ? 1 : 0,
'can_read'   => isset($_POST['can_read']) && htmlspecialchars($_POST['can_read']) ? 1 : 0,
'can_update' => isset($_POST['can_update']) && htmlspecialchars($_POST['can_update']) ? 1 : 0,
'can_delete' => isset($_POST['can_delete']) && htmlspecialchars($_POST['can_delete']) ? 1 : 0
            ];

            $this->model->updatePermissions($role_type, $data);
            
            // Detectar si es una petición AJAX
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            if ($isAjax) {
                // Para peticiones AJAX, devolver JavaScript para redirigir
                echo "<script>loadView('role/index');</script>";
            } else {
                // Para peticiones normales, redirigir con header
                header("Location: " . url . "?view=role&action=index");
            }
            exit;
        } catch (Exception $e) {
            error_log("Error en RoleController::update: " . $e->getMessage());
            include ROOT . '/app/views/Error/500.php';
        }
    }

    /**
     * Carga una vista parcial vía AJAX para el módulo role
     */
    public function loadPartial()
    {
        $view = htmlspecialchars($_POST['view']) ?? htmlspecialchars($_GET['view']) ?? '';
        $action = htmlspecialchars($_POST['action']) ?? htmlspecialchars($_GET['action']) ?? 'index';
        $partialView = htmlspecialchars($_POST['partialView']) ?? htmlspecialchars($_GET['partialView']) ?? '';
        $force = isset($_POST['force']) && htmlspecialchars($_POST['force']) || isset($_GET['force']) && htmlspecialchars($_GET['force']);

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($partialView)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=role&action=loadPartial&partialView=vista</div>';
                return;
            }
            $viewPath = 'role/' . $partialView;
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
        if (empty($partialView)) {
            echo json_encode(['success' => false, 'message' => 'Vista no especificada']);
            return;
        }
        $viewPath = 'role/' . $partialView;
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
