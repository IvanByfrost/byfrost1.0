<?php
<<<<<<< HEAD
require_once 'app/models/payrollModel.php';
require_once 'app/library/SessionManager.php';
require_once 'app/library/SecurityMiddleware.php';
=======
require_once ROOT . '/app/models/payrollModel.php';
require_once ROOT . '/app/library/SessionManager.php';
require_once ROOT . '/app/library/SecurityMiddleware.php';
require_once ROOT . '/app/controllers/MainController.php';
>>>>>>> 354953949cbaabb18f70f97b3e178d662b4ac9fb

class PayrollController extends MainController {
    protected $payrollModel;
    protected $sessionManager;
    protected $securityMiddleware;
    
    public function __construct($dbConn = null, $view = null) {
        $this->payrollModel = new PayrollModel();
        $this->sessionManager = new SessionManager();
        $this->securityMiddleware = new SecurityMiddleware();
    }
    
    // =============================================
    // MAIN PAYROLL METHODS
    // =============================================
    
    /**
     * Main payroll dashboard
     */
    public function dashboard() {
<<<<<<< HEAD
        // Check permissions
        if (!$this->securityMiddleware->hasRole(['root', 'director', 'treasurer'])) {
            header('Location: index.php?controller=error&action=unauthorized');
=======
        // Verificar permisos
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
>>>>>>> b580a54ac9ce32e1ee86e4d2f3a0fb94b2f6af3d
            exit;
        }
        
        try {
            // Get general statistics
            $currentPeriod = $this->payrollModel->getAllPeriods(['status' => 'open']);
            $employees = $this->payrollModel->getAllEmployees();
            $recentPeriods = $this->payrollModel->getAllPeriods(['status' => 'closed']);
            
            $data = [
                'current_period' => $currentPeriod[0] ?? null,
                'total_employees' => count($employees),
                'recent_periods' => array_slice($recentPeriods, 0, 5),
                'page_title' => 'Payroll Dashboard'
            ];
            
            $this->loadPartialView('payroll/dashboard', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Employee list
     */
    public function employees() {
<<<<<<< HEAD
        if (!$this->securityMiddleware->hasRole(['root', 'director', 'treasurer'])) {
            header('Location: index.php?controller=error&action=unauthorized');
=======
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
>>>>>>> b580a54ac9ce32e1ee86e4d2f3a0fb94b2f6af3d
            exit;
        }
        
        try {
            $filters = [];
            if (isset($_GET['department'])) $filters['department'] = $_GET['department'];
            if (isset($_GET['position'])) $filters['position'] = $_GET['position'];
            
            $employees = $this->payrollModel->getAllEmployees($filters);
            
            $data = [
                'employees' => $employees,
                'filters' => $filters,
                'page_title' => 'Employee Management'
            ];
            
            $this->loadPartialView('payroll/employees', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Create new employee
     */
    public function createEmployee() {
<<<<<<< HEAD
        if (!$this->securityMiddleware->hasRole(['root', 'director'])) {
            header('Location: index.php?controller=error&action=unauthorized');
=======
        if (!$this->sessionManager->hasRole(['root', 'director'])) {
            header('Location: ?view=unauthorized');
>>>>>>> b580a54ac9ce32e1ee86e4d2f3a0fb94b2f6af3d
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'user_id' => $_POST['user_id'],
                    'employee_code' => $_POST['employee_code'],
                    'position' => $_POST['position'],
                    'department' => $_POST['department'],
                    'hire_date' => $_POST['hire_date'],
                    'salary' => $_POST['salary'],
                    'contract_type' => $_POST['contract_type'],
                    'work_schedule' => $_POST['work_schedule'] ?? null,
                    'bank_account' => $_POST['bank_account'] ?? null,
                    'bank_name' => $_POST['bank_name'] ?? null
                ];
                
                if ($this->payrollModel->createEmployee($data)) {
                    header('Location: ?view=payroll&action=employees&success=1');
                    exit;
                } else {
                    throw new Exception('Error creating employee');
                }
                
            } catch (Exception $e) {
                $this->loadPartialView('payroll/createEmployee', [
                    'error' => $e->getMessage(),
                    'data' => $_POST
                ]);
            }
        } else {
<<<<<<< HEAD
            $this->loadView('payroll/createEmployee', ['page_title' => 'Create Employee']);
=======
            try {
                $availableUsers = $this->payrollModel->getAvailableUsersForEmployee();
                $this->loadPartialView('payroll/createEmployee', [
                    'page_title' => 'Crear Empleado',
                    'available_users' => $availableUsers
                ]);
            } catch (Exception $e) {
                $this->loadPartialView('payroll/createEmployee', [
                    'page_title' => 'Crear Empleado',
                    'error' => $e->getMessage()
                ]);
            }
>>>>>>> 354953949cbaabb18f70f97b3e178d662b4ac9fb
        }
    }
    
    /**
     * Edit employee
     */
    public function editEmployee() {
<<<<<<< HEAD
        if (!$this->securityMiddleware->hasRole(['root', 'director'])) {
            header('Location: index.php?controller=error&action=unauthorized');
=======
        if (!$this->sessionManager->hasRole(['root', 'director'])) {
            header('Location: ?view=unauthorized');
>>>>>>> b580a54ac9ce32e1ee86e4d2f3a0fb94b2f6af3d
            exit;
        }
        
        $employeeId = $_GET['id'] ?? null;
        if (!$employeeId) {
            header('Location: ?view=payroll&action=employees');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'position' => $_POST['position'],
                    'department' => $_POST['department'],
                    'salary' => $_POST['salary'],
                    'contract_type' => $_POST['contract_type'],
                    'work_schedule' => $_POST['work_schedule'] ?? null,
                    'bank_account' => $_POST['bank_account'] ?? null,
                    'bank_name' => $_POST['bank_name'] ?? null
                ];
                
                if ($this->payrollModel->updateEmployee($employeeId, $data)) {
                    header('Location: ?view=payroll&action=employees&success=1');
                    exit;
                } else {
                    throw new Exception('Error updating employee');
                }
                
            } catch (Exception $e) {
                $employee = $this->payrollModel->getEmployeeById($employeeId);
                $this->loadPartialView('payroll/editEmployee', [
                    'error' => $e->getMessage(),
                    'employee' => $employee
                ]);
            }
        } else {
            $employee = $this->payrollModel->getEmployeeById($employeeId);
            if (!$employee) {
                header('Location: ?view=payroll&action=employees');
                exit;
            }
            
            $this->loadPartialView('payroll/editEmployee', [
                'employee' => $employee,
                'page_title' => 'Edit Employee'
            ]);
        }
    }
    
    // =============================================
    // PAYROLL PERIOD METHODS
    // =============================================
    
    /**
     * Payroll periods list
     */
    public function periods() {
<<<<<<< HEAD
        if (!$this->securityMiddleware->hasRole(['root', 'director', 'treasurer'])) {
            header('Location: index.php?controller=error&action=unauthorized');
=======
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
>>>>>>> b580a54ac9ce32e1ee86e4d2f3a0fb94b2f6af3d
            exit;
        }
        
        try {
            $filters = [];
            if (isset($_GET['status'])) $filters['status'] = $_GET['status'];
            if (isset($_GET['year'])) $filters['year'] = $_GET['year'];
            
            $periods = $this->payrollModel->getAllPeriods($filters);
            
            $data = [
                'periods' => $periods,
                'filters' => $filters,
                'page_title' => 'Payroll Periods'
            ];
            
            $this->loadPartialView('payroll/periods', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Create new period
     */
    public function createPeriod() {
<<<<<<< HEAD
        if (!$this->securityMiddleware->hasRole(['root', 'director'])) {
            header('Location: index.php?controller=error&action=unauthorized');
=======
        if (!$this->sessionManager->hasRole(['root', 'director'])) {
            header('Location: ?view=unauthorized');
>>>>>>> b580a54ac9ce32e1ee86e4d2f3a0fb94b2f6af3d
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'period_name' => $_POST['period_name'],
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'payment_date' => $_POST['payment_date'],
                    'created_by_user_id' => $this->sessionManager->getUserId()
                ];
                
                if ($this->payrollModel->createPeriod($data)) {
                    header('Location: ?view=payroll&action=periods&success=1');
                    exit;
                } else {
                    throw new Exception('Error creating period');
                }
                
            } catch (Exception $e) {
                $this->loadPartialView('payroll/createPeriod', [
                    'error' => $e->getMessage(),
                    'data' => $_POST
                ]);
            }
        } else {
<<<<<<< HEAD
            $this->loadView('payroll/createPeriod', ['page_title' => 'Create Period']);
=======
            $this->loadPartialView('payroll/createPeriod', ['page_title' => 'Crear Período']);
>>>>>>> 354953949cbaabb18f70f97b3e178d662b4ac9fb
        }
    }
    
    /**
     * View period and its records
     */
    public function viewPeriod() {
<<<<<<< HEAD
        if (!$this->securityMiddleware->hasRole(['root', 'director', 'treasurer'])) {
            header('Location: index.php?controller=error&action=unauthorized');
=======
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
>>>>>>> b580a54ac9ce32e1ee86e4d2f3a0fb94b2f6af3d
            exit;
        }
        
        $periodId = $_GET['id'] ?? null;
        if (!$periodId) {
            header('Location: ?view=payroll&action=periods');
            exit;
        }
        
        try {
            $period = $this->payrollModel->getPeriodById($periodId);
            $records = $this->payrollModel->getPayrollRecordsByPeriod($periodId);
            $statistics = $this->payrollModel->getPayrollStatistics($periodId);
            $byDepartment = $this->payrollModel->getPayrollByDepartment($periodId);
            
            $data = [
                'period' => $period,
                'records' => $records,
                'statistics' => $statistics,
                'by_department' => $byDepartment,
                'page_title' => 'Period: ' . $period['period_name']
            ];
            
            $this->loadPartialView('payroll/viewPeriod', $data);
            
        } catch (Exception $e) {
            $this->loadView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Generate payroll for a period
     */
    public function generatePayroll() {
<<<<<<< HEAD
        if (!$this->securityMiddleware->hasRole(['root', 'director'])) {
            header('Location: index.php?controller=error&action=unauthorized');
=======
        if (!$this->sessionManager->hasRole(['root', 'director'])) {
            header('Location: ?view=unauthorized');
>>>>>>> b580a54ac9ce32e1ee86e4d2f3a0fb94b2f6af3d
            exit;
        }
        
        $periodId = $_GET['period_id'] ?? null;
        if (!$periodId) {
            header('Location: ?view=payroll&action=periods');
            exit;
        }
        
        try {
            if ($this->payrollModel->generatePayrollForPeriod($periodId, $this->sessionManager->getUserId())) {
                header('Location: ?view=payroll&action=viewPeriod&id=' . $periodId . '&success=1');
                exit;
            } else {
                throw new Exception('Error generating payroll');
            }
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    // =============================================
    // MÉTODOS PARA REGISTROS DE NÓMINA
    // =============================================
    
    /**
     * Ver registro de nómina específico
     */
    public function viewPayrollRecord() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        $recordId = $_GET['id'] ?? null;
        if (!$recordId) {
            header('Location: ?view=payroll&action=periods');
            exit;
        }
        
        try {
            $record = $this->payrollModel->getPayrollRecord($recordId);
            $conceptDetails = $this->payrollModel->getConceptDetails($recordId);
            
            $data = [
                'record' => $record,
                'concept_details' => $conceptDetails,
                'page_title' => 'Payroll Record'
            ];
            
            $this->loadPartialView('payroll/viewPayrollRecord', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Edit payroll record
     */
    public function editPayrollRecord() {
<<<<<<< HEAD
        if (!$this->securityMiddleware->hasRole(['root', 'director'])) {
            header('Location: index.php?controller=error&action=unauthorized');
=======
        if (!$this->sessionManager->hasRole(['root', 'director', 'treasurer'])) {
            header('Location: ?view=unauthorized');
>>>>>>> b580a54ac9ce32e1ee86e4d2f3a0fb94b2f6af3d
            exit;
        }
        
        $recordId = $_GET['id'] ?? null;
        if (!$recordId) {
            header('Location: ?view=payroll&action=periods');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'base_salary' => $_POST['base_salary'],
                    'total_earnings' => $_POST['total_earnings'],
                    'total_deductions' => $_POST['total_deductions'],
                    'net_salary' => $_POST['net_salary'],
                    'notes' => $_POST['notes'] ?? null
                ];
                
                if ($this->payrollModel->updatePayrollRecord($recordId, $data)) {
                    header('Location: ?view=payroll&action=viewPayrollRecord&id=' . $recordId . '&success=1');
                    exit;
                } else {
                    throw new Exception('Error updating record');
                }
                
            } catch (Exception $e) {
                $record = $this->payrollModel->getPayrollRecord($recordId);
                $this->loadPartialView('payroll/editPayrollRecord', [
                    'error' => $e->getMessage(),
                    'record' => $record
                ]);
            }
        } else {
            $record = $this->payrollModel->getPayrollRecord($recordId);
            if (!$record) {
                header('Location: ?view=payroll&action=periods');
                exit;
            }
            
            $this->loadPartialView('payroll/editPayrollRecord', [
                'record' => $record,
                'page_title' => 'Edit Payroll Record'
            ]);
        }
    }
    
    // =============================================
    // MÉTODOS PARA AUSENCIAS
    // =============================================
    
    /**
     * Lista de ausencias
     */
    public function absences() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        try {
            $filters = [];
            if (isset($_GET['employee_id'])) $filters['employee_id'] = $_GET['employee_id'];
            if (isset($_GET['period_id'])) $filters['period_id'] = $_GET['period_id'];
            
            $absences = $this->payrollModel->getAllAbsences($filters);
            
            $data = [
                'absences' => $absences,
                'filters' => $filters,
                'page_title' => 'Gestión de Ausencias'
            ];
            
            $this->loadPartialView('payroll/absences', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Crear nueva ausencia
     */
    public function createAbsence() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'employee_id' => $_POST['employee_id'],
                    'absence_date' => $_POST['absence_date'],
                    'absence_type' => $_POST['absence_type'],
                    'hours_missed' => $_POST['hours_missed'],
                    'justified' => isset($_POST['justified']) ? 1 : 0,
                    'notes' => $_POST['notes'] ?? null
                ];
                
                if ($this->payrollModel->createAbsence($data)) {
                    header('Location: ?view=payroll&action=absences&success=1');
                    exit;
                } else {
                    throw new Exception('Error al crear ausencia');
                }
                
            } catch (Exception $e) {
                $this->loadPartialView('payroll/createAbsence', [
                    'error' => $e->getMessage(),
                    'data' => $_POST
                ]);
            }
        } else {
            $this->loadPartialView('payroll/createAbsence', ['page_title' => 'Crear Ausencia']);
        }
    }
    
    // =============================================
    // MÉTODOS PARA HORAS EXTRA
    // =============================================
    
    /**
     * Lista de horas extra
     */
    public function overtime() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        try {
            $filters = [];
            if (isset($_GET['employee_id'])) $filters['employee_id'] = $_GET['employee_id'];
            if (isset($_GET['period_id'])) $filters['period_id'] = $_GET['period_id'];
            
            $overtime = $this->payrollModel->getAllOvertime($filters);
            
            $data = [
                'overtime' => $overtime,
                'filters' => $filters,
                'page_title' => 'Gestión de Horas Extra'
            ];
            
            $this->loadPartialView('payroll/overtime', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Crear nueva hora extra
     */
    public function createOvertime() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'employee_id' => $_POST['employee_id'],
                    'overtime_date' => $_POST['overtime_date'],
                    'hours_worked' => $_POST['hours_worked'],
                    'hourly_rate' => $_POST['hourly_rate'],
                    'total_amount' => $_POST['total_amount'],
                    'notes' => $_POST['notes'] ?? null
                ];
                
                if ($this->payrollModel->createOvertime($data)) {
                    header('Location: ?view=payroll&action=overtime&success=1');
                    exit;
                } else {
                    throw new Exception('Error al crear hora extra');
                }
                
            } catch (Exception $e) {
                $this->loadPartialView('payroll/createOvertime', [
                    'error' => $e->getMessage(),
                    'data' => $_POST
                ]);
            }
        } else {
            $this->loadPartialView('payroll/createOvertime', ['page_title' => 'Crear Hora Extra']);
        }
    }
    
    // =============================================
    // MÉTODOS PARA BONIFICACIONES
    // =============================================
    
    /**
     * Lista de bonificaciones
     */
    public function bonuses() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        try {
            $filters = [];
            if (isset($_GET['employee_id'])) $filters['employee_id'] = $_GET['employee_id'];
            if (isset($_GET['period_id'])) $filters['period_id'] = $_GET['period_id'];
            
            $bonuses = $this->payrollModel->getAllBonuses($filters);
            
            $data = [
                'bonuses' => $bonuses,
                'filters' => $filters,
                'page_title' => 'Gestión de Bonificaciones'
            ];
            
            $this->loadPartialView('payroll/bonuses', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Crear nueva bonificación
     */
    public function createBonus() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'employee_id' => $_POST['employee_id'],
                    'bonus_type' => $_POST['bonus_type'],
                    'amount' => $_POST['amount'],
                    'bonus_date' => $_POST['bonus_date'],
                    'notes' => $_POST['notes'] ?? null
                ];
                
                if ($this->payrollModel->createBonus($data)) {
                    header('Location: ?view=payroll&action=bonuses&success=1');
                    exit;
                } else {
                    throw new Exception('Error al crear bonificación');
                }
                
            } catch (Exception $e) {
                $this->loadPartialView('payroll/createBonus', [
                    'error' => $e->getMessage(),
                    'data' => $_POST
                ]);
            }
        } else {
            $this->loadPartialView('payroll/createBonus', ['page_title' => 'Crear Bonificación']);
        }
    }
    
    // =============================================
    // MÉTODOS PARA REPORTES
    // =============================================
    
    /**
     * Reportes de nómina
     */
    public function reports() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        try {
            $periodId = $_GET['period_id'] ?? null;
            $reportType = $_GET['type'] ?? 'summary';
            
            $data = [
                'period_id' => $periodId,
                'report_type' => $reportType,
                'page_title' => 'Reportes de Nómina'
            ];
            
            // Cargar datos según el tipo de reporte
            if ($periodId) {
                switch ($reportType) {
                    case 'summary':
                        $data['statistics'] = $this->payrollModel->getPayrollStatistics($periodId);
                        break;
                    case 'by_department':
                        $data['by_department'] = $this->payrollModel->getPayrollByDepartment($periodId);
                        break;
                    case 'detailed':
                        $data['records'] = $this->payrollModel->getPayrollRecordsByPeriod($periodId);
                        break;
                }
            }
            
            $this->loadPartialView('payroll/reports', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    // =============================================
    // MÉTODOS AUXILIARES
    // =============================================
    
    /**
     * Cargar vista
     */
    protected function loadView($view, $data = []) {
        // Extraer variables del array de datos
        extract($data);
        
        // Incluir la vista
        $viewPath = ROOT . '/app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception('Vista no encontrada: ' . $view);
        }
    }
}
?> 