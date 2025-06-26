<?php

require_once 'app/models/coordinatorModel.php';
require_once 'app/controllers/MainController.php';

class CoordinatorController extends MainController {
    protected $model;

    public function __construct($dbConn, $view) {
        parent::__construct($dbConn, $view);
        $this->model = new CoordinatorModel();
    }

    public function showDashCoord() {
        $data = $this->model->getData();
        $this->render('coordinator', 'dashboard', ['data' => $data]);
    }

    //Función para crear un coordinador
    //Función para consultar un coordinador
    //Función para actualizar un coordinador
    //Función para eliminar un coordinador
}
