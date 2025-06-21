<?php
// Modelo para manejar a los profesores
require_once 'mainModel.php';

class TeacherModel extends mainModel
{
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    // Función para obtener todos los profesores
    public function getTeachers()
    {
        $query = "SELECT * FROM professor";
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para crear un nuevo profesor. 
    public function createTeacher(){
        $query = "INSERT INTO professor () VALUES ()";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->rowCount() > 0; // Retorna true si se creó correctamente
    }

    // Función para obtener un profesor por Documento
    public function getTeachersbyDoc($document)
    {
        $query = "SELECT * FROM professor WHERE doc_professor = :document";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute(['document' => $document]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //Función para eliminar un profesor por Documento
    public function deleteTeacher($document)
    {
        $query = "DELETE FROM professor WHERE doc_professor = :document";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute(['document' => $document]);
    }
}
