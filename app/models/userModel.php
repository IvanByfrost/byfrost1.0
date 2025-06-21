<?php
require_once 'mainModel.php';
class UserModel extends mainModel
{
    // Constructor de la clase 
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
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

    public function completeProfile($data)
    {
        $query = "UPDATE mainUser 
              SET userName = :userName, 
                  userPhone = :userPhone, 
                  addressUser = :addressUser
              WHERE userEmail = :userEmails";

        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([
            ':userName' => $data['userName'],
            ':userPhone' => $data['userPhone'],
            ':addressUser' => $data['addressUser'],
            ':userEmail' => $data['userEmail']
        ]);

        return $stmt->rowCount() > 0;
    }
}
