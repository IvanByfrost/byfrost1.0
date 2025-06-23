<?php
require_once ROOT . '/app/models/userModel.php';

class assignRoleController {
    private $db;

    public function __construct($dbConn) {
        $this->db = $dbConn;
    }

    public function assign($userId, $roleId) {
        $userModel = new userModel();
        return $userModel->assignRole($userId, $roleId);
    }
}