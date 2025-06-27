<?php
require_once ROOT . '/app/models/userModel.php';
require_once 'app/controllers/MainController.php';
class assignRoleController extends MainController {
    protected $db;

    public function __construct($dbConn) {
        $this->db = $dbConn;
    }

    public function assign($userId, $roleId) {
        $userModel = new userModel();
        return $userModel->assignRole($userId, $roleId);
    }
}