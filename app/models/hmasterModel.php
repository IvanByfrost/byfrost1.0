<?php
// software_academico/app/models/RectorModel.php

class hmasterModel {
    private $conexion;

    public function __construct() {
        $this->conexion = conectarDB(); // Función definida en config/database.php
    }

    // Obtener todos los rectores
    public function getAllRectores() {
        $sql = "SELECT * FROM rectores";
        $resultado = $this->conexion->query($sql);
        $rectores = [];
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $rectores[] = $fila;
            }
        }
        return $rectores;
    }

    // Obtener un rector por ID
    public function getRectorById($id) {
        $sql = "SELECT * FROM rectores WHERE id_rector = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // Insertar un nuevo rector
    public function createRector($nombre, $apellido, $email, $password, $telefono = null) {
        // Se asume que la contraseña ya viene hasheada desde el controlador
        $sql = "INSERT INTO rectores (nombre, apellido, email, password, telefono) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $apellido, $email, $password, $telefono);
        return $stmt->execute();
    }

    // Actualizar un rector
    public function updateRector($id, $nombre, $apellido, $email, $telefono = null) {
        $sql = "UPDATE rectores SET nombre = ?, apellido = ?, email = ?, telefono = ? WHERE id_rector = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $apellido, $email, $telefono, $id);
        return $stmt->execute();
    }

    // Eliminar un rector
    public function deleteRector($id) {
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