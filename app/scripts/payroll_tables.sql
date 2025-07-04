-- =============================================
-- SISTEMA DE NÓMINA - TABLAS
-- =============================================

-- Tabla de empleados (extiende la tabla users existente)
CREATE TABLE employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    employee_code VARCHAR(20) UNIQUE NOT NULL,
    position VARCHAR(100) NOT NULL COMMENT 'Cargo del empleado',
    department VARCHAR(100) NOT NULL COMMENT 'Departamento',
    hire_date DATE NOT NULL,
    salary DECIMAL(12,2) NOT NULL COMMENT 'Salario base mensual',
    contract_type ENUM('full_time', 'part_time', 'temporary', 'contractor') NOT NULL DEFAULT 'full_time',
    work_schedule VARCHAR(100) COMMENT 'Horario de trabajo',
    bank_account VARCHAR(50) COMMENT 'Cuenta bancaria para pagos',
    bank_name VARCHAR(100) COMMENT 'Nombre del banco',
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_employee_code (employee_code),
    INDEX idx_position (position),
    INDEX idx_department (department)
) ENGINE=InnoDB;

-- Tabla de conceptos de nómina
CREATE TABLE payroll_concepts (
    concept_id INT AUTO_INCREMENT PRIMARY KEY,
    concept_name VARCHAR(100) NOT NULL,
    concept_type ENUM('income', 'deduction') NOT NULL COMMENT 'Ingreso o deducción',
    concept_category VARCHAR(50) NOT NULL COMMENT 'Categoría: salario, bonos, descuentos, etc.',
    is_percentage BIT NOT NULL DEFAULT 0 COMMENT 'Si es porcentaje del salario base',
    default_value DECIMAL(10,2) DEFAULT 0 COMMENT 'Valor por defecto',
    is_mandatory BIT NOT NULL DEFAULT 0 COMMENT 'Si es obligatorio para todos los empleados',
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_concept_type (concept_type),
    INDEX idx_concept_category (concept_category)
) ENGINE=InnoDB;

