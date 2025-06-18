<?php
class UserModel extends mainModel {
    // Constructor de la clase 
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    // Función para crear un usuario
    // Función para consultar un usuario
    // Función para actualizar un usuario
    // Función para eliminar un usuario
    // Función para validar un usuario
}
?>