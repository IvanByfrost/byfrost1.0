<?php
require_once 'models/CoordinatorModel.php';

class CoordinatorController {
    protected $model;
    protected $dbConn;

    public function __construct($dbConn) {
        $this->dbConn = $dbConn;
        $this->model = new CoordinatorModel($this->dbConn);
    }

    public function showDash() {
        $data = $this->model->getData();
        require './vista/coordinator/dashboard.php';
    }
}