-- Tabla de períodos de nómina
CREATE TABLE payroll_periods (
    period_id INT AUTO_INCREMENT PRIMARY KEY,
    period_name VARCHAR(50) NOT NULL COMMENT 'Ej: Enero 2024, Febrero 2024',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    payment_date DATE NOT NULL COMMENT 'Fecha de pago',
    status ENUM('open', 'processing', 'closed', 'paid') NOT NULL DEFAULT 'open',
    total_employees INT DEFAULT 0,
    total_payroll DECIMAL(15,2) DEFAULT 0,
    created_by_user_id INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL,
    
    FOREIGN KEY (created_by_user_id) REFERENCES users(user_id),
    INDEX idx_period_dates (start_date, end_date),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Tabla de nómina por empleado y período
CREATE TABLE payroll_records (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    period_id INT NOT NULL,
    employee_id INT NOT NULL,
    base_salary DECIMAL(12,2) NOT NULL COMMENT 'Salario base del período',
    total_income DECIMAL(12,2) NOT NULL DEFAULT 0 COMMENT 'Total ingresos',
    total_deductions DECIMAL(12,2) NOT NULL DEFAULT 0 COMMENT 'Total deducciones',
    net_salary DECIMAL(12,2) NOT NULL DEFAULT 0 COMMENT 'Salario neto',
    working_days INT NOT NULL DEFAULT 30 COMMENT 'Días trabajados',
    status ENUM('pending', 'approved', 'paid') NOT NULL DEFAULT 'pending',
    notes TEXT,
    created_by_user_id INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (period_id) REFERENCES payroll_periods(period_id) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by_user_id) REFERENCES users(user_id),
    UNIQUE KEY idx_period_employee (period_id, employee_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Tabla de detalles de conceptos por registro de nómina
CREATE TABLE payroll_concept_details (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    concept_id INT NOT NULL,
    concept_value DECIMAL(10,2) NOT NULL COMMENT 'Valor del concepto',
    concept_percentage DECIMAL(5,2) DEFAULT 0 COMMENT 'Porcentaje aplicado',
    description TEXT COMMENT 'Descripción adicional',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (record_id) REFERENCES payroll_records(record_id) ON DELETE CASCADE,
    FOREIGN KEY (concept_id) REFERENCES payroll_concepts(concept_id),
    INDEX idx_record_concept (record_id, concept_id)
) ENGINE=InnoDB;

-- Tabla de ausencias y licencias
CREATE TABLE employee_absences (
    absence_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    absence_type ENUM('sick_leave', 'vacation', 'personal_leave', 'maternity_leave', 'other') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    days_count INT NOT NULL COMMENT 'Número de días',
    is_paid BIT NOT NULL DEFAULT 0 COMMENT 'Si es pagada',
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    approved_by_user_id INT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by_user_id) REFERENCES users(user_id),
    INDEX idx_employee_dates (employee_id, start_date, end_date),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Tabla de horas extras
CREATE TABLE employee_overtime (
    overtime_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    period_id INT NOT NULL,
    date_worked DATE NOT NULL,
    hours_worked DECIMAL(4,2) NOT NULL COMMENT 'Horas trabajadas',
    hourly_rate DECIMAL(8,2) NOT NULL COMMENT 'Tarifa por hora',
    total_amount DECIMAL(10,2) NOT NULL COMMENT 'Total a pagar',
    description TEXT,
    status ENUM('pending', 'approved', 'paid') NOT NULL DEFAULT 'pending',
    approved_by_user_id INT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (period_id) REFERENCES payroll_periods(period_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by_user_id) REFERENCES users(user_id),
    INDEX idx_employee_period (employee_id, period_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Tabla de bonificaciones especiales
CREATE TABLE employee_bonuses (
    bonus_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    period_id INT NOT NULL,
    bonus_type VARCHAR(50) NOT NULL COMMENT 'Tipo de bonificación',
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    status ENUM('pending', 'approved', 'paid') NOT NULL DEFAULT 'pending',
    approved_by_user_id INT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (period_id) REFERENCES payroll_periods(period_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by_user_id) REFERENCES users(user_id),
    INDEX idx_employee_period (employee_id, period_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- =============================================
-- DATOS INICIALES PARA NÓMINA
-- =============================================

-- Insertar conceptos básicos de nómina
INSERT INTO payroll_concepts (concept_name, concept_type, concept_category, is_percentage, default_value, is_mandatory) VALUES
-- Ingresos
('Salario Base', 'income', 'salary', 0, 0, 1),
('Horas Extras', 'income', 'overtime', 0, 0, 0),
('Bonificación', 'income', 'bonus', 0, 0, 0),
('Prima de Servicios', 'income', 'bonus', 0, 0, 0),
('Cesantías', 'income', 'bonus', 0, 0, 0),
('Intereses sobre Cesantías', 'income', 'bonus', 0, 0, 0),

-- Deducciones
('Salud', 'deduction', 'social_security', 1, 4.0, 1),
('Pensión', 'deduction', 'social_security', 1, 4.0, 1),
('Fondo de Solidaridad Pensional', 'deduction', 'social_security', 1, 1.0, 1),
('Retención en la Fuente', 'deduction', 'tax', 0, 0, 0),
('Préstamos', 'deduction', 'loan', 0, 0, 0),
('Otros Descuentos', 'deduction', 'other', 0, 0, 0);

-- =============================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =============================================

-- Índices para consultas frecuentes de nómina
CREATE INDEX idx_payroll_period_status ON payroll_periods(status, start_date);
CREATE INDEX idx_payroll_records_status ON payroll_records(status, period_id);
CREATE INDEX idx_employee_active ON employees(is_active, department);
CREATE INDEX idx_absences_employee_status ON employee_absences(employee_id, status, start_date);
CREATE INDEX idx_overtime_employee_period ON employee_overtime(employee_id, period_id, status); 