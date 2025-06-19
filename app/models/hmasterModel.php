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
        $sql = "SELECT * FROM rectores";
        $resultado = $this->dbConn->query($sql);
        $headMaster = [];
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $headMaster[] = $fila;
            }
        }
        return $headMaster;
    }

    // Obtener un rector por ID
    public function getRectorById($id) {
        $sql = "SELECT * FROM rectores WHERE id_rector = ?";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // Insertar un nuevo rector
    public function createHmaster($userName, $userLastName, $userEmail, $password, $phoneUser = null) {
        // Se asume que la contraseña ya viene hasheada desde el controlador
        $sql = "INSERT INTO rectores (nombre, apellido, email, password, telefono) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
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