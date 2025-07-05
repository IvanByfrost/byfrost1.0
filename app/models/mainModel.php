<?php
require_once __DIR__ . '/../scripts/connection.php';

// Modelo principal para manejar la conexión a la base de datos
class MainModel {
    protected $dbConn;
    
    public function __construct()
    {
        $this->dbConn = DatabaseConnection::getInstance()->getConnection();
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

    // Función para consultar un registro por cualquier campo
    public function getByField($table, $field, $value)
    {
        $query = "SELECT * FROM $table WHERE $field = :value LIMIT 1";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute(['value' => $value]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}