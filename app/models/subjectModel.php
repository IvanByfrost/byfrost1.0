<?php
class subjectModel extends mainModel
{
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    //Función para obtener todas las asignaturas
    // Función para crear una asignatura
    // Función para actualizar una asignatura
    // Función para eliminar una asignatura
}
