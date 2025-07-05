<?php
require_once 'mainModel.php';
class UserModel extends MainModel
{
    // Constructor de la clase 
    public function __construct($dbConn = null)
    {
        if ($dbConn) {
            // Si se pasa una conexión, usarla
            $this->dbConn = $dbConn;
        } else {
            // Si no, usar la conexión del MainModel
            parent::__construct();
        }
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
        // Iniciar transacción
        $this->dbConn->beginTransaction();
        
        try {
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

            if (!$result) {
                throw new Exception("Error al insertar el usuario en la base de datos.");
            }

            $userId = $this->dbConn->lastInsertId();
            
            // 5. Asignar rol por defecto
            $roleResult = $this->assignDefaultRole($userId, 'student');
            if (!$roleResult) {
                throw new Exception("Error al asignar el rol por defecto al usuario.");
            }

            // Si todo salió bien, confirmar la transacción
            $this->dbConn->commit();
            return true;
            
        } catch (Exception $e) {
            // Si algo salió mal, revertir la transacción
            $this->dbConn->rollBack();
            error_log("Error en UserModel::createUser: " . $e->getMessage());
            throw $e; // Re-lanzar la excepción para que el controlador la maneje
        }
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
                  email = :email,
                  phone = :phone,
                  date_of_birth = :date_of_birth,
                  address = :address
                  WHERE user_id = :user_id";

        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':date_of_birth' => $data['date_of_birth'],
            ':address' => $data['address'],
            ':user_id' => $userId
        ]);
    }

    // Función para actualizar un usuario incluyendo campos de documento
    public function updateUserWithDocument($userId, $data)
    {
        $query = "UPDATE users SET 
                  first_name = :first_name,
                  last_name = :last_name,
                  email = :email,
                  phone = :phone,
                  date_of_birth = :date_of_birth,
                  address = :address,
                  credential_type = :credential_type,
                  credential_number = :credential_number
                  WHERE user_id = :user_id";

        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':date_of_birth' => $data['date_of_birth'],
            ':address' => $data['address'],
            ':credential_type' => $data['credential_type'],
            ':credential_number' => $data['credential_number'],
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

    // Función para desactivar un usuario (soft delete)
    public function deleteUser($userId)
    {
        $query = "UPDATE users SET is_active = 0 WHERE user_id = :user_id";
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([':user_id' => $userId]);
    }

    // Función para eliminar un usuario permanentemente (hard delete)
    public function deleteUserPermanently($userId)
    {
        try {
            $this->dbConn->beginTransaction();
            
            // Verificar que el usuario existe
            $checkQuery = "SELECT user_id FROM users WHERE user_id = :user_id";
            $checkStmt = $this->dbConn->prepare($checkQuery);
            $checkStmt->execute([':user_id' => $userId]);
            
            if (!$checkStmt->fetch()) {
                throw new Exception("El usuario no existe");
            }
            
            // Eliminar roles del usuario (se eliminan automáticamente por CASCADE)
            $roleQuery = "DELETE FROM user_roles WHERE user_id = :user_id";
            $roleStmt = $this->dbConn->prepare($roleQuery);
            $roleStmt->execute([':user_id' => $userId]);
            
            // Eliminar usuario permanentemente
            $userQuery = "DELETE FROM users WHERE user_id = :user_id";
            $userStmt = $this->dbConn->prepare($userQuery);
            $userStmt->execute([':user_id' => $userId]);
            
            $this->dbConn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->dbConn->rollBack();
            throw $e;
        }
    }

    // Función para verificar si un usuario puede ser eliminado permanentemente
    public function canDeleteUserPermanently($userId)
    {
        try {
            // Verificar si es el último usuario root
            $rootQuery = "SELECT COUNT(*) as root_count FROM users u 
                         INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                         WHERE ur.role_type = 'root' AND ur.is_active = 1";
            $rootStmt = $this->dbConn->prepare($rootQuery);
            $rootStmt->execute();
            $rootCount = $rootStmt->fetch(PDO::FETCH_ASSOC)['root_count'];
            
            // Verificar si el usuario a eliminar es root
            $isRootQuery = "SELECT COUNT(*) as is_root FROM users u 
                           INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                           WHERE u.user_id = :user_id AND ur.role_type = 'root' AND ur.is_active = 1";
            $isRootStmt = $this->dbConn->prepare($isRootQuery);
            $isRootStmt->execute([':user_id' => $userId]);
            $isRoot = $isRootStmt->fetch(PDO::FETCH_ASSOC)['is_root'] > 0;
            
            // Si es el último root, no permitir eliminación
            if ($isRoot && $rootCount <= 1) {
                return ['can_delete' => false, 'reason' => 'No se puede eliminar el último usuario root del sistema'];
            }
            
            // Verificar dependencias en otras tablas (opcional)
            // Aquí puedes agregar verificaciones adicionales según tu esquema
            
            return ['can_delete' => true, 'reason' => 'Usuario puede ser eliminado'];
            
        } catch (Exception $e) {
            return ['can_delete' => false, 'reason' => 'Error al verificar: ' . $e->getMessage()];
        }
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
        try {
            // Verificar que el usuario existe
            $checkUser = "SELECT user_id FROM users WHERE user_id = :user_id AND is_active = 1";
            $stmtCheck = $this->dbConn->prepare($checkUser);
            $stmtCheck->execute([':user_id' => $userId]);
            if (!$stmtCheck->fetch()) {
                throw new Exception("El usuario especificado no existe o no está activo.");
            }

            // Verificar que el tipo de rol es válido
            $validRoles = ['student', 'parent', 'professor', 'coordinator', 'director', 'treasurer', 'root'];
            if (!in_array($roleType, $validRoles)) {
                throw new Exception("Tipo de rol inválido: " . $roleType);
            }

            // Primero desactivar roles existentes
            $queryDeactivate = "UPDATE user_roles SET is_active = 0 WHERE user_id = :user_id";
            $stmtDeactivate = $this->dbConn->prepare($queryDeactivate);
            $deactivateResult = $stmtDeactivate->execute([':user_id' => $userId]);
            
            if (!$deactivateResult) {
                throw new Exception("Error al desactivar roles existentes.");
            }

            // Luego insertar el nuevo rol
            $query = "INSERT INTO user_roles (user_id, role_type, is_active) VALUES (:user_id, :role_type, 1)";
            $stmt = $this->dbConn->prepare($query);
            $insertResult = $stmt->execute([
                ':user_id' => $userId,
                ':role_type' => $roleType
            ]);

            if (!$insertResult) {
                throw new Exception("Error al insertar el nuevo rol.");
            }

            return true;
            
        } catch (Exception $e) {
            error_log("Error en UserModel::assignRole: " . $e->getMessage());
            throw $e;
        }
    }

    // Función para asignar rol por defecto
    private function assignDefaultRole($userId, $roleType)
    {
        return $this->assignRole($userId, $roleType);
    }

    
    /**
     * Busca usuarios por tipo y número de documento
     * 
     * @param string $credentialType Tipo de documento (CC, TI, etc.)
     * @param string $credentialNumber Número de documento
     * @return array Array de usuarios encontrados
     */
    public function searchUsersByDocument($credentialType, $credentialNumber)
    {
        try {
            // Verificar que tenemos conexión
            if (!$this->dbConn) {
                throw new Exception('No hay conexión a la base de datos');
            }
            
            // Verificar parámetros
            if (empty($credentialType) || empty($credentialNumber)) {
                throw new Exception('Tipo de documento y número son requeridos');
            }
            
            $query = "SELECT 
                        u.user_id,
                        u.credential_type,
                        u.credential_number,
                        u.first_name,
                        u.last_name,
                        u.email,
                        u.phone,
                        u.address,
                        ur.role_type as user_role
                      FROM users u
                      LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1
                      WHERE u.credential_type = :credential_type 
                      AND u.credential_number = :credential_number
                      AND u.is_active = 1
                      ORDER BY u.first_name, u.last_name";
            
            // Log de debug
            error_log("UserModel::searchUsersByDocument - Query: " . $query);
            error_log("UserModel::searchUsersByDocument - Params: " . $credentialType . ", " . $credentialNumber);
            
            $stmt = $this->dbConn->prepare($query);
            if (!$stmt) {
                throw new Exception('Error al preparar la consulta: ' . implode(', ', $this->dbConn->errorInfo()));
            }
            
            $result = $stmt->execute([
                ':credential_type' => $credentialType,
                ':credential_number' => $credentialNumber
            ]);
            
            if (!$result) {
                throw new Exception('Error al ejecutar la consulta: ' . implode(', ', $stmt->errorInfo()));
            }
            
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("UserModel::searchUsersByDocument - Resultados encontrados: " . count($users));
            
            return $users;
            
        } catch (PDOException $e) {
            error_log("UserModel::searchUsersByDocument PDO Error: " . $e->getMessage());
            throw new Exception('Error de base de datos: ' . $e->getMessage());
        } catch (Exception $e) {
            error_log("UserModel::searchUsersByDocument Error: " . $e->getMessage());
            throw new Exception('Error al buscar usuarios por documento: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene usuarios sin rol asignado
     * 
     * @return array Array de usuarios sin rol
     */
    public function getUsersWithoutRole()
    {
        try {
            $query = "SELECT 
                        u.user_id,
                        u.credential_type,
                        u.credential_number,
                        u.first_name,
                        u.last_name,
                        u.email,
                        u.phone,
                        u.address
                      FROM users u
                      LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1
                      WHERE ur.user_id IS NULL
                      AND u.is_active = 1
                      ORDER BY u.first_name, u.last_name
                      LIMIT 50";
            
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en UserModel::getUsersWithoutRole: " . $e->getMessage());
            throw new Exception('Error al obtener usuarios sin rol');
        }
    }

    /**
     * Obtiene usuarios con un rol específico
     * 
     * @param string $roleType Tipo de rol
     * @return array Array de usuarios con el rol especificado
     */
    public function getUsersByRole($roleType)
    {
        try {
            $query = "SELECT 
                        u.user_id,
                        u.credential_type,
                        u.credential_number,
                        u.first_name,
                        u.last_name,
                        u.email,
                        u.phone,
                        u.address,
                        ur.role_type
                      FROM users u
                      INNER JOIN user_roles ur ON u.user_id = ur.user_id
                      WHERE ur.role_type = :role_type
                      AND u.is_active = 1
                      AND ur.is_active = 1
                      ORDER BY u.first_name, u.last_name";
            
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute([':role_type' => $roleType]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en UserModel::getUsersByRole: " . $e->getMessage());
            throw new Exception('Error al obtener usuarios por rol');
        }
    }

    /**
     * Busca usuarios por rol y criterio de búsqueda
     * 
     * @param string $roleType Tipo de rol
     * @param string $query Criterio de búsqueda (nombre o documento)
     * @return array Array de usuarios encontrados
     */
    public function searchUsersByRole($roleType, $query)
    {
        try {
            $searchQuery = "SELECT 
                            u.user_id,
                            u.credential_type,
                            u.credential_number,
                            u.first_name,
                            u.last_name,
                            u.email,
                            u.phone,
                            u.address,
                            ur.role_type
                          FROM users u
                          INNER JOIN user_roles ur ON u.user_id = ur.user_id
                          WHERE LOWER(ur.role_type) = LOWER(:role_type)
                          AND u.is_active = 1
                          AND ur.is_active = 1
                          AND (u.first_name LIKE :search 
                               OR u.last_name LIKE :search 
                               OR u.credential_number LIKE :search
                               OR CONCAT(u.first_name, ' ', u.last_name) LIKE :search)
                          ORDER BY u.first_name, u.last_name
                          LIMIT 10";
            
            $stmt = $this->dbConn->prepare($searchQuery);
            $searchTerm = '%' . $query . '%';
            $stmt->execute([
                ':role_type' => $roleType,
                ':search' => $searchTerm
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en UserModel::searchUsersByRole: " . $e->getMessage());
            throw new Exception('Error al buscar usuarios por rol');
        }
    }

    /**
     * Obtiene el historial de roles de un usuario
     * @param int $userId
     * @return array
     */
    public function getRoleHistory($userId) {
        $query = "SELECT role_type, is_active, created_at FROM user_roles WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsersByRoleAndDocument($roleType, $credentialNumber)
    {
        try {
            $query = "SELECT 
                        u.user_id,
                        u.credential_type,
                        u.credential_number,
                        u.first_name,
                        u.last_name,
                        u.email,
                        u.phone,
                        u.address,
                        ur.role_type
                      FROM users u
                      INNER JOIN user_roles ur ON u.user_id = ur.user_id
                      WHERE LOWER(ur.role_type) = LOWER(:role_type)
                      AND u.is_active = 1
                      AND ur.is_active = 1
                      AND u.credential_number = :credential_number
                      ORDER BY u.first_name, u.last_name
                      LIMIT 10";
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute([
                ':role_type' => $roleType,
                ':credential_number' => $credentialNumber
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error al buscar usuarios por rol y documento');
        }
    }

    /**
     * Cambia la contraseña de un usuario autenticado
     * @param int $userId
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool|string true si éxito, mensaje de error si falla
     */
    public function changePassword($userId, $currentPassword, $newPassword)
    {
        // 1. Obtener el hash actual
        $query = "SELECT password_hash FROM users WHERE user_id = :user_id AND is_active = 1";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return 'Usuario no encontrado o inactivo.';
        }
        // 2. Verificar contraseña actual
        if (!password_verify($currentPassword, $user['password_hash'])) {
            return 'La contraseña actual es incorrecta.';
        }
        // 3. Actualizar con el nuevo hash
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $update = "UPDATE users SET password_hash = :new_hash WHERE user_id = :user_id";
        $stmtUpdate = $this->dbConn->prepare($update);
        $result = $stmtUpdate->execute([':new_hash' => $newHash, ':user_id' => $userId]);
        if ($result) {
            return true;
        } else {
            return 'Error al actualizar la contraseña.';
        }
    }
}
