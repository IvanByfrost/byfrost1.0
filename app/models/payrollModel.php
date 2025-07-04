<?php
require_once ROOT . '/app/scripts/connection.php';

class PayrollModel {
    private $conn;
    
    public function __construct() {
        $this->conn = getConnection();
    }
    
    // =============================================
    // MÉTODOS PARA EMPLEADOS
    // =============================================
    
    /**
     * Obtener todos los empleados activos
     */
    public function getAllEmployees($filters = []) {
        $sql = "SELECT e.*, u.first_name, u.last_name, u.email, u.phone, ur.role_type 
                FROM employees e 
                INNER JOIN users u ON e.user_id = u.user_id 
                INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                WHERE e.is_active = 1 AND ur.is_active = 1";
        
        $params = [];
        
        if (!empty($filters['department'])) {
            $sql .= " AND e.department = ?";
            $params[] = $filters['department'];
        }
        
        if (!empty($filters['position'])) {
            $sql .= " AND e.position = ?";
            $params[] = $filters['position'];
        }
        
        $sql .= " ORDER BY u.first_name, u.last_name";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener usuarios que pueden ser empleados (excluye estudiantes y padres)
     */
    public function getAvailableUsersForEmployee() {
        $sql = "SELECT u.user_id, u.first_name, u.last_name, u.email, u.credential_number, ur.role_type
                FROM users u 
                INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                WHERE u.is_active = 1 
                AND ur.is_active = 1 
                AND ur.role_type IN ('professor', 'coordinator', 'director', 'treasurer', 'root')
                AND u.user_id NOT IN (SELECT user_id FROM employees WHERE is_active = 1)
                ORDER BY u.first_name, u.last_name";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Validar que un usuario puede ser empleado
     */
    public function canUserBeEmployee($userId) {
        $sql = "SELECT COUNT(*) as count 
                FROM users u 
                INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                WHERE u.user_id = ? 
                AND u.is_active = 1 
                AND ur.is_active = 1 
                AND ur.role_type IN ('professor', 'coordinator', 'director', 'treasurer', 'root')
                AND u.user_id NOT IN (SELECT user_id FROM employees WHERE is_active = 1)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    /**
     * Obtener empleado por ID
     */
    public function getEmployeeById($employeeId) {
        $sql = "SELECT e.*, u.first_name, u.last_name, u.email, u.phone, u.role_id 
                FROM employees e 
                INNER JOIN users u ON e.user_id = u.user_id 
                WHERE e.employee_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$employeeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear nuevo empleado
     */
    public function createEmployee($data) {
        // Validar que el usuario puede ser empleado
        if (!$this->canUserBeEmployee($data['user_id'])) {
            throw new Exception('El usuario seleccionado no puede ser empleado. Solo profesores, coordinadores, directores, tesoreros y administradores pueden ser empleados.');
        }
        
        // Verificar que el código de empleado sea único
        $checkSql = "SELECT COUNT(*) FROM employees WHERE employee_code = ? AND is_active = 1";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->execute([$data['employee_code']]);
        if ($checkStmt->fetchColumn() > 0) {
            throw new Exception('El código de empleado ya existe.');
        }
        
        $sql = "INSERT INTO employees (user_id, employee_code, position, department, 
                hire_date, salary, contract_type, work_schedule, bank_account, bank_name) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['user_id'],
            $data['employee_code'],
            $data['position'],
            $data['department'],
            $data['hire_date'],
            $data['salary'],
            $data['contract_type'],
            $data['work_schedule'] ?? null,
            $data['bank_account'] ?? null,
            $data['bank_name'] ?? null
        ]);
    }
    
    /**
     * Actualizar empleado
     */
    public function updateEmployee($employeeId, $data) {
        $sql = "UPDATE employees SET 
                position = ?, department = ?, salary = ?, contract_type = ?, 
                work_schedule = ?, bank_account = ?, bank_name = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE employee_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['position'],
            $data['department'],
            $data['salary'],
            $data['contract_type'],
            $data['work_schedule'] ?? null,
            $data['bank_account'] ?? null,
            $data['bank_name'] ?? null,
            $employeeId
        ]);
    }
    
    /**
     * Desactivar empleado
     */
    public function deactivateEmployee($employeeId) {
        $sql = "UPDATE employees SET is_active = 0, updated_at = CURRENT_TIMESTAMP WHERE employee_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$employeeId]);
    }
    
    // =============================================
    // MÉTODOS PARA PERÍODOS DE NÓMINA
    // =============================================
    
    /**
     * Obtener todos los períodos de nómina
     */
    public function getAllPeriods($filters = []) {
        $sql = "SELECT p.*, u.first_name as created_by_name 
                FROM payroll_periods p 
                INNER JOIN users u ON p.created_by_user_id = u.user_id";
        
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " WHERE p.status = ?";
            $params[] = $filters['status'];
        }
        
        $sql .= " ORDER BY p.start_date DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener período por ID
     */
    public function getPeriodById($periodId) {
        $sql = "SELECT p.*, u.first_name as created_by_name 
                FROM payroll_periods p 
                INNER JOIN users u ON p.created_by_user_id = u.user_id 
                WHERE p.period_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$periodId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear nuevo período de nómina
     */
    public function createPeriod($data) {
        $sql = "INSERT INTO payroll_periods (period_name, start_date, end_date, payment_date, created_by_user_id) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['period_name'],
            $data['start_date'],
            $data['end_date'],
            $data['payment_date'],
            $data['created_by_user_id']
        ]);
    }
    
    /**
     * Actualizar estado del período
     */
    public function updatePeriodStatus($periodId, $status) {
        $sql = "UPDATE payroll_periods SET status = ?, closed_at = ? WHERE period_id = ?";
        $closedAt = ($status === 'closed' || $status === 'paid') ? date('Y-m-d H:i:s') : null;
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $closedAt, $periodId]);
    }
    
    // =============================================
    // MÉTODOS PARA CONCEPTOS DE NÓMINA
    // =============================================
    
    /**
     * Obtener todos los conceptos de nómina
     */
    public function getAllConcepts($filters = []) {
        $sql = "SELECT * FROM payroll_concepts WHERE is_active = 1";
        
        if (!empty($filters['concept_type'])) {
            $sql .= " AND concept_type = ?";
        }
        
        if (!empty($filters['concept_category'])) {
            $sql .= " AND concept_category = ?";
        }
        
        $sql .= " ORDER BY concept_type, concept_name";
        
        $stmt = $this->conn->prepare($sql);
        $params = [];
        
        if (!empty($filters['concept_type'])) {
            $params[] = $filters['concept_type'];
        }
        
        if (!empty($filters['concept_category'])) {
            $params[] = $filters['concept_category'];
        }
        
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener conceptos obligatorios
     */
    public function getMandatoryConcepts() {
        $sql = "SELECT * FROM payroll_concepts WHERE is_mandatory = 1 AND is_active = 1 ORDER BY concept_type, concept_name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // =============================================
    // MÉTODOS PARA REGISTROS DE NÓMINA
    // =============================================
    
    /**
     * Obtener registros de nómina por período
     */
    public function getPayrollRecordsByPeriod($periodId) {
        $sql = "SELECT pr.*, e.employee_code, e.position, e.department, 
                       u.first_name, u.last_name, u.email,
                       p.period_name, p.start_date, p.end_date
                FROM payroll_records pr 
                INNER JOIN employees e ON pr.employee_id = e.employee_id 
                INNER JOIN users u ON e.user_id = u.user_id 
                INNER JOIN payroll_periods p ON pr.period_id = p.period_id 
                WHERE pr.period_id = ? 
                ORDER BY u.first_name, u.last_name";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$periodId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener registro de nómina específico
     */
    public function getPayrollRecord($recordId) {
        $sql = "SELECT pr.*, e.employee_code, e.position, e.department, 
                       u.first_name, u.last_name, u.email,
                       p.period_name, p.start_date, p.end_date
                FROM payroll_records pr 
                INNER JOIN employees e ON pr.employee_id = e.employee_id 
                INNER JOIN users u ON e.user_id = u.user_id 
                INNER JOIN payroll_periods p ON pr.period_id = p.period_id 
                WHERE pr.record_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$recordId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear registro de nómina
     */
    public function createPayrollRecord($data) {
        $sql = "INSERT INTO payroll_records (period_id, employee_id, base_salary, 
                total_income, total_deductions, net_salary, working_days, notes, created_by_user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['period_id'],
            $data['employee_id'],
            $data['base_salary'],
            $data['total_income'],
            $data['total_deductions'],
            $data['net_salary'],
            $data['working_days'],
            $data['notes'] ?? null,
            $data['created_by_user_id']
        ]);
    }
    
    /**
     * Actualizar registro de nómina
     */
    public function updatePayrollRecord($recordId, $data) {
        $sql = "UPDATE payroll_records SET 
                base_salary = ?, total_income = ?, total_deductions = ?, 
                net_salary = ?, working_days = ?, notes = ?, status = ?, 
                updated_at = CURRENT_TIMESTAMP 
                WHERE record_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['base_salary'],
            $data['total_income'],
            $data['total_deductions'],
            $data['net_salary'],
            $data['working_days'],
            $data['notes'] ?? null,
            $data['status'],
            $recordId
        ]);
    }
    
    /**
     * Generar nómina automática para un período
     */
    public function generatePayrollForPeriod($periodId, $createdByUserId) {
        try {
            $this->conn->beginTransaction();
            
            // Obtener empleados activos
            $employees = $this->getAllEmployees();
            $mandatoryConcepts = $this->getMandatoryConcepts();
            
            foreach ($employees as $employee) {
                // Crear registro de nómina
                $payrollData = [
                    'period_id' => $periodId,
                    'employee_id' => $employee['employee_id'],
                    'base_salary' => $employee['salary'],
                    'total_income' => $employee['salary'],
                    'total_deductions' => 0,
                    'net_salary' => $employee['salary'],
                    'working_days' => 30,
                    'created_by_user_id' => $createdByUserId
                ];
                
                $this->createPayrollRecord($payrollData);
                $recordId = $this->conn->lastInsertId();
                
                // Aplicar conceptos obligatorios
                foreach ($mandatoryConcepts as $concept) {
                    if ($concept['concept_type'] === 'deduction') {
                        $conceptValue = $concept['is_percentage'] ? 
                            ($employee['salary'] * $concept['default_value'] / 100) : 
                            $concept['default_value'];
                        
                        $this->addConceptDetail($recordId, $concept['concept_id'], $conceptValue, $concept['default_value']);
                        
                        // Actualizar totales
                        $payrollData['total_deductions'] += $conceptValue;
                        $payrollData['net_salary'] -= $conceptValue;
                    }
                }
                
                // Actualizar registro con totales finales
                $this->updatePayrollRecord($recordId, $payrollData);
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
    
    // =============================================
    // MÉTODOS PARA DETALLES DE CONCEPTOS
    // =============================================
    
    /**
     * Agregar detalle de concepto a un registro de nómina
     */
    public function addConceptDetail($recordId, $conceptId, $value, $percentage = 0) {
        $sql = "INSERT INTO payroll_concept_details (record_id, concept_id, concept_value, concept_percentage) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$recordId, $conceptId, $value, $percentage]);
    }
    
    /**
     * Obtener detalles de conceptos de un registro
     */
    public function getConceptDetails($recordId) {
        $sql = "SELECT pcd.*, pc.concept_name, pc.concept_type, pc.concept_category 
                FROM payroll_concept_details pcd 
                INNER JOIN payroll_concepts pc ON pcd.concept_id = pc.concept_id 
                WHERE pcd.record_id = ? 
                ORDER BY pc.concept_type, pc.concept_name";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$recordId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // =============================================
    // MÉTODOS PARA AUSENCIAS
    // =============================================
    
    /**
     * Obtener ausencias de un empleado
     */
    public function getEmployeeAbsences($employeeId, $periodId = null) {
        $sql = "SELECT ea.*, e.employee_code, u.first_name, u.last_name 
                FROM employee_absences ea 
                INNER JOIN employees e ON ea.employee_id = e.employee_id 
                INNER JOIN users u ON e.user_id = u.user_id 
                WHERE ea.employee_id = ?";
        
        $params = [$employeeId];
        
        if ($periodId) {
            $sql .= " AND ea.start_date BETWEEN (SELECT start_date FROM payroll_periods WHERE period_id = ?) 
                     AND (SELECT end_date FROM payroll_periods WHERE period_id = ?)";
            $params[] = $periodId;
            $params[] = $periodId;
        }
        
        $sql .= " ORDER BY ea.start_date DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear ausencia
     */
    public function createAbsence($data) {
        $sql = "INSERT INTO employee_absences (employee_id, absence_type, start_date, end_date, 
                days_count, is_paid, reason) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['employee_id'],
            $data['absence_type'],
            $data['start_date'],
            $data['end_date'],
            $data['days_count'],
            $data['is_paid'],
            $data['reason'] ?? null
        ]);
    }
    
    // =============================================
    // MÉTODOS PARA HORAS EXTRAS
    // =============================================
    
    /**
     * Obtener horas extras de un empleado
     */
    public function getEmployeeOvertime($employeeId, $periodId = null) {
        $sql = "SELECT eo.*, e.employee_code, u.first_name, u.last_name 
                FROM employee_overtime eo 
                INNER JOIN employees e ON eo.employee_id = e.employee_id 
                INNER JOIN users u ON e.user_id = u.user_id 
                WHERE eo.employee_id = ?";
        
        $params = [$employeeId];
        
        if ($periodId) {
            $sql .= " AND eo.period_id = ?";
            $params[] = $periodId;
        }
        
        $sql .= " ORDER BY eo.date_worked DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear registro de horas extras
     */
    public function createOvertime($data) {
        $sql = "INSERT INTO employee_overtime (employee_id, period_id, date_worked, 
                hours_worked, hourly_rate, total_amount, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['employee_id'],
            $data['period_id'],
            $data['date_worked'],
            $data['hours_worked'],
            $data['hourly_rate'],
            $data['total_amount'],
            $data['description'] ?? null
        ]);
    }
    
    // =============================================
    // MÉTODOS PARA BONIFICACIONES
    // =============================================
    
    /**
     * Obtener bonificaciones de un empleado
     */
    public function getEmployeeBonuses($employeeId, $periodId = null) {
        $sql = "SELECT eb.*, e.employee_code, u.first_name, u.last_name 
                FROM employee_bonuses eb 
                INNER JOIN employees e ON eb.employee_id = e.employee_id 
                INNER JOIN users u ON e.user_id = u.user_id 
                WHERE eb.employee_id = ?";
        
        $params = [$employeeId];
        
        if ($periodId) {
            $sql .= " AND eb.period_id = ?";
            $params[] = $periodId;
        }
        
        $sql .= " ORDER BY eb.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear bonificación
     */
    public function createBonus($data) {
        $sql = "INSERT INTO employee_bonuses (employee_id, period_id, bonus_type, amount, description) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['employee_id'],
            $data['period_id'],
            $data['bonus_type'],
            $data['amount'],
            $data['description'] ?? null
        ]);
    }
    
    // =============================================
    // MÉTODOS DE ESTADÍSTICAS
    // =============================================
    
    /**
     * Obtener estadísticas de nómina por período
     */
    public function getPayrollStatistics($periodId) {
        $sql = "SELECT 
                    COUNT(*) as total_employees,
                    SUM(base_salary) as total_base_salary,
                    SUM(total_income) as total_income,
                    SUM(total_deductions) as total_deductions,
                    SUM(net_salary) as total_net_salary,
                    AVG(net_salary) as average_salary
                FROM payroll_records 
                WHERE period_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$periodId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener resumen de nómina por departamento
     */
    public function getPayrollByDepartment($periodId) {
        $sql = "SELECT 
                    e.department,
                    COUNT(*) as employee_count,
                    SUM(pr.net_salary) as total_salary
                FROM payroll_records pr 
                INNER JOIN employees e ON pr.employee_id = e.employee_id 
                WHERE pr.period_id = ? 
                GROUP BY e.department 
                ORDER BY total_salary DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$periodId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // =============================================
    // MÉTODOS FALTANTES PARA LISTAS COMPLETAS
    // =============================================
    
    /**
     * Obtener todas las ausencias con filtros
     */
    public function getAllAbsences($filters = []) {
        $sql = "SELECT ea.*, e.employee_code, u.first_name, u.last_name, e.department 
                FROM employee_absences ea 
                INNER JOIN employees e ON ea.employee_id = e.employee_id 
                INNER JOIN users u ON e.user_id = u.user_id";
        
        $params = [];
        
        if (!empty($filters['employee_id'])) {
            $sql .= " AND ea.employee_id = ?";
            $params[] = $filters['employee_id'];
        }
        
        if (!empty($filters['period_id'])) {
            $sql .= " AND ea.start_date BETWEEN (SELECT start_date FROM payroll_periods WHERE period_id = ?) 
                     AND (SELECT end_date FROM payroll_periods WHERE period_id = ?)";
            $params[] = $filters['period_id'];
            $params[] = $filters['period_id'];
        }
        
        $sql .= " ORDER BY ea.start_date DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener todas las horas extra con filtros
     */
    public function getAllOvertime($filters = []) {
        $sql = "SELECT eo.*, e.employee_code, u.first_name, u.last_name, e.department 
                FROM employee_overtime eo 
                INNER JOIN employees e ON eo.employee_id = e.employee_id 
                INNER JOIN users u ON e.user_id = u.user_id";
        
        $params = [];
        
        if (!empty($filters['employee_id'])) {
            $sql .= " AND eo.employee_id = ?";
            $params[] = $filters['employee_id'];
        }
        
        if (!empty($filters['period_id'])) {
            $sql .= " AND eo.period_id = ?";
            $params[] = $filters['period_id'];
        }
        
        $sql .= " ORDER BY eo.date_worked DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener todas las bonificaciones con filtros
     */
    public function getAllBonuses($filters = []) {
        $sql = "SELECT eb.*, e.employee_code, u.last_name, e.department 
                FROM employee_bonuses eb 
                INNER JOIN employees e ON eb.employee_id = e.employee_id 
                INNER JOIN users u ON e.user_id = u.user_id";
        
        $params = [];
        
        if (!empty($filters['employee_id'])) {
            $sql .= " AND eb.employee_id = ?";
            $params[] = $filters['employee_id'];
        }
        
        if (!empty($filters['period_id'])) {
            $sql .= " AND eb.period_id = ?";
            $params[] = $filters['period_id'];
        }
        
        $sql .= " ORDER BY eb.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 