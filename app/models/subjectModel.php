<?php
class subjectModel extends mainModel
{
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
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
    // Función para actualizar una asignatura
    // Función para eliminar una asignatura
}
