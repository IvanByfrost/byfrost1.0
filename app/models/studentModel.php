<?php
require_once 'mainModel.php';

class studentModel extends mainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //Función para crear un estudiante
    public function createStudent($data) {
        //Implementar la lógica para crear un estudiante
    }
    //Función para consultar un estudiante
    public function getStudentById($id) {
        return parent::getByField('users', 'user_id', $id);
    }
    //Función para actualizar un estudiante
    //Función para eliminar un estudiante
}
