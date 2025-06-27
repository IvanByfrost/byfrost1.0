<?php
require_once 'mainModel.php';

class SchoolModel extends MainModel {
    public function __construct()
    {
        parent::__construct();
    }

    //Función para crear un colegio
    public function createSchool() {
        //Implementar la lógica para crear un colegio
        $query = "INSERT INTO schools (
        
        )
        VALUES (
            :school_name,
            :school_dane,
            :school_document
        )";
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([
            ':school_name' => $_POST['school_name'],
            ':school_dane' => $_POST['school_dane'],
            ':school_document' => $_POST['school_document']
        ]);

    }
    //Función para consultar un colegio
    //Función para actualizar un colegio
    //Función para eliminar un colegio
}
?>