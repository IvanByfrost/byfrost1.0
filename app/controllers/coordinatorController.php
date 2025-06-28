<?php

require_once 'app/models/coordinatorModel.php';
require_once 'app/controllers/MainController.php';

class CoordinatorController extends MainController {
    protected $model;

    public function __construct($dbConn) {
        parent::__construct($dbConn);
        $this->model = new CoordinatorModel();
    }

    public function showDashCoord() {
        // Verificar si el usuario está logueado
        if (!$this->sessionManager->isLoggedIn()) {
            $this->redirect('/login');
            return;
        }
        
        // Verificar si el usuario tiene rol de coordinador
        if (!$this->sessionManager->hasRole('coordinator')) {
            $this->redirect('/unauthorized');
            return;
        }
        
        // Obtener datos del usuario para la vista
        $user = $this->sessionManager->getCurrentUser();
        
        $data = $this->model->getData();
        $this->render('coordinator', 'dashboard', [
            'data' => $data,
            'user' => $user
        ]);
    }

    //Función para crear un coordinador
    //Función para consultar un coordinador
    //Función para actualizar un coordinador
    //Función para eliminar un coordinador
}
