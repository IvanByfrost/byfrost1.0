<?php
require_once 'mainModel.php';

class SchoolModel extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //Función para crear un colegio
    public function createSchool()
    {
        //Implementar la lógica para crear un colegio
        $query = "INSERT INTO schools (
            school_name,
            school_dane,
            school_document,
            total_quota,
            director_user_id,
            coordinator_user_id,
            address,
            phone,
            email
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
            :email
        )";
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([
            ':school_name' => $_POST['school_name'],
            ':school_dane' => $_POST['school_dane'],
            ':school_document' => $_POST['school_document'],
            ':total_quota' => $_POST['total_quota'] ?? 0,
            ':director_user_id' => $_POST['director_user_id'] ?? null,
            ':coordinator_user_id' => $_POST['coordinator_user_id'] ?? null,
            ':address' => $_POST['address'] ?? '',
            ':phone' => $_POST['phone'] ?? '',
            ':email' => $_POST['email'] ?? ''
        ]);
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
}
