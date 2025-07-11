<?php
require_once 'mainModel.php';

class SchoolModel extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //Función para crear un colegio
    public function createSchool($data)
    {
        try {
            // Validar que los datos requeridos estén presentes
            if (empty($data['school_name']) || empty($data['school_dane']) || empty($data['school_document'])) {
                throw new Exception('Los campos Nombre del colegio, DANE y NIT son obligatorios');
            }
            
            // Validar que se haya proporcionado un director
            if (empty($data['director_user_id'])) {
                throw new Exception('Debe seleccionar un director para la escuela');
            }
            
            // Verificar si ya existe una escuela con el mismo NIT o código DANE
            $checkQuery = "SELECT school_id FROM schools WHERE school_document = :school_document OR school_dane = :school_dane";
            $checkStmt = $this->dbConn->prepare($checkQuery);
            $checkStmt->execute([
                ':school_document' => $data['school_document'],
                ':school_dane' => $data['school_dane']
            ]);
            
            if ($checkStmt->fetch()) {
                throw new Exception('Ya existe una escuela con el mismo NIT o código DANE');
            }
            
            // Verificar que el director existe y tiene el rol correcto
            $directorQuery = "SELECT u.user_id FROM users u 
                             INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                             WHERE u.user_id = :director_id AND ur.role_type = 'director' 
                             AND u.is_active = 1 AND ur.is_active = 1";
            $directorStmt = $this->dbConn->prepare($directorQuery);
            $directorStmt->execute([':director_id' => $data['director_user_id']]);
            
            if (!$directorStmt->fetch()) {
                throw new Exception('El director seleccionado no existe o no tiene el rol correcto');
            }
            
            // Verificar que el coordinador existe y tiene el rol correcto (si se proporciona)
            if (!empty($data['coordinator_user_id'])) {
                $coordinatorQuery = "SELECT u.user_id FROM users u 
                                   INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                                   WHERE u.user_id = :coordinator_id AND ur.role_type = 'coordinator' 
                                   AND u.is_active = 1 AND ur.is_active = 1";
                $coordinatorStmt = $this->dbConn->prepare($coordinatorQuery);
                $coordinatorStmt->execute([':coordinator_id' => $data['coordinator_user_id']]);
                
                if (!$coordinatorStmt->fetch()) {
                    throw new Exception('El coordinador seleccionado no existe o no tiene el rol correcto');
                }
            }
            
            // Insertar la nueva escuela
            $query = "INSERT INTO schools (
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
            )
            VALUES (
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
            )";
            
            $stmt = $this->dbConn->prepare($query);
            $result = $stmt->execute([
                ':school_name' => $data['school_name'],
                ':school_dane' => $data['school_dane'],
                ':school_document' => $data['school_document'],
                ':total_quota' => $data['total_quota'] ?? 0,
                ':director_user_id' => $data['director_user_id'],
                ':coordinator_user_id' => $data['coordinator_user_id'] ?? null,
                ':address' => $data['address'] ?? '',
                ':phone' => $data['phone'] ?? '',
                ':email' => $data['email'] ?? ''
            ]);
            
            if ($result) {
                $schoolId = $this->dbConn->lastInsertId();
                error_log("Escuela creada exitosamente con ID: " . $schoolId);
                return $schoolId;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Error en SchoolModel::createSchool: " . $e->getMessage());
            throw $e;
        }
    }

    //Función para consultar un colegio
    public function consultSchool($searchData = [])
    {
        try {
            // Construir la consulta dinámicamente basada en los parámetros proporcionados
            $whereConditions = [];
            $params = [];
            
            if (!empty($searchData['nit'])) {
                $whereConditions[] = "s.school_document = :nit";
                $params[':nit'] = $searchData['nit'];
            }
            
            if (!empty($searchData['school_name'])) {
                $whereConditions[] = "s.school_name LIKE :school_name";
                $params[':school_name'] = '%' . $searchData['school_name'] . '%';
            }
            
            if (!empty($searchData['codigoDANE'])) {
                $whereConditions[] = "s.school_dane = :codigoDANE";
                $params[':codigoDANE'] = $searchData['codigoDANE'];
            }
            
            // Si no hay condiciones específicas, buscar en todos los campos
            if (empty($whereConditions)) {
                $searchTerm = $searchData['search'] ?? '';
                if (!empty($searchTerm)) {
                    $whereConditions[] = "(s.school_name LIKE :search OR s.school_dane LIKE :search OR s.school_document LIKE :search OR s.address LIKE :search OR s.email LIKE :search)";
                    $params[':search'] = '%' . $searchTerm . '%';
                }
            }
            
            // Agregar condición de escuela activa
            $whereConditions[] = "s.is_active = 1";
            
            $query = "SELECT 
                        s.school_id,
                        s.school_name,
                        s.school_dane,
                        s.school_document,
                        s.total_quota,
                        s.address,
                        s.phone,
                        s.email,
                        s.is_active,
                        CONCAT(d.first_name, ' ', d.last_name) as director_name,
                        CONCAT(c.first_name, ' ', c.last_name) as coordinator_name
                      FROM schools s
                      LEFT JOIN users d ON s.director_user_id = d.user_id
                      LEFT JOIN users c ON s.coordinator_user_id = c.user_id
                      WHERE " . implode(' AND ', $whereConditions) . "
                      ORDER BY s.school_name";
            
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en SchoolModel::consultSchool: " . $e->getMessage());
            return [];
        }
    }

    // Función para obtener todas las escuelas
    public function getAllSchools()
    {
        try {
            $query = "SELECT 
                        s.school_id,
                        s.school_name,
                        s.school_dane,
                        s.school_document,
                        s.total_quota,
                        s.address,
                        s.phone,
                        s.email,
                        s.is_active,
                        CONCAT(d.first_name, ' ', d.last_name) as director_name,
                        CONCAT(c.first_name, ' ', c.last_name) as coordinator_name
                      FROM schools s
                      LEFT JOIN users d ON s.director_user_id = d.user_id
                      LEFT JOIN users c ON s.coordinator_user_id = c.user_id
                      WHERE s.is_active = 1
                      ORDER BY s.school_name";
            
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en SchoolModel::getAllSchools: " . $e->getMessage());
            return [];
        }
    }

    // Función para obtener una escuela por ID
    public function getSchoolById($schoolId)
    {
        try {
            $query = "SELECT 
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
                        CONCAT(d.first_name, ' ', d.last_name) as director_name,
                        CONCAT(c.first_name, ' ', c.last_name) as coordinator_name
                      FROM schools s
                      LEFT JOIN users d ON s.director_user_id = d.user_id
                      LEFT JOIN users c ON s.coordinator_user_id = c.user_id
                      WHERE s.school_id = :school_id AND s.is_active = 1";
            
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute([':school_id' => $schoolId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en SchoolModel::getSchoolById: " . $e->getMessage());
            return null;
        }
    }

    // Función para obtener directores disponibles
    public function getDirectors()
    {
        try {
            $query = "SELECT user_id, first_name, last_name, email 
                      FROM users u
                      INNER JOIN user_roles ur ON u.user_id = ur.user_id
                      WHERE ur.role_type = 'director' AND u.is_active = 1 AND ur.is_active = 1
                      ORDER BY first_name, last_name";
            
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en SchoolModel::getDirectors: " . $e->getMessage());
            return [];
        }
    }

    // Función para obtener coordinadores disponibles
    public function getCoordinators()
    {
        try {
            $query = "SELECT user_id, first_name, last_name, email 
                      FROM users u
                      INNER JOIN user_roles ur ON u.user_id = ur.user_id
                      WHERE ur.role_type = 'coordinator' AND u.is_active = 1 AND ur.is_active = 1
                      ORDER BY first_name, last_name";
            
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en SchoolModel::getCoordinators: " . $e->getMessage());
            return [];
        }
    }

    // Función para actualizar una escuela
    public function updateSchool($data)
    {
        try {
            // Validar que los datos requeridos estén presentes
            if (empty($data['school_name']) || empty($data['school_dane']) || empty($data['school_document'])) {
                throw new Exception('Los campos Nombre del colegio, DANE y NIT son obligatorios');
            }
            
            // Validar que se haya proporcionado un director
            if (empty($data['director_user_id'])) {
                throw new Exception('Debe seleccionar un director para la escuela');
            }
            
            // Verificar si ya existe otra escuela con el mismo NIT o código DANE (excluyendo la actual)
            $checkQuery = "SELECT school_id FROM schools WHERE (school_document = :school_document OR school_dane = :school_dane) AND school_id != :school_id";
            $checkStmt = $this->dbConn->prepare($checkQuery);
            $checkStmt->execute([
                ':school_document' => $data['school_document'],
                ':school_dane' => $data['school_dane'],
                ':school_id' => $data['school_id']
            ]);
            
            if ($checkStmt->fetch()) {
                throw new Exception('Ya existe otra escuela con el mismo NIT o código DANE');
            }
            
            // Verificar que el director existe y tiene el rol correcto
            $directorQuery = "SELECT u.user_id FROM users u 
                             INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                             WHERE u.user_id = :director_id AND ur.role_type = 'director' 
                             AND u.is_active = 1 AND ur.is_active = 1";
            $directorStmt = $this->dbConn->prepare($directorQuery);
            $directorStmt->execute([':director_id' => $data['director_user_id']]);
            
            if (!$directorStmt->fetch()) {
                throw new Exception('El director seleccionado no existe o no tiene el rol correcto');
            }
            
            // Verificar que el coordinador existe y tiene el rol correcto (si se proporciona)
            if (!empty($data['coordinator_user_id'])) {
                $coordinatorQuery = "SELECT u.user_id FROM users u 
                                   INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                                   WHERE u.user_id = :coordinator_id AND ur.role_type = 'coordinator' 
                                   AND u.is_active = 1 AND ur.is_active = 1";
                $coordinatorStmt = $this->dbConn->prepare($coordinatorQuery);
                $coordinatorStmt->execute([':coordinator_id' => $data['coordinator_user_id']]);
                
                if (!$coordinatorStmt->fetch()) {
                    throw new Exception('El coordinador seleccionado no existe o no tiene el rol correcto');
                }
            }
            
            // Actualizar la escuela
            $query = "UPDATE schools SET 
                        school_name = :school_name,
                        school_dane = :school_dane,
                        school_document = :school_document,
                        total_quota = :total_quota,
                        director_user_id = :director_user_id,
                        coordinator_user_id = :coordinator_user_id,
                        address = :address,
                        phone = :phone,
                        email = :email
                      WHERE school_id = :school_id AND is_active = 1";
            
            $stmt = $this->dbConn->prepare($query);
            $result = $stmt->execute([
                ':school_name' => $data['school_name'],
                ':school_dane' => $data['school_dane'],
                ':school_document' => $data['school_document'],
                ':total_quota' => $data['total_quota'] ?? 0,
                ':director_user_id' => $data['director_user_id'],
                ':coordinator_user_id' => $data['coordinator_user_id'] ?? null,
                ':address' => $data['address'] ?? '',
                ':phone' => $data['phone'] ?? '',
                ':email' => $data['email'] ?? '',
                ':school_id' => $data['school_id']
            ]);
            
            if ($result) {
                error_log("Escuela actualizada exitosamente con ID: " . $data['school_id']);
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Error en SchoolModel::updateSchool: " . $e->getMessage());
            throw $e;
        }
    }

    // Función para eliminar una escuela (marcar como inactiva)
    public function deleteSchool($schoolId)
    {
        try {
            // Verificar que la escuela existe y está activa
            $checkQuery = "SELECT school_id FROM schools WHERE school_id = :school_id AND is_active = 1";
            $checkStmt = $this->dbConn->prepare($checkQuery);
            $checkStmt->execute([':school_id' => $schoolId]);
            
            if (!$checkStmt->fetch()) {
                throw new Exception('La escuela no existe o ya fue eliminada');
            }
            
            // Marcar como inactiva en lugar de eliminar físicamente
            $query = "UPDATE schools SET is_active = 0 WHERE school_id = :school_id";
            
            $stmt = $this->dbConn->prepare($query);
            $result = $stmt->execute([':school_id' => $schoolId]);
            
            if ($result) {
                error_log("Escuela eliminada exitosamente con ID: " . $schoolId);
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Error en SchoolModel::deleteSchool: " . $e->getMessage());
            throw $e;
        }
    }
}
