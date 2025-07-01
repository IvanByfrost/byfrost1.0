<?php
require_once 'models/rootModel.php';

class RoleController extends MainController {

    private $model;

    public function __construct($dbConn, $view = null)
    {
        parent::__construct($dbConn);
        $this->view = $view;
        $this->model = new RootModel();
    }

    // Muestra todos los roles disponibles
    public function index() {
        try {
            $roles = $this->model->getAllRoleTypes();
            include 'views/role/index.php';
        } catch (Exception $e) {
            error_log("Error en RoleController::index: " . $e->getMessage());
            include 'views/Error/500.php';
        }
    }

    // Muestra los permisos actuales para un role_type
    public function edit($role_type = null) {
        try {
            // Si no se proporciona role_type, mostrar selector
            if (!$role_type) {
                $role_type = $_GET['role_type'] ?? null;
            }
            
            if ($role_type) {
                $permissions = $this->model->getPermissionsByRole($role_type);
                include 'views/role/editRole.php';
            } else {
                // Mostrar selector de roles
                include 'views/role/editRole.php';
            }
        } catch (Exception $e) {
            error_log("Error en RoleController::edit: " . $e->getMessage());
            include 'views/Error/500.php';
        }
    }

    // Actualiza los permisos del role_type
    public function update($role_type = null) {
        try {
            if (!$role_type) {
                $role_type = $_POST['role_type'] ?? null;
            }
            
            if (!$role_type) {
                throw new Exception("Tipo de rol no especificado");
            }
            
            $data = [
                'can_create' => isset($_POST['can_create']) ? 1 : 0,
                'can_read'   => isset($_POST['can_read']) ? 1 : 0,
                'can_update' => isset($_POST['can_update']) ? 1 : 0,
                'can_delete' => isset($_POST['can_delete']) ? 1 : 0
            ];

            $this->model->updatePermissions($role_type, $data);
            header("Location: /?controller=role&action=index");
            exit;
        } catch (Exception $e) {
            error_log("Error en RoleController::update: " . $e->getMessage());
            include 'views/Error/500.php';
        }
    }
}
