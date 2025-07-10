<?php
require_once ROOT . '/app/models/MainModel.php';

class CoordinatorModel extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //Función para obtener los datos de un coordinador específico por ID
    public function getCoordinator($coordinatorId)
    {
        $query = "SELECT u.*, ur.role_type, ur.user_role_id
                  FROM users u 
                  INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                  WHERE ur.role_type = 'coordinator' 
                  AND u.user_id = :coordinator_id 
                  AND ur.is_active = 1";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':coordinator_id', $coordinatorId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Función para obtener todos los coordinadores
    public function getAllCoordinators()
    {
        $query = "SELECT u.*, ur.role_type, ur.user_role_id
                  FROM users u 
                  INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                  WHERE ur.role_type = 'coordinator' 
                  AND ur.is_active = 1
                  ORDER BY u.first_name, u.last_name";
        
        $result = $this->dbConn->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    //Función para asignar rol de coordinador a un usuario existente
    public function createCoordinator($userId)
    {
        try {
            $this->dbConn->beginTransaction();
            
            // Verificar que el usuario existe
            $userQuery = "SELECT user_id FROM users WHERE user_id = :user_id AND is_active = 1";
            $userStmt = $this->dbConn->prepare($userQuery);
            $userStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $userStmt->execute();
            
            if (!$userStmt->fetch()) {
                throw new Exception("El usuario no existe o no está activo");
            }
            
            // Verificar que no tenga ya el rol de coordinador
            $existingRoleQuery = "SELECT user_role_id FROM user_roles 
                                 WHERE user_id = :user_id AND role_type = 'coordinator' AND is_active = 1";
            $existingRoleStmt = $this->dbConn->prepare($existingRoleQuery);
            $existingRoleStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $existingRoleStmt->execute();
            
            if ($existingRoleStmt->fetch()) {
                throw new Exception("El usuario ya tiene el rol de coordinador");
            }
            
            // Asignar rol de coordinador
            $roleQuery = "INSERT INTO user_roles (user_id, role_type) VALUES (:user_id, 'coordinator')";
            $roleStmt = $this->dbConn->prepare($roleQuery);
            $roleStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $roleStmt->execute();
            
            $this->dbConn->commit();
            return $userId;
            
        } catch (Exception $e) {
            $this->dbConn->rollBack();
            throw $e;
        }
    }

    //Función para actualizar un coordinador
    public function updateCoordinator($coordinatorId, $userData)
    {
        try {
            $this->dbConn->beginTransaction();
            
            // Actualizar datos del usuario
            $userQuery = "UPDATE users SET 
                         credential_number = :credential_number,
                         first_name = :first_name,
                         last_name = :last_name,
                         credential_type = :credential_type,
                         date_of_birth = :date_of_birth,
                         address = :address,
                         phone = :phone,
                         email = :email
                         WHERE user_id = :user_id";
            
            $userStmt = $this->dbConn->prepare($userQuery);
            $userStmt->bindParam(':credential_number', $userData['credential_number']);
            $userStmt->bindParam(':first_name', $userData['first_name']);
            $userStmt->bindParam(':last_name', $userData['last_name']);
            $userStmt->bindParam(':credential_type', $userData['credential_type']);
            $userStmt->bindParam(':date_of_birth', $userData['date_of_birth']);
            $userStmt->bindParam(':address', $userData['address']);
            $userStmt->bindParam(':phone', $userData['phone']);
            $userStmt->bindParam(':email', $userData['email']);
            $userStmt->bindParam(':user_id', $coordinatorId, PDO::PARAM_INT);
            
            $userStmt->execute();
            
            $this->dbConn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->dbConn->rollBack();
            throw $e;
        }
    }

    //Función para cambiar contraseña del coordinador
    public function updateCoordinatorPassword($coordinatorId, $newPassword)
    {
        try {
            // Generar nuevo salt y hash
            $salt = bin2hex(random_bytes(32));
            $passwordHash = hash('sha256', $newPassword . $salt);
            
            $query = "UPDATE users SET password_hash = :password_hash, salt_password = :salt_password 
                     WHERE user_id = :user_id";
            
            $stmt = $this->dbConn->prepare($query);
            $stmt->bindParam(':password_hash', $passwordHash, PDO::PARAM_STR);
            $stmt->bindParam(':salt_password', $salt, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $coordinatorId, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    //Función para desactivar solo el rol de coordinador (Director y Root)
    public function deactivateCoordinatorRole($coordinatorId)
    {
        try {
            // Solo desactivar el rol de coordinador, mantener el usuario activo
            $roleQuery = "UPDATE user_roles SET is_active = 0 
                         WHERE user_id = :user_id AND role_type = 'coordinator'";
            $roleStmt = $this->dbConn->prepare($roleQuery);
            $roleStmt->bindParam(':user_id', $coordinatorId, PDO::PARAM_INT);
            $roleStmt->execute();
            
            return true;
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    //Función para desactivar un coordinador completamente (soft delete)
    public function deactivateCoordinator($coordinatorId)
    {
        try {
            $this->dbConn->beginTransaction();
            
            // Desactivar usuario
            $userQuery = "UPDATE users SET is_active = 0 WHERE user_id = :user_id";
            $userStmt = $this->dbConn->prepare($userQuery);
            $userStmt->bindParam(':user_id', $coordinatorId, PDO::PARAM_INT);
            $userStmt->execute();
            
            // Desactivar rol
            $roleQuery = "UPDATE user_roles SET is_active = 0 
                         WHERE user_id = :user_id AND role_type = 'coordinator'";
            $roleStmt = $this->dbConn->prepare($roleQuery);
            $roleStmt->bindParam(':user_id', $coordinatorId, PDO::PARAM_INT);
            $roleStmt->execute();
            
            $this->dbConn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->dbConn->rollBack();
            throw $e;
        }
    }

    //Función para eliminar un coordinador (hard delete - solo para administradores)
    public function deleteCoordinator($coordinatorId)
    {
        try {
            $this->dbConn->beginTransaction();
            
            // Eliminar rol (se elimina automáticamente por CASCADE)
            $roleQuery = "DELETE FROM user_roles 
                         WHERE user_id = :user_id AND role_type = 'coordinator'";
            $roleStmt = $this->dbConn->prepare($roleQuery);
            $roleStmt->bindParam(':user_id', $coordinatorId, PDO::PARAM_INT);
            $roleStmt->execute();
            
            // Eliminar usuario
            $userQuery = "DELETE FROM users WHERE user_id = :user_id";
            $userStmt = $this->dbConn->prepare($userQuery);
            $userStmt->bindParam(':user_id', $coordinatorId, PDO::PARAM_INT);
            $userStmt->execute();
            
            $this->dbConn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->dbConn->rollBack();
            throw $e;
        }
    }

    //Función para verificar si un coordinador existe
    public function coordinatorExists($coordinatorId)
    {
        $query = "SELECT COUNT(*) as count 
                  FROM users u 
                  INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                  WHERE ur.role_type = 'coordinator' 
                  AND u.user_id = :coordinator_id 
                  AND ur.is_active = 1";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':coordinator_id', $coordinatorId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    //Función para buscar coordinadores por nombre o email
    public function searchCoordinators($searchTerm)
    {
        $searchTerm = '%' . $searchTerm . '%';
        
        $query = "SELECT u.*, ur.role_type, ur.user_role_id
                  FROM users u 
                  INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                  WHERE ur.role_type = 'coordinator' 
                  AND ur.is_active = 1
                  AND (u.first_name LIKE :search_term 
                       OR u.last_name LIKE :search_term 
                       OR u.email LIKE :search_term 
                       OR u.credential_number LIKE :search_term)
                  ORDER BY u.first_name, u.last_name";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':search_term', $searchTerm);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getData()
    {
        //Consulta a la base de datos para obtener los datos del coordinador
        $query = "SELECT u.*, ur.role_type 
                  FROM users u 
                  INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                  WHERE ur.role_type = 'coordinator' AND ur.is_active = 1";
        $result = $this->dbConn->query($query);
        $coordinator = $result->fetch(PDO::FETCH_ASSOC);
        return $coordinator;
    }
}
