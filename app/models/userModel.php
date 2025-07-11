<?php
require_once ROOT . '/app/models/MainModel.php';

class UserModel extends MainModel
{
    public function __construct($dbConn = null)
    {
        if ($dbConn) {
            $this->dbConn = $dbConn;
        } else {
            parent::__construct();
        }
    }

    public function getUsers()
    {
        $query = "
            SELECT u.*, ur.role_type
            FROM users u
            LEFT JOIN user_roles ur ON u.user_id = ur.user_id
            WHERE u.is_active = 1
        ";
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createUser($data)
    {
        $this->dbConn->beginTransaction();

        try {
            // Verificar documento
            $queryCheckDoc = "SELECT COUNT(*) FROM users WHERE credential_number = :credential_number";
            $stmtDoc = $this->dbConn->prepare($queryCheckDoc);
            $stmtDoc->execute([':credential_number' => $data['credential_number']]);
            if ($stmtDoc->fetchColumn() > 0) {
                throw new Exception("Ya existe un usuario con ese documento.");
            }

            // Verificar email
            $queryCheckEmail = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmtEmail = $this->dbConn->prepare($queryCheckEmail);
            $stmtEmail->execute([':email' => $data['email']]);
            if ($stmtEmail->fetchColumn() > 0) {
                throw new Exception("Ya existe un usuario con ese correo electrónico.");
            }

            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

            $query = "
                INSERT INTO users (
                    credential_type, credential_number, first_name, last_name,
                    date_of_birth, email, phone, address, password_hash, salt_password
                )
                VALUES (
                    :credential_type, :credential_number, :first_name, :last_name,
                    :date_of_birth, :email, :phone, :address, :password_hash, ''
                )
            ";

            $stmt = $this->dbConn->prepare($query);
            $result = $stmt->execute([
                ':credential_type' => $data['credential_type'],
                ':credential_number' => $data['credential_number'],
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':date_of_birth' => $data['date_of_birth'],
                ':email' => $data['email'],
                ':phone' => $data['phone'] ?? null,
                ':address' => $data['address'] ?? null,
                ':password_hash' => $passwordHash
            ]);

            if (!$result) {
                throw new Exception("Error al insertar el usuario.");
            }

            $userId = $this->dbConn->lastInsertId();

            $this->dbConn->commit();
            return $userId;

        } catch (Exception $e) {
            $this->dbConn->rollBack();
            error_log("Error en UserModel::createUser → " . $e->getMessage());
            throw $e;
        }
    }

    public function getUser($userId)
    {
        $query = "
            SELECT u.*, ur.role_type
            FROM users u
            LEFT JOIN user_roles ur ON u.user_id = ur.user_id
            WHERE u.user_id = :user_id AND u.is_active = 1
        ";

        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($userId, $data)
    {
        $query = "
            UPDATE users SET
                first_name = :first_name,
                last_name = :last_name,
                email = :email,
                phone = :phone,
                date_of_birth = :date_of_birth,
                address = :address
            WHERE user_id = :user_id
        ";

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

    public function updateUserWithDocument($userId, $data)
    {
        $query = "
            UPDATE users SET
                first_name = :first_name,
                last_name = :last_name,
                email = :email,
                phone = :phone,
                date_of_birth = :date_of_birth,
                address = :address,
                credential_type = :credential_type,
                credential_number = :credential_number
        ";

        $params = [
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':date_of_birth' => $data['date_of_birth'],
            ':address' => $data['address'],
            ':credential_type' => $data['credential_type'],
            ':credential_number' => $data['credential_number'],
            ':user_id' => $userId
        ];

        if (!empty($data['profile_photo'])) {
            $query .= ", profile_photo = :profile_photo";
            $params[':profile_photo'] = $data['profile_photo'];
        }

        $query .= " WHERE user_id = :user_id";

        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute($params);
    }

    public function completeProfile($data)
    {
        $query = "
            UPDATE users SET
                first_name = :first_name,
                last_name = :last_name,
                phone = :phone,
                date_of_birth = :date_of_birth,
                address = :address
            WHERE credential_number = :credential_number
        ";

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

    public function deleteUser($userId)
    {
        $query = "UPDATE users SET is_active = 0 WHERE user_id = :user_id";
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([':user_id' => $userId]);
    }

    public function deleteUserPermanently($userId)
    {
        try {
            $this->dbConn->beginTransaction();

            $check = "SELECT user_id FROM users WHERE user_id = :user_id";
            $stmtCheck = $this->dbConn->prepare($check);
            $stmtCheck->execute([':user_id' => $userId]);

            if (!$stmtCheck->fetch()) {
                throw new Exception("El usuario no existe.");
            }

            $this->dbConn->prepare("DELETE FROM user_roles WHERE user_id = :user_id")
                ->execute([':user_id' => $userId]);

            $this->dbConn->prepare("DELETE FROM users WHERE user_id = :user_id")
                ->execute([':user_id' => $userId]);

            $this->dbConn->commit();
            return true;

        } catch (Exception $e) {
            $this->dbConn->rollBack();
            error_log("Error en UserModel::deleteUserPermanently → " . $e->getMessage());
            throw $e;
        }
    }

    public function canDeleteUserPermanently($userId)
    {
        try {
            $rootQuery = "
                SELECT COUNT(*) AS root_count
                FROM users u
                INNER JOIN user_roles ur ON u.user_id = ur.user_id
                WHERE ur.role_type = 'root' AND ur.is_active = 1
            ";
            $stmtRoot = $this->dbConn->query($rootQuery);
            $rootCount = $stmtRoot->fetch(PDO::FETCH_ASSOC)['root_count'] ?? 0;

            $isRootQuery = "
                SELECT COUNT(*) AS is_root
                FROM users u
                INNER JOIN user_roles ur ON u.user_id = ur.user_id
                WHERE u.user_id = :user_id AND ur.role_type = 'root' AND ur.is_active = 1
            ";
            $stmtIsRoot = $this->dbConn->prepare($isRootQuery);
            $stmtIsRoot->execute([':user_id' => $userId]);
            $isRoot = $stmtIsRoot->fetch(PDO::FETCH_ASSOC)['is_root'] ?? 0;

            if ($isRoot && $rootCount <= 1) {
                return ['can_delete' => false, 'reason' => 'No se puede eliminar el último usuario root.'];
            }

            return ['can_delete' => true, 'reason' => 'Usuario puede ser eliminado.'];

        } catch (Exception $e) {
            error_log("Error en UserModel::canDeleteUserPermanently → " . $e->getMessage());
            return ['can_delete' => false, 'reason' => 'Error al verificar: ' . $e->getMessage()];
        }
    }

    public function validateUser($credentialNumber, $password)
    {
        $query = "SELECT * FROM users WHERE credential_number = :credential_number AND is_active = 1";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':credential_number' => $credentialNumber]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public function assignRole($userId, $roleType)
    {
        try {
            $this->dbConn->beginTransaction();

            $validRoles = ['student', 'parent', 'professor', 'coordinator', 'director', 'treasurer', 'root'];
            if (!in_array($roleType, $validRoles)) {
                throw new Exception("Rol inválido: " . $roleType);
            }

            $deactivateQuery = "UPDATE user_roles SET is_active = 0 WHERE user_id = :user_id";
            $this->dbConn->prepare($deactivateQuery)->execute([':user_id' => $userId]);

            $checkQuery = "SELECT * FROM user_roles WHERE user_id = :user_id AND role_type = :role_type";
            $stmtCheck = $this->dbConn->prepare($checkQuery);
            $stmtCheck->execute([
                ':user_id' => $userId,
                ':role_type' => $roleType
            ]);
            $existing = $stmtCheck->fetch();

            if ($existing) {
                $reactivateQuery = "
                    UPDATE user_roles
                    SET is_active = 1
                    WHERE user_id = :user_id AND role_type = :role_type
                ";
                $this->dbConn->prepare($reactivateQuery)
                    ->execute([':user_id' => $userId, ':role_type' => $roleType]);
            } else {
                $insertQuery = "
                    INSERT INTO user_roles (user_id, role_type, is_active)
                    VALUES (:user_id, :role_type, 1)
                ";
                $this->dbConn->prepare($insertQuery)
                    ->execute([':user_id' => $userId, ':role_type' => $roleType]);
            }

            $this->dbConn->commit();
            return true;

        } catch (Exception $e) {
            $this->dbConn->rollBack();
            error_log("Error en UserModel::assignRole → " . $e->getMessage());
            throw $e;
        }
    }

    public function searchUsersByDocument($credentialType, $credentialNumber)
    {
        $query = "
            SELECT
                u.user_id, u.credential_type, u.credential_number,
                u.first_name, u.last_name, u.email, u.phone, u.address, u.is_active,
                ur.role_type AS user_role
            FROM users u
            LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1
            WHERE u.credential_type = :credential_type
              AND u.credential_number = :credential_number
              AND u.is_active = 1
            ORDER BY u.first_name, u.last_name
        ";

        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([
            ':credential_type' => $credentialType,
            ':credential_number' => $credentialNumber
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsersWithoutRole()
    {
        $query = "
            SELECT u.*
            FROM users u
            LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1
            WHERE ur.user_id IS NULL
              AND u.is_active = 1
            ORDER BY u.first_name, u.last_name
            LIMIT 50
        ";

        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsersByRole($roleType, $currentUserRole = null)
    {
        if ($currentUserRole === 'director' && in_array($roleType, ['root', 'director'])) {
            throw new Exception('No tienes permisos para buscar usuarios con ese rol.');
        }

        $query = "
            SELECT u.*, ur.role_type
            FROM users u
            INNER JOIN user_roles ur ON u.user_id = ur.user_id
            WHERE ur.role_type = :role_type
              AND u.is_active = 1
              AND ur.is_active = 1
            ORDER BY u.first_name, u.last_name
        ";

        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':role_type' => $roleType]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsersByRole($roleType, $query, $currentUserRole = null)
    {
        if ($currentUserRole === 'director' && in_array($roleType, ['root', 'director'])) {
            throw new Exception('No tienes permisos para buscar usuarios con ese rol.');
        }

        $sql = "
            SELECT u.*, ur.role_type
            FROM users u
            INNER JOIN user_roles ur ON u.user_id = ur.user_id
            WHERE ur.role_type = :role_type
              AND u.is_active = 1
              AND ur.is_active = 1
              AND (
                  u.first_name LIKE :search
                  OR u.last_name LIKE :search
                  OR u.credential_number LIKE :search
                  OR CONCAT(u.first_name, ' ', u.last_name) LIKE :search
              )
            ORDER BY u.first_name, u.last_name
            LIMIT 10
        ";

        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute([
            ':role_type' => $roleType,
            ':search' => '%' . $query . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsersByRoleAndDocument($roleType, $credentialNumber, $currentUserRole = null)
    {
        if ($currentUserRole === 'director' && in_array($roleType, ['root', 'director'])) {
            throw new Exception('No tienes permisos para buscar usuarios con ese rol.');
        }

        $query = "
            SELECT u.*, ur.role_type
            FROM users u
            INNER JOIN user_roles ur ON u.user_id = ur.user_id
            WHERE ur.role_type = :role_type
              AND u.credential_number = :credential_number
              AND u.is_active = 1
              AND ur.is_active = 1
            ORDER BY u.first_name, u.last_name
            LIMIT 10
        ";

        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([
            ':role_type' => $roleType,
            ':credential_number' => $credentialNumber
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changePassword($userId, $currentPassword, $newPassword)
    {
        $query = "SELECT password_hash FROM users WHERE user_id = :user_id AND is_active = 1";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return 'Usuario no encontrado o inactivo.';
        }

        if (!password_verify($currentPassword, $user['password_hash'])) {
            return 'La contraseña actual es incorrecta.';
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $update = "UPDATE users SET password_hash = :new_hash WHERE user_id = :user_id";
        $stmtUpdate = $this->dbConn->prepare($update);
        $result = $stmtUpdate->execute([':new_hash' => $newHash, ':user_id' => $userId]);

        return $result ? true : 'Error al actualizar la contraseña.';
    }

    public function searchUsersByName($nameSearch)
    {
        $query = "
            SELECT u.*, ur.role_type AS user_role
            FROM users u
            LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1
            WHERE u.is_active = 1
              AND (
                  u.first_name LIKE :search
                  OR u.last_name LIKE :search
                  OR CONCAT(u.first_name, ' ', u.last_name) LIKE :search
              )
            ORDER BY u.first_name, u.last_name
            LIMIT 50
        ";

        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':search' => '%' . $nameSearch . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsersGeneral($search)
    {
        $query = "
            SELECT u.*, ur.role_type AS user_role
            FROM users u
            LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1
            WHERE u.is_active = 1
              AND (
                  u.first_name LIKE :search
                  OR u.last_name LIKE :search
                  OR u.credential_number LIKE :search
                  OR u.email LIKE :search
                  OR ur.role_type LIKE :search
                  OR CONCAT(u.first_name, ' ', u.last_name) LIKE :search
              )
            ORDER BY u.first_name, u.last_name
            LIMIT 50
        ";

        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':search' => '%' . $search . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function assignDefaultRole($userId, $roleType)
    {
        return $this->assignRole($userId, $roleType);
    }

    public function updateUser($userId, $data)
{
    $sql = "
        UPDATE user_main
        SET 
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            phone = :phone,
            address = :address,
            date_of_birth = :date_of_birth,
            credential_type = :credential_type,
            credential_number = :credential_number
        WHERE user_main_id = :user_id
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':first_name', $data['first_name']);
    $stmt->bindValue(':last_name', $data['last_name']);
    $stmt->bindValue(':email', $data['email']);
    $stmt->bindValue(':phone', $data['phone']);
    $stmt->bindValue(':address', $data['address']);
    $stmt->bindValue(':date_of_birth', $data['date_of_birth']);
    $stmt->bindValue(':credential_type', $data['credential_type']);
    $stmt->bindValue(':credential_number', $data['credential_number']);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

    return $stmt->execute();
}

}
