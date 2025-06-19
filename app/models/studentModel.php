<?php
class studentModel extends MainModel
{
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    //Función para crear un estudiante
    //Función para consultar un estudiante
    //Función para actualizar un estudiante
    //Función para eliminar un estudiante
}
