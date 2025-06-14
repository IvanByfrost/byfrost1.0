<?php
// Modelo para manejar a las actividades
require_once 'mainModel.php';
class ActivityModel extends MainModel {
//Función para crear una actividad.

//Función para consultar actividades. 
    public function getActivities(){
        $query = "SELECT * FROM activity";
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//Función para actualizar una actividad
//Función para eliminar una actividad
}
?>