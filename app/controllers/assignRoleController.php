<?php
require_once ROOT . '/app/models/userModel.php';
require_once ROOT . '/app/controllers/MainController.php';

class AssignRoleController extends MainController {
    protected $db;

    public function __construct($dbConn) {
        $this->db = $dbConn;
    }

    public function assign($userId, $roleType) {
        $userModel = new UserModel($this->db);
        return $userModel->assignRole($userId, $roleType);
    }

        public function searchUsersByDocument($credentialType, $credentialNumber) {
        $userModel = new UserModel($this->db);
        return $userModel->searchUsersByDocument($credentialType, $credentialNumber);
    }
}