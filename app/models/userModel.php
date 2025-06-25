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
        $query = "SELECT u.*, r.role_type FROM users u 
                  LEFT JOIN user_roles r ON u.user_id = r.user_id 
                  WHERE r.is_active = 1 OR r.is_active IS NULL";
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para crear un usuario
    public function createUser($data)
    {
        // 1. Verificar si el documento ya existe
        $queryCheck = "SELECT COUNT(*) FROM users WHERE credential_number = :credential_number";
        $stmtCheck = $this->dbConn->prepare($queryCheck);
        $stmtCheck->execute([':credential_number' => $data['credential_number']]);
        if ($stmtCheck->fetchColumn() > 0) {
            throw new Exception("Ya existe un usuario con ese documento.");
        }

        // 2. Verificar si el email ya existe
        $queryCheckEmail = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmtCheckEmail = $this->dbConn->prepare($queryCheckEmail);
        $stmtCheckEmail->execute([':email' => $data['email']]);
        if ($stmtCheckEmail->fetchColumn() > 0) {
            throw new Exception("Ya existe un usuario con ese email.");
        }

        // 3. Generar hash de contraseña usando password_hash
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

        // 4. Insertar usuario
        $query = "INSERT INTO users (credential_type, credential_number, first_name, last_name, 
                                    date_of_birth, email, phone, address, password_hash, salt_password) 
                  VALUES (:credential_type, :credential_number, :first_name, :last_name, 
                          :date_of_birth, :email, :phone, :address, :password_hash, :salt_password)";
        
        $stmt = $this->dbConn->prepare($query);
        $result = $stmt->execute([
            'credential_type' => $data['credential_type'],
            'credential_number' => $data['credential_number'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'date_of_birth' => $data['date_of_birth'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'password_hash' => $passwordHash,
            'salt_password' => '' // Ya no necesitamos salt con password_hash
        ]);

        if ($result) {
            $userId = $this->dbConn->lastInsertId();
            // Por defecto, asignar rol de estudiante (puedes cambiar esto)
            $this->assignDefaultRole($userId, 'student');
            return true;
        }
        
        return false;
    }

    // Función para consultar un usuario
    public function getUser($userId)
    {
        $query = "SELECT u.*, r.role_type FROM users u 
                  LEFT JOIN user_roles r ON u.user_id = r.user_id 
                  WHERE u.user_id = :userId AND (r.is_active = 1 OR r.is_active IS NULL)";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Función para actualizar un usuario
    public function updateUser($userId, $data)
    {
        $query = "UPDATE users SET 
                  first_name = :first_name,
                  last_name = :last_name,
                  phone = :phone,
                  date_of_birth = :date_of_birth,
                  address = :address
                  WHERE user_id = :user_id";

        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':phone' => $data['phone'],
            ':date_of_birth' => $data['date_of_birth'],
            ':address' => $data['address'],
            ':user_id' => $userId
        ]);
    }

    public function completeProfile($data)
    {
        $query = "UPDATE users 
                  SET first_name = :first_name,
                      last_name = :last_name,
                      phone = :phone, 
                      date_of_birth = :date_of_birth,
                      address = :address
                  WHERE credential_number = :credential_number";

        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':phone' => $data['phone'],
            ':date_of_birth' => $data['date_of_birth'],
            ':address' => $data['address'],
            ':credential_number' => $data['credential_number']
        ]);

        return $stmt->rowCount() > 0;
    }

    // Función para eliminar un usuario
    public function deleteUser($userId)
    {
        $query = "UPDATE users SET is_active = 0 WHERE user_id = :user_id";
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([':user_id' => $userId]);
    }

    // Función para validar un usuario
    public function validateUser($credentialNumber, $password)
    {
        $query = "SELECT * FROM users WHERE credential_number = :credential_number AND is_active = 1";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':credential_number' => $credentialNumber]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return password_verify($password, $user['password_hash']) ? $user : false;
        }
        
        return false;
    }

    // Función para asignar un rol a un usuario
    public function assignRole($userId, $roleType)
    {
        // Primero desactivar roles existentes
        $queryDeactivate = "UPDATE user_roles SET is_active = 0 WHERE user_id = :user_id";
        $stmtDeactivate = $this->dbConn->prepare($queryDeactivate);
        $stmtDeactivate->execute([':user_id' => $userId]);

        // Luego insertar el nuevo rol
        $query = "INSERT INTO user_roles (user_id, role_type, is_active) VALUES (:user_id, :role_type, 1)";
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([
            ':user_id' => $userId,
            ':role_type' => $roleType
        ]);
    }

    // Función para asignar rol por defecto
    private function assignDefaultRole($userId, $roleType)
    {
        return $this->assignRole($userId, $roleType);
    }
}
