<?php
require_once ROOT . '/app/models/rootModel.php';

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
            
            // Detectar si se está cargando como vista parcial (desde loadView)
            $isPartialView = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            if ($isPartialView) {
                // Cargar solo el contenido sin estructura HTML completa
                include ROOT . '/app/views/role/indexPartial.php';
            } else {
                // Cargar página completa
                include ROOT . '/app/views/role/index.php';
            }
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
                $role_type = $_GET['role_type'] ?? null;
            }
            
            // Detectar si se está cargando como vista parcial
            $isPartialView = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            if ($role_type) {
                $permissions = $this->model->getPermissionsByRole($role_type);
                if ($isPartialView) {
                    include ROOT . '/app/views/role/editRolePartial.php';
                } else {
                    include ROOT . '/app/views/role/editRole.php';
                }
            } else {
                // Mostrar selector de roles
                if ($isPartialView) {
                    include ROOT . '/app/views/role/editRolePartial.php';
                } else {
                    include ROOT . '/app/views/role/editRole.php';
                }
            }
        } catch (Exception $e) {
            error_log("Error en RoleController::edit: " . $e->getMessage());
            include ROOT . '/app/views/Error/500.php';
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
            
            // Detectar si se está cargando como vista parcial
            $isPartialView = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            if ($isPartialView) {
                // Para vistas parciales, redirigir usando JavaScript
                echo "<script>loadView('role/index');</script>";
            } else {
                // Para páginas completas, redirigir con header
                header("Location: " . url . "?controller=role&action=index");
            }
            exit;
        } catch (Exception $e) {
            error_log("Error en RoleController::update: " . $e->getMessage());
            include 'views/Error/500.php';
        }
    }
}
