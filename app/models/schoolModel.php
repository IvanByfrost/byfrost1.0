<?php
class SchoolModel extends MainModel {
        protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    //Función para crear un colegio
    //Función para consultar un colegio
    //Función para actualizar un colegio
    //Función para eliminar un colegio
}
?>