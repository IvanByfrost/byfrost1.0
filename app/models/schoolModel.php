<?php
require_once ROOT . '/app/models/MainModel.php';

class SchoolModel extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Crear una escuela
     */
    public function createSchool($data)
    {
        try {
            // Validar campos obligatorios
            if (empty($data['school_name']) || empty($data['school_dane']) || empty($data['school_document'])) {
                throw new Exception('Los campos Nombre, DANE y NIT son obligatorios.');
            }

            if (empty($data['director_user_id'])) {
                throw new Exception('Debe seleccionar un director.');
            }

            // Verificar duplicados por NIT o DANE
            if ($this->schoolExistsByDocumentOrDane($data['school_document'], $data['school_dane'])) {
                throw new Exception('Ya existe una escuela con el mismo NIT o código DANE.');
            }

            // Validar director
            if (!$this->userExistsWithRole($data['director_user_id'], 'director')) {
                throw new Exception('El director seleccionado no existe o no tiene el rol adecuado.');
            }

            // Validar coordinador si se proporciona
            if (!empty($data['coordinator_user_id'])) {
                if (!$this->userExistsWithRole($data['coordinator_user_id'], 'coordinator')) {
                    throw new Exception('El coordinador seleccionado no existe o no tiene el rol adecuado.');
                }
            }

            // Insertar escuela
            $query = "
                INSERT INTO schools (
                    school_name,
                    school_dane,
                    school_document,
                    total_quota,
                    director_user_id,
                    coordinator_user_id,
                    address,
                    phone,
                    email,
                    is_active
                ) VALUES (
                    :school_name,
                    :school_dane,
                    :school_document,
                    :total_quota,
                    :director_user_id,
                    :coordinator_user_id,
                    :address,
                    :phone,
                    :email,
                    1
                )
            ";

            $stmt = $this->dbConn->prepare($query);
            $stmt->execute([
                ':school_name'          => $data['school_name'],
                ':school_dane'          => $data['school_dane'],
                ':school_document'      => $data['school_document'],
                ':total_quota'          => $data['total_quota'] ?? 0,
                ':director_user_id'     => $data['director_user_id'],
                ':coordinator_user_id'  => $data['coordinator_user_id'] ?? null,
                ':address'              => $data['address'] ?? '',
                ':phone'                => $data['phone'] ?? '',
                ':email'                => $data['email'] ?? ''
            ]);

            $schoolId = $this->dbConn->lastInsertId();
            error_log("Escuela creada exitosamente con ID: $schoolId");

            return $schoolId;
        } catch (Exception $e) {
            error_log("Error en SchoolModel::createSchool: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Actualizar una escuela
     */
    public function updateSchool($data)
    {
        try {
            if (empty($data['school_name']) || empty($data['school_dane']) || empty($data['school_document'])) {
                throw new Exception('Los campos Nombre, DANE y NIT son obligatorios.');
            }

            if (empty($data['director_user_id'])) {
                throw new Exception('Debe seleccionar un director.');
            }

            // Verificar duplicados (excluyendo la escuela actual)
            if ($this->schoolExistsByDocumentOrDane($data['school_document'], $data['school_dane'], $data['school_id'])) {
                throw new Exception('Ya existe otra escuela con el mismo NIT o código DANE.');
            }

            if (!$this->userExistsWithRole($data['director_user_id'], 'director')) {
                throw new Exception('El director seleccionado no existe o no tiene el rol adecuado.');
            }

            if (!empty($data['coordinator_user_id'])) {
                if (!$this->userExistsWithRole($data['coordinator_user_id'], 'coordinator')) {
                    throw new Exception('El coordinador seleccionado no existe o no tiene el rol adecuado.');
                }
            }

            $query = "
                UPDATE schools SET
                    school_name = :school_name,
                    school_dane = :school_dane,
                    school_document = :school_document,
                    total_quota = :total_quota,
                    director_user_id = :director_user_id,
                    coordinator_user_id = :coordinator_user_id,
                    address = :address,
                    phone = :phone,
                    email = :email
                WHERE school_id = :school_id AND is_active = 1
            ";

            $stmt = $this->dbConn->prepare($query);
            $result = $stmt->execute([
                ':school_name'          => $data['school_name'],
                ':school_dane'          => $data['school_dane'],
                ':school_document'      => $data['school_document'],
                ':total_quota'          => $data['total_quota'] ?? 0,
                ':director_user_id'     => $data['director_user_id'],
                ':coordinator_user_id'  => $data['coordinator_user_id'] ?? null,
                ':address'              => $data['address'] ?? '',
                ':phone'                => $data['phone'] ?? '',
                ':email'                => $data['email'] ?? '',
                ':school_id'            => $data['school_id']
            ]);

            if ($result) {
                error_log("Escuela actualizada exitosamente. ID: " . $data['school_id']);
                return true;
            }

            return false;
        } catch (Exception $e) {
            error_log("Error en SchoolModel::updateSchool: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Marcar escuela como inactiva
     */
    public function deleteSchool($schoolId)
    {
        try {
            $query = "UPDATE schools SET is_active = 0 WHERE school_id = :school_id AND is_active = 1";
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute([':school_id' => $schoolId]);

            error_log("Escuela eliminada exitosamente. ID: $schoolId");
            return true;
        } catch (Exception $e) {
            error_log("Error en SchoolModel::deleteSchool: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Consultar escuelas con filtros
     */
    public function consultSchool($searchData = [])
    {
        try {
            $whereConditions = ["s.is_active = 1"];
            $params = [];

            if (!empty($searchData['nit'])) {
                $whereConditions[] = "s.school_document = :nit";
                $params[':nit'] = $searchData['nit'];
            }

            if (!empty($searchData['school_name'])) {
                $whereConditions[] = "s.school_name LIKE :school_name";
                $params[':school_name'] = "%" . $searchData['school_name'] . "%";
            }

            if (!empty($searchData['codigoDANE'])) {
                $whereConditions[] = "s.school_dane = :codigoDANE";
                $params[':codigoDANE'] = $searchData['codigoDANE'];
            }

            if (!empty($searchData['search'])) {
                $whereConditions[] = "(s.school_name LIKE :search
                    OR s.school_dane LIKE :search
                    OR s.school_document LIKE :search
                    OR s.address LIKE :search
                    OR s.email LIKE :search)";
                $params[':search'] = "%" . $searchData['search'] . "%";
            }

            $query = "
                SELECT 
                    s.school_id,
                    s.school_name,
                    s.school_dane,
                    s.school_document,
                    s.total_quota,
                    s.address,
                    s.phone,
                    s.email,
                    s.is_active,
                    CONCAT(d.first_name, ' ', d.last_name) AS director_name,
                    CONCAT(c.first_name, ' ', c.last_name) AS coordinator_name
                FROM schools s
                LEFT JOIN users d ON s.director_user_id = d.user_id
                LEFT JOIN users c ON s.coordinator_user_id = c.user_id
                WHERE " . implode(" AND ", $whereConditions) . "
                ORDER BY s.school_name
            ";

            $stmt = $this->dbConn->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en SchoolModel::consultSchool: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener todas las escuelas activas
     */
    public function getAllSchools()
    {
        return $this->consultSchool();
    }

    /**
     * Obtener una escuela por su ID
     */
    public function getSchoolById($schoolId)
    {
        try {
            $query = "
                SELECT 
                    s.school_id,
                    s.school_name,
                    s.school_dane,
                    s.school_document,
                    s.total_quota,
                    s.address,
                    s.phone,
                    s.email,
                    s.is_active,
                    s.director_user_id,
                    s.coordinator_user_id,
                    CONCAT(d.first_name, ' ', d.last_name) AS director_name,
                    CONCAT(c.first_name, ' ', c.last_name) AS coordinator_name
                FROM schools s
                LEFT JOIN users d ON s.director_user_id = d.user_id
                LEFT JOIN users c ON s.coordinator_user_id = c.user_id
                WHERE s.school_id = :school_id AND s.is_active = 1
            ";

            $stmt = $this->dbConn->prepare($query);
            $stmt->execute([':school_id' => $schoolId]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en SchoolModel::getSchoolById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar directores por documento (para AJAX)
     */
    public function searchDirectorsByDocument($document)
    {
        return $this->getDirectors($document);
    }

    /**
     * Obtener directores (opcionalmente por documento)
     */
    public function getDirectors($document = null)
    {
        return $this->getUsersByRole('director', $document);
    }

    /**
     * Obtener coordinadores (opcionalmente por documento)
     */
    public function getCoordinators($document = null)
    {
        return $this->getUsersByRole('coordinator', $document);
    }

    /**
     * Reutilizable para buscar usuarios por rol
     */
    private function getUsersByRole($role, $document = null)
    {
        try {
            $query = "
                SELECT u.user_id, u.first_name, u.last_name, u.email
                FROM users u
                INNER JOIN user_roles ur ON u.user_id = ur.user_id
                WHERE ur.role_type = :role AND u.is_active = 1 AND ur.is_active = 1
            ";

            $params = [':role' => $role];

            if (!empty($document)) {
                $query .= " AND u.credential_number = :document";
                $params[':document'] = $document;
            }

            $query .= " ORDER BY u.first_name, u.last_name";

            $stmt = $this->dbConn->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en SchoolModel::getUsersByRole: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Verificar si existe una escuela con mismo NIT o DANE
     */
    private function schoolExistsByDocumentOrDane($document, $dane, $excludeId = null)
    {
        $query = "
            SELECT school_id
            FROM schools
            WHERE (school_document = :document OR school_dane = :dane)
        ";

        $params = [
            ':document' => $document,
            ':dane'     => $dane
        ];

        if ($excludeId) {
            $query .= " AND school_id != :excludeId";
            $params[':excludeId'] = $excludeId;
        }

        $stmt = $this->dbConn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch() ? true : false;
    }

    /**
     * Verificar existencia de usuario con determinado rol
     */
    private function userExistsWithRole($userId, $role)
    {
        $query = "
            SELECT u.user_id
            FROM users u
            INNER JOIN user_roles ur ON u.user_id = ur.user_id
            WHERE u.user_id = :user_id
              AND ur.role_type = :role
              AND u.is_active = 1
              AND ur.is_active = 1
        ";

        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([
            ':user_id' => $userId,
            ':role'    => $role
        ]);

        return $stmt->fetch() ? true : false;
    }
}
