<?php
require_once ROOT . '/app/models/MainModel.php';

class SubjectModel extends MainModel
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
        //Aquí se implementa la lógica para crear una asignatura.
        $query = "INSERT INTO subjects 
        ";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    // Función para actualizar una asignatura
    // Función para eliminar una asignatura
}
