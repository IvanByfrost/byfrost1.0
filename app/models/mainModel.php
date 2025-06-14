<?php
// Modelo principal para manejar la conexión a la base de datos
class mainModel {
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    // Función para Consultar todos los registros
    public function getAll($table)
    {
        $query = "SELECT * FROM $table";
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para Consultar un registro por ID
    public function getById($table, $id)
    {
        $query = "SELECT * FROM $table WHERE id = :id";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Función para insertar un registro
    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute($data);
    }
} 