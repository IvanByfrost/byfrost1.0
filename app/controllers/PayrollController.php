<?php
require_once ROOT . '/app/models/payrollModel.php';
require_once ROOT . '/app/library/SessionManager.php';
require_once ROOT . '/app/library/SecurityMiddleware.php';
require_once ROOT . '/app/controllers/MainController.php';

class PayrollController extends MainController {
    protected $payrollModel;
    protected $sessionManager;
    protected $securityMiddleware;
    
    public function __construct($dbConn = null, $view = null) {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->payrollModel = new PayrollModel();
        $this->sessionManager = new SessionManager();
        $this->securityMiddleware = new SecurityMiddleware();
    }
    
    // =============================================
    // MÉTODOS PRINCIPALES DE NÓMINA
    // =============================================
    
    /**
     * Dashboard principal de nómina
     */
    public function dashboard() {
        // Verificar permisos
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        try {
            // Obtener estadísticas generales
            $employees = $this->payrollModel->getAllEmployees();
            $currentPeriods = $this->payrollModel->getAllPeriods(['status' => 'open']);
            $closedPeriods = $this->payrollModel->getAllPeriods(['status' => 'closed']);
            $absences = $this->payrollModel->getAllAbsences();
            
            // Calcular estadísticas
            $total_employees = count($employees);
            $active_periods = count($currentPeriods);
            $total_absences = count($absences);
            
            // Calcular nómina del mes (ejemplo)
            $monthly_payroll = 0;
            if (!empty($employees)) {
                foreach ($employees as $employee) {
                    $monthly_payroll += $employee['salary'] ?? 0;
                }
            }
            
            $data = [
                'total_employees' => $total_employees,
                'monthly_payroll' => $monthly_payroll,
                'active_periods' => $active_periods,
                'total_absences' => $total_absences,
                'current_period' => $currentPeriods[0] ?? null,
                'recent_periods' => array_slice($closedPeriods, 0, 5),
                'page_title' => 'Dashboard de Nómina'
            ];
            
            $this->loadPartialView('payroll/dashboard', $data);
            
        } catch (Exception $e) {
            // Si hay error, mostrar dashboard con valores por defecto
            $data = [
                'total_employees' => 0,
                'monthly_payroll' => 0,
                'active_periods' => 0,
                'total_absences' => 0,
                'current_period' => null,
                'recent_periods' => [],
                'page_title' => 'Dashboard de Nómina',
                'error' => $e->getMessage()
            ];
            
            $this->loadPartialView('payroll/dashboard', $data);
        }
    }
    
