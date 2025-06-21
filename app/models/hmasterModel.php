<?php
// software_academico/app/models/RectorModel.php

class hmasterModel {
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    // Obtener todos los rectores
    public function getAllHmaster() {
        $sql = "SELECT * FROM headmaster";
        $result = $this->dbConn->query($sql);
        $headMaster = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $headMaster[] = $row;
            }
        }
        return $headMaster;
    }

    // Obtener un rector por ID
    public function getRectorById($document) {
        $sql = "SELECT * FROM headmaster WHERE doc_hmaster = :document";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bind_param("i", $document);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Insertar un nuevo rector
    public function createHmaster($userName, $userLastName, $userEmail, $password, $phoneUser = null) {
        // Se asume que la contraseña ya viene hasheada desde el controlador
        $sql = "INSERT INTO headmaster (userId) VALUES ()";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bind_param("sssss", $userName, $userLastName, $userEmail, $password, $phoneUser);
        return $stmt->execute();
    }

    // Actualizar un rector
    public function headMasterUpdate($id, $userName, $userLastName, $userEmail, $phoneUser = null) {
        $sql = "UPDATE rectores SET nombre = ?, apellido = ?, email = ?, telefono = ? WHERE id_rector = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssi", $userName, $userLastName, $userEmail, $phoneUser, $id);
        return $stmt->execute();
    }

    // Eliminar un rector
    public function deleteHeadMaster($id) {
        $sql = "DELETE FROM rectores WHERE id_rector = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function __destruct() {
        $this->conexion->close();
    }
}
?>