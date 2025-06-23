<?php
require_once 'mainModel.php';

class subjectModel extends mainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //Función para obtener todas las asignaturas
    public function getAllSubjects()
    {
        $query = "SELECT * FROM subjects";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Función para crear una asignatura
    public function createSubject(){
        //
    }
    
    // Función para actualizar una asignatura
    // Función para eliminar una asignatura
}
