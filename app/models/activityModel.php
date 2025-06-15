<?php
// Modelo para manejar a las actividades
require_once 'mainModel.php';

class ActivityModel extends MainModel
{
    // Constructor de la clase 
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }
    //Función para crear una actividad.
    public function createActivity() {
        
    }
    //Función para consultar actividades. 
    public function getActivities()
    {
        $query = "SELECT * FROM activity";
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //Función para actualizar una actividad
        public function updateActivity() {
        
    }
    //Función para eliminar una actividad
        public function deleteActivity() {
        
    }
}
