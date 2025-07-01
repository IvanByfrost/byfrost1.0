<?php
require_once 'models/Role.php';

class RoleController extends mainController {

    private $model;

    public function __construct($dbConn, $view = null)
    {
        $this->dbConn = $dbConn;
        $this->view = $view;
    }

    // Muestra todos los roles disponibles
    public function index() {
        $roles = $this->model->getAllRoleTypes();
        include 'views/role/index.php';
    }

    // Muestra los permisos actuales para un role_type
    public function edit($role_type) {
        $permissions = $this->model->getPermissionsByRole($role_type);
        include 'views/role/edit.php';
    }

    // Actualiza los permisos del role_type
    public function update($role_type) {
        $data = [
            'can_create' => isset($_POST['can_create']) ? 1 : 0,
            'can_read'   => isset($_POST['can_read']) ? 1 : 0,
            'can_update' => isset($_POST['can_update']) ? 1 : 0,
            'can_delete' => isset($_POST['can_delete']) ? 1 : 0
        ];

        $this->model->updatePermissions($role_type, $data);
        header("Location: /?controller=role&action=index");
        exit;
    }
}