    /**
     * Lista de empleados
     */
    public function employees() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        try {
            $filters = [];
            if (isset(_GET['department']) && htmlspecialchars(_GET['department'])) $filters['department'] = htmlspecialchars($_GET['department']);
            if (isset(_GET['position']) && htmlspecialchars(_GET['position'])) $filters['position'] = htmlspecialchars($_GET['position']);
            
            $employees = $this->payrollModel->getAllEmployees($filters);
            
            $data = [
                'employees' => $employees,
                'filters' => $filters,
                'sessionManager' => $this->sessionManager,
                'page_title' => 'Gestión de Empleados'
            ];
            
            $this->loadPartialView('payroll/employees', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Crear nuevo empleado
     */
    public function createEmployee() {
        if (!$this->sessionManager->hasRole(['root', 'director'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'user_id' => htmlspecialchars($_POST['user_id']),
                    'employee_code' => htmlspecialchars($_POST['employee_code']),
                    'position' => htmlspecialchars($_POST['position']),
                    'department' => htmlspecialchars($_POST['department']),
                    'hire_date' => htmlspecialchars($_POST['hire_date']),
                    'salary' => htmlspecialchars($_POST['salary']),
                    'contract_type' => htmlspecialchars($_POST['contract_type']),
                    'work_schedule' => htmlspecialchars($_POST['work_schedule']) ?? null,
                    'bank_account' => htmlspecialchars($_POST['bank_account']) ?? null,
                    'bank_name' => htmlspecialchars($_POST['bank_name']) ?? null
                ];
                
                if ($this->payrollModel->createEmployee($data)) {
                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                        echo "<script>window.loadView('payroll/employees');</script>";
                        exit;
                    } else {
                        header('Location: ?view=payroll');
                        exit;
                    }
                } else {
                    throw new Exception('Error al crear empleado');
                }
                
            } catch (Exception $e) {
                $this->loadPartialView('payroll/createEmployee', [
                    'error' => $e->getMessage(),
                    'data' => $_POST
                ]);
            }
        } else {
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
        }
    }
    
    /**
     * Editar empleado
     */
    public function editEmployee() {
        if (!$this->sessionManager->hasRole(['root', 'director'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        $employeeId = htmlspecialchars($_GET['id']) ?? null;
        if (!$employeeId) {
            header('Location: ?view=payroll&action=employees');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'position' => htmlspecialchars($_POST['position']),
                    'department' => htmlspecialchars($_POST['department']),
                    'salary' => htmlspecialchars($_POST['salary']),
                    'contract_type' => htmlspecialchars($_POST['contract_type']),
                    'work_schedule' => htmlspecialchars($_POST['work_schedule']) ?? null,
                    'bank_account' => htmlspecialchars($_POST['bank_account']) ?? null,
                    'bank_name' => htmlspecialchars($_POST['bank_name']) ?? null
                ];
                
                if ($this->payrollModel->updateEmployee($employeeId, $data)) {
                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                        echo "<script>window.loadView('payroll/employees');</script>";
                        exit;
                    } else {
                        header('Location: ?view=payroll');
                        exit;
                    }
                } else {
                    throw new Exception('Error al actualizar empleado');
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
                'page_title' => 'Editar Empleado'
            ]);
        }
    }
    
    /**
     * Ver detalles de empleado
     */
    public function viewEmployee() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        $employeeId = htmlspecialchars($_GET['id']) ?? null;
        if (!$employeeId) {
            header('Location: ?view=payroll&action=employees');
            exit;
        }
        
        try {
            // Paso 1: Obtener empleado
            $employee = $this->payrollModel->getEmployeeById($employeeId);
            if (!$employee) {
                throw new Exception('Empleado no encontrado con ID: ' . $employeeId);
            }
            
            // Paso 2: Obtener historial de nómina del empleado
            try {
                $payrollHistory = $this->payrollModel->getEmployeePayrollHistory($employeeId);
            } catch (Exception $e) {
                // Si falla el historial, usar array vacío
                $payrollHistory = [];
                error_log('Error obteniendo historial de nómina: ' . $e->getMessage());
            }
            
            $data = [
                'employee' => $employee,
                'payrollHistory' => $payrollHistory,
                'sessionManager' => $this->sessionManager,
                'page_title' => 'Detalles del Empleado: ' . $employee['first_name'] . ' ' . $employee['last_name']
            ];
            
            // Paso 3: Cargar la vista
            $this->loadPartialView('payroll/viewEmployee', $data);
            
        } catch (Exception $e) {
            error_log('Error en viewEmployee: ' . $e->getMessage());
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    // =============================================
    // MÉTODOS PARA PERÍODOS DE NÓMINA
    // =============================================
    
    /**
     * Lista de períodos de nómina
     */
    public function periods() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        try {
            $filters = [];
            if (isset(_GET['status']) && htmlspecialchars(_GET['status'])) $filters['status'] = htmlspecialchars($_GET['status']);
            if (isset(_GET['year']) && htmlspecialchars(_GET['year'])) $filters['year'] = htmlspecialchars($_GET['year']);
            
            $periods = $this->payrollModel->getAllPeriods($filters);
            
            $data = [
                'periods' => $periods,
                'filters' => $filters,
                'page_title' => 'Períodos de Nómina'
            ];
            
            $this->loadPartialView('payroll/periods', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Crear nuevo período
     */
    public function createPeriod() {
        if (!$this->sessionManager->hasRole(['root', 'director'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'period_name' => htmlspecialchars($_POST['period_name']),
                    'start_date' => htmlspecialchars($_POST['start_date']),
                    'end_date' => htmlspecialchars($_POST['end_date']),
                    'payment_date' => htmlspecialchars($_POST['payment_date']),
                    'created_by_user_id' => $this->sessionManager->getUserId()
                ];
                
                if ($this->payrollModel->createPeriod($data)) {
                    header('Location: ?view=payroll&action=periods&success=1');
                    exit;
                } else {
                    throw new Exception('Error al crear período');
                }
                
            } catch (Exception $e) {
                $this->loadPartialView('payroll/createPeriod', [
                    'error' => $e->getMessage(),
                    'data' => $_POST
                ]);
            }
        } else {
            $this->loadPartialView('payroll/createPeriod', ['page_title' => 'Crear Período']);
        }
    }
    
    /**
     * Ver período y sus registros
     */
    public function viewPeriod() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        $periodId = htmlspecialchars($_GET['id']) ?? null;
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
                'page_title' => 'Período: ' . $period['period_name']
            ];
            
            $this->loadPartialView('payroll/viewPeriod', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Generar nómina para un período
     */
    public function generatePayroll() {
        if (!$this->sessionManager->hasRole(['root', 'director'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        $periodId = htmlspecialchars($_GET['period_id']) ?? null;
        if (!$periodId) {
            header('Location: ?view=payroll&action=periods');
            exit;
        }
        
        try {
            if ($this->payrollModel->generatePayrollForPeriod($periodId, $this->sessionManager->getUserId())) {
                header('Location: ?view=payroll&action=viewPeriod&id=' . $periodId . '&success=1');
                exit;
            } else {
                throw new Exception('Error al generar nómina');
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
        
        $recordId = htmlspecialchars($_GET['id']) ?? null;
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
                'page_title' => 'Registro de Nómina'
            ];
            
            $this->loadPartialView('payroll/viewPayrollRecord', $data);
            
        } catch (Exception $e) {
            $this->loadPartialView('Error/error', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Editar registro de nómina
     */
    public function editPayrollRecord() {
        if (!$this->sessionManager->hasRole(['root', 'director', 'treasurer'])) {
            header('Location: ?view=unauthorized');
            exit;
        }
        
        $recordId = htmlspecialchars($_GET['id']) ?? null;
        if (!$recordId) {
            header('Location: ?view=payroll&action=periods');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'base_salary' => htmlspecialchars($_POST['base_salary']),
                    'total_earnings' => htmlspecialchars($_POST['total_earnings']),
                    'total_deductions' => htmlspecialchars($_POST['total_deductions']),
                    'net_salary' => htmlspecialchars($_POST['net_salary']),
                    'notes' => htmlspecialchars($_POST['notes']) ?? null
                ];
                
                if ($this->payrollModel->updatePayrollRecord($recordId, $data)) {
                    header('Location: ?view=payroll&action=viewPayrollRecord&id=' . $recordId . '&success=1');
                    exit;
                } else {
                    throw new Exception('Error al actualizar registro');
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
                'page_title' => 'Editar Registro de Nómina'
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
            if (isset(_GET['employee_id']) && htmlspecialchars(_GET['employee_id'])) $filters['employee_id'] = htmlspecialchars($_GET['employee_id']);
            if (isset(_GET['period_id']) && htmlspecialchars(_GET['period_id'])) $filters['period_id'] = htmlspecialchars($_GET['period_id']);
            
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
                    'employee_id' => htmlspecialchars($_POST['employee_id']),
                    'absence_type' => htmlspecialchars($_POST['absence_type']),
                    'start_date' => htmlspecialchars($_POST['start_date']),
                    'end_date' => htmlspecialchars($_POST['end_date']),
                    'days_count' => htmlspecialchars($_POST['days_count']),
                    'is_paid' => isset(_POST['is_paid']) && htmlspecialchars(_POST['is_paid']) ? 1 : 0,
                    'reason' => htmlspecialchars($_POST['reason']) ?? null
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
            try {
                $employees = $this->payrollModel->getAllEmployees();
                $this->loadPartialView('payroll/createAbsence', [
                    'page_title' => 'Crear Ausencia',
                    'employees' => $employees
                ]);
            } catch (Exception $e) {
                $this->loadPartialView('payroll/createAbsence', [
                    'page_title' => 'Crear Ausencia',
                    'error' => $e->getMessage()
                ]);
            }
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
            if (isset(_GET['employee_id']) && htmlspecialchars(_GET['employee_id'])) $filters['employee_id'] = htmlspecialchars($_GET['employee_id']);
            if (isset(_GET['period_id']) && htmlspecialchars(_GET['period_id'])) $filters['period_id'] = htmlspecialchars($_GET['period_id']);
            
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
                    'employee_id' => htmlspecialchars($_POST['employee_id']),
                    'period_id' => htmlspecialchars($_POST['period_id']),
                    'date_worked' => htmlspecialchars($_POST['date_worked']),
                    'hours_worked' => htmlspecialchars($_POST['hours_worked']),
                    'hourly_rate' => htmlspecialchars($_POST['hourly_rate']),
                    'total_amount' => htmlspecialchars($_POST['total_amount']),
                    'description' => htmlspecialchars($_POST['description']) ?? null
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
            try {
                $employees = $this->payrollModel->getAllEmployees();
                $periods = $this->payrollModel->getAllPeriods(['status' => 'open']);
                $this->loadPartialView('payroll/createOvertime', [
                    'page_title' => 'Crear Hora Extra',
                    'employees' => $employees,
                    'periods' => $periods
                ]);
            } catch (Exception $e) {
                $this->loadPartialView('payroll/createOvertime', [
                    'page_title' => 'Crear Hora Extra',
                    'error' => $e->getMessage()
                ]);
            }
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
            if (isset(_GET['employee_id']) && htmlspecialchars(_GET['employee_id'])) $filters['employee_id'] = htmlspecialchars($_GET['employee_id']);
            if (isset(_GET['period_id']) && htmlspecialchars(_GET['period_id'])) $filters['period_id'] = htmlspecialchars($_GET['period_id']);
            
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
                    'employee_id' => htmlspecialchars($_POST['employee_id']),
                    'period_id' => htmlspecialchars($_POST['period_id']),
                    'bonus_type' => htmlspecialchars($_POST['bonus_type']),
                    'amount' => htmlspecialchars($_POST['amount']),
                    'description' => htmlspecialchars($_POST['description']) ?? null
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
            try {
                $employees = $this->payrollModel->getAllEmployees();
                $periods = $this->payrollModel->getAllPeriods(['status' => 'open']);
                $this->loadPartialView('payroll/createBonus', [
                    'page_title' => 'Crear Bonificación',
                    'employees' => $employees,
                    'periods' => $periods
                ]);
            } catch (Exception $e) {
                $this->loadPartialView('payroll/createBonus', [
                    'page_title' => 'Crear Bonificación',
                    'error' => $e->getMessage()
                ]);
            }
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
            $periodId = htmlspecialchars($_GET['period_id']) ?? null;
            $reportType = htmlspecialchars($_GET['type']) ?? 'summary';
            
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
        // Usar el método loadPartialView del MainController
        $this->loadPartialView($view, $data);
    }

    /**
     * Carga una vista parcial vía AJAX para el módulo payroll
     */
    public function loadPartial()
    {
        $view = htmlspecialchars($_POST['view']) ?? htmlspecialchars($_GET['view']) ?? '';
        $action = htmlspecialchars($_POST['action']) ?? htmlspecialchars($_GET['action']) ?? 'index';
        $partialView = htmlspecialchars($_POST['partialView']) ?? htmlspecialchars($_GET['partialView']) ?? '';
        $force = isset(_POST['force']) && htmlspecialchars(_POST['force']) || isset(_GET['force']) && htmlspecialchars(_GET['force']);

        if (!$this->isAjaxRequest() && !$force) {
            if (empty($partialView)) {
                echo '<div class="alert alert-warning">Vista no especificada. Use: ?view=payroll&action=loadPartial&partialView=vista</div>';
                return;
            }
            $viewPath = 'payroll/' . $partialView;
            $fullPath = ROOT . "/app/views/{$viewPath}.php";
            if (!file_exists($fullPath)) {
                echo '<div class="alert alert-danger">Vista no encontrada: ' . htmlspecialchars($viewPath) . '</div>';
                return;
            }
            try {
                $this->loadPartialView($viewPath);
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error al cargar la vista: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            return;
        }
        if (empty($partialView)) {
            echo json_encode(['success' => false, 'message' => 'Vista no especificada']);
            return;
        }
        $viewPath = 'payroll/' . $partialView;
        $fullPath = ROOT . "/app/views/{$viewPath}.php";
        if (!file_exists($fullPath)) {
            echo json_encode(['success' => false, 'message' => "Vista no encontrada: {$viewPath}"]);
            return;
        }
        try {
            $this->loadPartialView($viewPath);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al cargar la vista: ' . $e->getMessage()]);
        }
    }
}
?> 