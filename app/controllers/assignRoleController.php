<?php
require_once ROOT . '/app/models/userModel.php';
require_once ROOT . '/app/controllers/MainController.php';

class AssignRoleController extends MainController {
    protected $db;

    public function __construct($dbConn) {
        parent::__construct($dbConn); // Para inicializar sessionManager
        $this->db = $dbConn;
    }

    public function assign($userId, $roleType) {
        // Si el usuario logueado es director, restringe los roles que puede asignar
        if ($this->sessionManager && $this->sessionManager->hasRole('director')) {
            $rolesPermitidos = ['coordinator', 'professor', 'parent', 'treasurer', 'student'];
            if (!in_array($roleType, $rolesPermitidos)) {
                http_response_code(403);
                echo "No tienes permiso para asignar el rol seleccionado.";
                exit;
            }
        }
        // Si el usuario logueado es coordinador, solo puede asignar estudiantes
        if ($this->sessionManager && $this->sessionManager->hasRole('coordinator')) {
            if ($roleType !== 'student') {
                http_response_code(403);
                echo "El coordinador solo puede asignar estudiantes.";
                exit;
            }
        }
        $userModel = new UserModel($this->db);
        return $userModel->assignRole($userId, $roleType);
    }

    public function searchUsersByDocument($credentialType, $credentialNumber) {
        $userModel = new UserModel($this->db);
        return $userModel->searchUsersByDocument($credentialType, $credentialNumber);
    }
}