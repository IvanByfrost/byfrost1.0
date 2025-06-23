<?php
require_once 'mainModel.php';
class UserModel extends mainModel
{
    // Constructor de la clase 
    public function __construct()
    {
        parent::__construct();
    }

    // Función para consultar a todos los usuarios
    public function getUsers()
    {
        $query = "SELECT * FROM mainUser";
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Función para crear un usuario
    public function createUser($data)
    {
        // Implementar la lógica para crear un usuario
        // 1. Verificar si el documento ya existe
        $queryCheck = "SELECT COUNT(*) FROM mainUser WHERE userDocument = :userDocument";
        $stmtCheck = $this->dbConn->prepare($queryCheck);
        $stmtCheck->execute([':userDocument' => $data['userDocument']]);
        if ($stmtCheck->fetchColumn() > 0) {
            throw new Exception("Ya existe un usuario con ese documento.");
        }
        $query = "INSERT INTO mainUser (credType, userDocument, userEmail, userPassword) VALUES (:credType, :userDocument, :userEmail, :userPassword)";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([
            'credType' => $data['credType'],
            'userDocument' => $data['userDocument'],
            'userEmail' => $data['userEmail'],
            'userPassword' => password_hash($data['userPassword'], PASSWORD_DEFAULT)
        ]);
        return $stmt->rowCount() > 0; // Retorna true si se creó correctamente
    }
    // Función para consultar un usuario
    public function getUser()
    {
        $query = "SELECT * FROM mainUser WHERE userId = :userId ";
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Función para actualizar un usuario
    public function updateUser()
    {
        //Aquí va la lógica de actualizar el usuario.
        $query = "SELECT * FROM mainUser WHERE userId = :userId ";
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function completeProfile($data)
    {
        $query = "UPDATE mainUser 
              SET userName = :userName,
               lastnameUser = :lastnameUser,
                  userPhone = :userPhone, 
                  dob = :dob,
                  addressUser = :addressUser
              WHERE userDocument = :userDocument";

        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([
            ':userName'      => $data['userName'],
            ':lastnameUser'  => $data['lastnameUser'],
            ':userPhone'     => $data['userPhone'],
            ':dob'           => $data['dob'],
            ':addressUser'   => $data['addressUser'],
            ':userDocument'  => $data['userDocument']
        ]);

        return $stmt->rowCount() > 0;
    }

    // Función para eliminar un usuario
    public function deleteUser()
    {
        //Aquí va la lógica de eliminar un usuario.
    }
    // Función para validar un usuario
    public function validateUser()
    {
        //Aquí va la lógica de validar un usuario. 
    }

    //Función para asignar un rol a un usuario
    public function assignRole($userId, $roleId)
    {
        $query = "UPDATE mainUser SET roleId = :roleId WHERE mainUser = :userId";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([
            ':roleId' => $roleId,
            ':userId' => $userId
        ]);
        return $stmt->rowCount() > 0;
    }
}
