<?php
require_once 'models/coordinatorModel.php';

class CoordinatorController {
    protected $model;
    protected $dbConn;

    public function __construct($dbConn) {
        $this->dbConn = $dbConn;
        $this->model = new CoordinatorModel();
    }

    public function showDashCoord() {
        $data = $this->model->getData();
        require './vista/coordinator/dashboard.php';
    }

    //Función para crear un coordinador
    //Función para consultar un coordinador
    //Función para actualizar un coordinador
    //Función para eliminar un coordinador
}
