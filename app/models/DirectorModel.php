<?php
require_once 'mainModel.php';

// software_academico/app/models/RectorModel.php

class DirectorModel extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    // Obtener todos los rectores
    public function getAllDirector()
    {
        $sql = "SELECT * FROM headmaster";
        $stmt = $this->dbConn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un rector por ID
    public function getRectorById($document)
    {
        $sql = "SELECT * FROM headmaster WHERE doc_hmaster = :document";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute(['document' => $document]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insertar un nuevo rector
    public function createDirector($userName, $userLastName, $userEmail, $password, $phoneUser = null)
    {
        // Se asume que la contraseña ya viene hasheada desde el controlador
        $sql = "INSERT INTO rectores (nombre, apellido, email, password, telefono) VALUES (:userName, :userLastName, :userEmail, :password, :phoneUser)";
        $stmt = $this->dbConn->prepare($sql);
        return $stmt->execute([
            'userName' => $userName,
            'userLastName' => $userLastName,
            'userEmail' => $userEmail,
            'password' => $password,
            'phoneUser' => $phoneUser
        ]);
    }

    // Actualizar un rector
    public function directorUpdate($id, $userName, $userLastName, $userEmail, $phoneUser = null)
    {
        $sql = "UPDATE rectores SET nombre = :userName, apellido = :userLastName, email = :userEmail, telefono = :phoneUser WHERE id_rector = :id";
        $stmt = $this->dbConn->prepare($sql);
        return $stmt->execute([
            'userName' => $userName,
            'userLastName' => $userLastName,
            'userEmail' => $userEmail,
            'phoneUser' => $phoneUser,
            'id' => $id
        ]);
    }

    // Eliminar un rector
    public function deleteHeadMaster($id)
    {
        $sql = "DELETE FROM rectores WHERE id_rector = :id";
        $stmt = $this->dbConn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
