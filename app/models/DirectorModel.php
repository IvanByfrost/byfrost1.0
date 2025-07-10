<?php
require_once ROOT . '/app/models/MainModel.php';

// software_academico/app/models/RectorModel.php

class DirectorModel extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    // Obtener todos los directores
    public function getAllDirector()
    {
        $sql = "SELECT u.*, ur.role_type 
                FROM users u 
                INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                WHERE ur.role_type = 'director' AND u.is_active = 1";
        $stmt = $this->dbConn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un director por ID
    public function getDirectorById($userId)
    {
        $sql = "SELECT u.*, ur.role_type 
                FROM users u 
                INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                WHERE u.user_id = :userId AND ur.role_type = 'director' AND u.is_active = 1";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insertar un nuevo director
    public function createDirector($userName, $userLastName, $userEmail, $password, $phoneUser = null)
    {
        try {
            $this->dbConn->beginTransaction();
            
            // Insertar en la tabla users
            $sql = "INSERT INTO users (first_name, last_name, email, phone, password_hash, salt_password, is_active) 
                    VALUES (:firstName, :lastName, :email, :phone, :passwordHash, :saltPassword, 1)";
            $stmt = $this->dbConn->prepare($sql);
            
            // Generar salt y hash de la contraseña
            $salt = bin2hex(random_bytes(32));
            $passwordHash = hash('sha256', $password . $salt);
            
            $stmt->execute([
                'firstName' => $userName,
                'lastName' => $userLastName,
                'email' => $userEmail,
                'phone' => $phoneUser,
                'passwordHash' => $passwordHash,
                'saltPassword' => $salt
            ]);
            
            $userId = $this->dbConn->lastInsertId();
            
            // Insertar el rol de director
            $sqlRole = "INSERT INTO user_roles (user_id, role_type, is_active) VALUES (:userId, 'director', 1)";
            $stmtRole = $this->dbConn->prepare($sqlRole);
            $stmtRole->execute(['userId' => $userId]);
            
            $this->dbConn->commit();
            return true;
        } catch (Exception $e) {
            $this->dbConn->rollBack();
            return false;
        }
    }

    // Actualizar un director
    public function updateDirector($id, $userName, $userLastName, $userEmail, $phoneUser = null)
    {
        $sql = "UPDATE users SET first_name = :firstName, last_name = :lastName, email = :email, phone = :phone 
                WHERE user_id = :userId AND EXISTS (
                    SELECT 1 FROM user_roles WHERE user_id = :userId AND role_type = 'director'
                )";
        $stmt = $this->dbConn->prepare($sql);
        return $stmt->execute([
            'firstName' => $userName,
            'lastName' => $userLastName,
            'email' => $userEmail,
            'phone' => $phoneUser,
            'userId' => $id
        ]);
    }

    // Eliminar un director (desactivar en lugar de eliminar)
    public function deleteDirector($id)
    {
        try {
            $this->dbConn->beginTransaction();
            
            // Desactivar el usuario
            $sql = "UPDATE users SET is_active = 0 WHERE user_id = :userId AND EXISTS (
                        SELECT 1 FROM user_roles WHERE user_id = :userId AND role_type = 'director'
                    )";
            $stmt = $this->dbConn->prepare($sql);
            $stmt->execute(['userId' => $id]);
            
            // Desactivar el rol
            $sqlRole = "UPDATE user_roles SET is_active = 0 WHERE user_id = :userId AND role_type = 'director'";
            $stmtRole = $this->dbConn->prepare($sqlRole);
            $stmtRole->execute(['userId' => $id]);
            
            $this->dbConn->commit();
            return true;
        } catch (Exception $e) {
            $this->dbConn->rollBack();
            return false;
        }
    }
}
