-- =============================================
-- BYFROST - SISTEMA UNIFICADO DE BASE DE DATOS
-- =============================================
-- Versión: 2.0
-- Fecha: 2025-01-27
-- Descripción: Sistema unificado y consistente para ByFrost
-- =============================================

-- Crear base de datos
-- CREATE DATABASE IF NOT EXISTS byfrost_db 
-- CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE byfrost_db;

-- =============================================
-- TABLAS CORE DE USUARIOS Y AUTENTICACIÓN
-- =============================================

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    credential_number VARCHAR(40) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(60) NOT NULL,
    credential_type VARCHAR(2) NOT NULL COMMENT 'CC, TI, etc.',
    date_of_birth DATE NOT NULL,
    address VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    school_id INT NULL,
    password_hash VARBINARY(255) NOT NULL,
    salt_password VARBINARY(255) NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_credential (credential_number),
    INDEX idx_email (email),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_roles (
    user_role_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_type ENUM('student', 'parent', 'professor', 'coordinator', 'director', 'treasurer', 'root') NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY idx_user_role (user_id, role_type),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_role_type (role_type),
    INDEX idx_active_role (is_active, role_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS role_permissions (
    role_type ENUM('student', 'parent', 'professor', 'coordinator', 'director', 'treasurer', 'root') PRIMARY KEY,
    can_create BOOLEAN DEFAULT FALSE,
    can_read BOOLEAN DEFAULT TRUE,
    can_update BOOLEAN DEFAULT FALSE,
    can_delete BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS password_resets (
    reset_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used BIT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_token (token),
    INDEX idx_user_id (user_id),
    INDEX idx_expires (expires_at),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- ESTRUCTURA ACADÉMICA
-- =============================================

CREATE TABLE IF NOT EXISTS schools (
    school_id INT AUTO_INCREMENT PRIMARY KEY,
    school_name VARCHAR(100) NOT NULL COMMENT 'Incluye sede: Colegio ABC - Sede Norte',
    school_dane VARCHAR(10) NOT NULL,
    school_document VARCHAR(10) NOT NULL,
    total_quota SMALLINT NOT NULL,
    director_user_id INT NOT NULL,
    coordinator_user_id INT NULL,
    address VARCHAR(200),
    phone VARCHAR(20),
    email VARCHAR(100),
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (director_user_id) REFERENCES users(user_id),
    FOREIGN KEY (coordinator_user_id) REFERENCES users(user_id),
    INDEX idx_school_active (is_active),
    INDEX idx_school_dane (school_dane)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS grades (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    grade_name VARCHAR(20) NOT NULL COMMENT '1ro, 2do, etc.',
    grade_level VARCHAR(30) NOT NULL COMMENT 'Primaria, Secundaria',
    school_id INT NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (school_id) REFERENCES schools(school_id) ON DELETE CASCADE,
    INDEX idx_grade_school (school_id, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL COMMENT 'Matemáticas, Español, etc.',
    subject_code VARCHAR(20),
    description TEXT,
    credits INT DEFAULT 0,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_subject_active (is_active),
    INDEX idx_subject_code (subject_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS professor_subjects (
    professor_subject_id INT AUTO_INCREMENT PRIMARY KEY,
    professor_user_id INT NOT NULL,
    subject_id INT NOT NULL,
    school_id INT NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY idx_professor_subject_school (professor_user_id, subject_id, school_id),
    FOREIGN KEY (professor_user_id) REFERENCES users(user_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (school_id) REFERENCES schools(school_id),
    INDEX idx_professor_active (professor_user_id, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS class_groups (
    class_group_id INT AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(10) NOT NULL COMMENT 'A, B, etc.',
    grade_id INT NOT NULL,
    professor_user_id INT NOT NULL COMMENT 'Director de grupo',
    max_students TINYINT NOT NULL,
    classroom VARCHAR(20),
    school_year YEAR NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (grade_id) REFERENCES grades(grade_id),
    FOREIGN KEY (professor_user_id) REFERENCES users(user_id),
    INDEX idx_group_grade (grade_id, is_active),
    INDEX idx_group_professor (professor_user_id, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- ESTUDIANTES Y RELACIONES FAMILIARES
-- =============================================

CREATE TABLE IF NOT EXISTS student_enrollment (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    class_group_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    student_code VARCHAR(20) UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY idx_student_group (student_user_id, class_group_id),
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (class_group_id) REFERENCES class_groups(class_group_id),
    INDEX idx_enrollment_active (is_active),
    INDEX idx_student_code (student_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS student_parents (
    student_parent_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    parent_user_id INT NOT NULL,
    relationship_type VARCHAR(20) NOT NULL COMMENT 'Padre, Madre, Tutor',
    is_primary_contact BIT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY idx_student_parent (student_user_id, parent_user_id),
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (parent_user_id) REFERENCES users(user_id),
    INDEX idx_primary_contact (is_primary_contact)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- HORARIOS Y PROGRAMACIÓN ACADÉMICA
-- =============================================

CREATE TABLE IF NOT EXISTS academic_terms (
    term_id INT AUTO_INCREMENT PRIMARY KEY,
    term_name VARCHAR(30) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    school_year YEAR NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_term_active (is_active),
    INDEX idx_term_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    class_group_id INT NOT NULL,
    professor_subject_id INT NOT NULL COMMENT 'Relaciona profesor con materia',
    day_of_week TINYINT NOT NULL COMMENT '1=Lunes, 7=Domingo',
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    term_id INT NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (class_group_id) REFERENCES class_groups(class_group_id),
    FOREIGN KEY (professor_subject_id) REFERENCES professor_subjects(professor_subject_id),
    FOREIGN KEY (term_id) REFERENCES academic_terms(term_id),
    INDEX idx_schedule_class_day (class_group_id, day_of_week),
    INDEX idx_schedule_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- SISTEMA DE CALIFICACIONES Y ACTIVIDADES
-- =============================================

CREATE TABLE IF NOT EXISTS activity_types (
    activity_type_id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL COMMENT 'Examen, Tarea, Proyecto',
    weight_percentage DECIMAL(5,2) COMMENT 'Peso en la calificación final',
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_type_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS activities (
    activity_id INT AUTO_INCREMENT PRIMARY KEY,
    activity_name VARCHAR(100) NOT NULL,
    professor_subject_id INT NOT NULL,
    activity_type_id INT NOT NULL,
    class_group_id INT NOT NULL,
    term_id INT NOT NULL,
    max_score DECIMAL(5,2) NOT NULL,
    due_date DATETIME,
    description TEXT,
    created_by_user_id INT NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (professor_subject_id) REFERENCES professor_subjects(professor_subject_id),
    FOREIGN KEY (activity_type_id) REFERENCES activity_types(activity_type_id),
    FOREIGN KEY (class_group_id) REFERENCES class_groups(class_group_id),
    FOREIGN KEY (term_id) REFERENCES academic_terms(term_id),
    FOREIGN KEY (created_by_user_id) REFERENCES users(user_id),
    INDEX idx_activities_group_term (class_group_id, term_id),
    INDEX idx_activities_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS student_scores (
    score_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    activity_id INT NOT NULL,
    score DECIMAL(5,2) NOT NULL CHECK (score >= 0 AND score <= 10),
    score_date DATE NOT NULL,
    comments TEXT,
    recorded_by_user_id INT NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (activity_id) REFERENCES activities(activity_id),
    FOREIGN KEY (recorded_by_user_id) REFERENCES users(user_id),
    INDEX idx_student_scores_student (student_user_id),
    INDEX idx_score_active (is_active),
    INDEX idx_score_date (score_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- SISTEMA DE ASISTENCIA
-- =============================================

CREATE TABLE IF NOT EXISTS attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    schedule_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused') DEFAULT 'present',
    notes TEXT,
    recorded_by_user_id INT NOT NULL,
    recorded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY idx_student_schedule_date (student_user_id, schedule_id, attendance_date),
    FOREIGN KEY (student_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by_user_id) REFERENCES users(user_id),
    
    INDEX idx_student_user_id (student_user_id),
    INDEX idx_schedule_id (schedule_id),
    INDEX idx_attendance_date (attendance_date),
    INDEX idx_status (status),
    INDEX idx_student_date (student_user_id, attendance_date),
    INDEX idx_student_date_status (student_user_id, attendance_date, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- SISTEMA DE PAGOS ESTUDIANTILES
-- =============================================

CREATE TABLE IF NOT EXISTS student_payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    tuition_amount DECIMAL(10,2) NOT NULL,
    tuition_status ENUM('pending', 'paid', 'overdue', 'partial') DEFAULT 'pending',
    payment_due_date DATE NOT NULL,
    payment_date DATETIME DEFAULT NULL,
    payment_method VARCHAR(50) DEFAULT NULL,
    payment_notes TEXT,
    last_payment_date DATETIME DEFAULT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_student_payment (student_user_id),
    INDEX idx_tuition_status (tuition_status),
    INDEX idx_payment_due_date (payment_due_date),
    INDEX idx_payment_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- SISTEMA DE NÓMINA
-- =============================================

CREATE TABLE IF NOT EXISTS employees (
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
    INDEX idx_department (department),
    INDEX idx_employee_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS payroll_concepts (
    concept_id INT AUTO_INCREMENT PRIMARY KEY,
    concept_name VARCHAR(100) NOT NULL,
    concept_type ENUM('income', 'deduction') NOT NULL COMMENT 'Ingreso o deducción',
    concept_category VARCHAR(50) NOT NULL COMMENT 'Categoría: salario, bonos, descuentos, etc.',
    is_percentage BIT NOT NULL DEFAULT 0 COMMENT 'Si es porcentaje del salario base',
    default_value DECIMAL(10,2) DEFAULT 0 COMMENT 'Valor por defecto',
    is_mandatory BIT NOT NULL DEFAULT 0 COMMENT 'Si es obligatorio para todos los empleados',
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_concept_type (concept_type),
    INDEX idx_concept_category (concept_category),
    INDEX idx_concept_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS payroll_periods (
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
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by_user_id) REFERENCES users(user_id),
    INDEX idx_period_dates (start_date, end_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS payroll_records (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS payroll_concept_details (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    concept_id INT NOT NULL,
    concept_value DECIMAL(10,2) NOT NULL COMMENT 'Valor del concepto',
    concept_percentage DECIMAL(5,2) DEFAULT 0 COMMENT 'Porcentaje aplicado',
    description TEXT COMMENT 'Descripción adicional',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (record_id) REFERENCES payroll_records(record_id) ON DELETE CASCADE,
    FOREIGN KEY (concept_id) REFERENCES payroll_concepts(concept_id),
    INDEX idx_record_concept (record_id, concept_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS employee_absences (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS employee_overtime (
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
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (period_id) REFERENCES payroll_periods(period_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by_user_id) REFERENCES users(user_id),
    INDEX idx_employee_period (employee_id, period_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS employee_bonuses (
    bonus_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    period_id INT NOT NULL,
    bonus_type VARCHAR(50) NOT NULL COMMENT 'Tipo de bonificación',
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    status ENUM('pending', 'approved', 'paid') NOT NULL DEFAULT 'pending',
    approved_by_user_id INT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (period_id) REFERENCES payroll_periods(period_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by_user_id) REFERENCES users(user_id),
    INDEX idx_employee_period (employee_id, period_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- SISTEMA DE EVENTOS Y NOTIFICACIONES
-- =============================================

CREATE TABLE IF NOT EXISTS school_events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(100) NOT NULL,
    event_title VARCHAR(255) NOT NULL,
    event_description TEXT,
    start_date DATETIME NOT NULL,
    end_date DATETIME DEFAULT NULL,
    event_location VARCHAR(255) DEFAULT NULL,
    school_id INT NOT NULL,
    created_by_user_id INT NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (school_id) REFERENCES schools(school_id),
    FOREIGN KEY (created_by_user_id) REFERENCES users(user_id),
    INDEX idx_start_date (start_date),
    INDEX idx_event_type (event_type),
    INDEX idx_event_active (is_active),
    INDEX idx_school_events (school_id, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_user_id INT NOT NULL,
    notification_type VARCHAR(50) NOT NULL,
    notification_title VARCHAR(255) NOT NULL,
    notification_message TEXT NOT NULL,
    is_read BIT NOT NULL DEFAULT 0,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (recipient_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_recipient_read (recipient_user_id, is_read),
    INDEX idx_notification_type (notification_type),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- SISTEMA DE REPORTES ACADÉMICOS
-- =============================================

CREATE TABLE IF NOT EXISTS academic_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    term_id INT NOT NULL,
    report_type ENUM('quarterly', 'final', 'special') NOT NULL,
    report_date DATE NOT NULL,
    academic_average DECIMAL(4,2),
    conduct_grade VARCHAR(10),
    attendance_percentage DECIMAL(5,2),
    observations TEXT,
    created_by_user_id INT NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (term_id) REFERENCES academic_terms(term_id),
    FOREIGN KEY (created_by_user_id) REFERENCES users(user_id),
    INDEX idx_student_term (student_user_id, term_id),
    INDEX idx_report_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS conduct_reports (
    conduct_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    report_date DATE NOT NULL,
    incident_type VARCHAR(50) NOT NULL,
    incident_description TEXT NOT NULL,
    severity_level ENUM('low', 'medium', 'high', 'critical') NOT NULL,
    disciplinary_action TEXT,
    reported_by_user_id INT NOT NULL,
    is_resolved BIT NOT NULL DEFAULT 0,
    resolved_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (reported_by_user_id) REFERENCES users(user_id),
    INDEX idx_conduct_student (student_user_id),
    INDEX idx_conduct_date (report_date),
    INDEX idx_conduct_severity (severity_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS parent_meetings (
    meeting_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    parent_user_id INT NOT NULL,
    meeting_date DATETIME NOT NULL,
    meeting_type ENUM('academic', 'behavioral', 'general') NOT NULL,
    meeting_subject VARCHAR(255) NOT NULL,
    meeting_notes TEXT,
    meeting_outcome TEXT,
    scheduled_by_user_id INT NOT NULL,
    is_completed BIT NOT NULL DEFAULT 0,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (parent_user_id) REFERENCES users(user_id),
    FOREIGN KEY (scheduled_by_user_id) REFERENCES users(user_id),
    INDEX idx_meeting_student (student_user_id),
    INDEX idx_meeting_date (meeting_date),
    INDEX idx_meeting_completed (is_completed)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- DATOS INICIALES
-- =============================================

-- Insertar permisos por defecto
INSERT IGNORE INTO role_permissions (role_type, can_create, can_read, can_update, can_delete) VALUES
('student', FALSE, TRUE, FALSE, FALSE),
('parent', FALSE, TRUE, FALSE, FALSE),
('professor', TRUE, TRUE, TRUE, FALSE),
('coordinator', TRUE, TRUE, TRUE, TRUE),
('director', TRUE, TRUE, TRUE, TRUE),
('treasurer', TRUE, TRUE, TRUE, FALSE),
('root', TRUE, TRUE, TRUE, TRUE);

-- Insertar conceptos básicos de nómina
INSERT IGNORE INTO payroll_concepts (concept_name, concept_type, concept_category, is_percentage, default_value, is_mandatory) VALUES
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

-- Insertar tipos de actividad por defecto
INSERT IGNORE INTO activity_types (type_name, weight_percentage) VALUES
('Examen', 40.00),
('Tarea', 20.00),
('Proyecto', 25.00),
('Participación', 10.00),
('Laboratorio', 5.00);

-- =============================================
-- VISTAS PARA CONSULTAS FRECUENTES
-- =============================================

-- Vista para estadísticas de asistencia
CREATE OR REPLACE VIEW attendance_summary AS
SELECT 
    a.attendance_date as date,
    COUNT(DISTINCT a.student_user_id) as present_count,
    (SELECT COUNT(*) FROM users u 
     JOIN user_roles ur ON u.user_id = ur.user_id 
     WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1) as total_students,
    ROUND(
        COUNT(DISTINCT a.student_user_id) / 
        (SELECT COUNT(*) FROM users u 
         JOIN user_roles ur ON u.user_id = ur.user_id 
         WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1) * 100, 1
    ) as attendance_percentage
FROM attendance a
WHERE a.status = 'present'
GROUP BY a.attendance_date
ORDER BY a.attendance_date DESC;

-- Vista para estadísticas de pagos
CREATE OR REPLACE VIEW payment_statistics_view AS
SELECT 
    COUNT(*) AS total_accounts,
    SUM(CASE WHEN tuition_status = 'pending' THEN 1 ELSE 0 END) AS pending_payments,
    SUM(CASE WHEN tuition_status = 'paid' THEN 1 ELSE 0 END) AS completed_payments,
    SUM(CASE WHEN tuition_status = 'overdue' THEN 1 ELSE 0 END) AS overdue_payments,
    SUM(CASE WHEN tuition_status = 'partial' THEN 1 ELSE 0 END) AS partial_payments,
    ROUND(
        (SUM(CASE WHEN tuition_status = 'paid' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1
    ) AS completion_rate
FROM student_payments
WHERE is_active = 1;

-- Vista para eventos próximos
CREATE OR REPLACE VIEW upcoming_events_view AS
SELECT 
    event_id,
    event_type,
    event_title,
    event_description,
    start_date,
    end_date,
    event_location,
    DATEDIFF(start_date, NOW()) AS days_until,
    CASE 
        WHEN DATEDIFF(start_date, NOW()) = 0 THEN 'Hoy'
        WHEN DATEDIFF(start_date, NOW()) = 1 THEN 'Mañana'
        WHEN DATEDIFF(start_date, NOW()) <= 3 THEN 'Próximo'
        ELSE 'Futuro'
    END AS urgency_level
FROM school_events
WHERE start_date >= NOW() 
    AND is_active = 1
ORDER BY start_date ASC;

-- Vista para estadísticas académicas generales
CREATE OR REPLACE VIEW academic_general_stats_view AS
SELECT 
    COUNT(ss.score_id) AS total_scores,
    ROUND(AVG(ss.score), 2) AS global_average,
    MIN(ss.score) AS lowest_score,
    MAX(ss.score) AS highest_score,
    ROUND(STDDEV(ss.score), 2) AS standard_deviation,
    COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
    COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
    ROUND(
        (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
    ) AS global_pass_rate,
    COUNT(DISTINCT ss.student_user_id) AS total_students,
    COUNT(DISTINCT a.professor_subject_id) AS total_subjects
FROM student_scores ss
JOIN activities a ON ss.activity_id = a.activity_id
WHERE ss.is_active = 1;

-- =============================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =============================================

-- Índices para consultas frecuentes de nómina
CREATE INDEX IF NOT EXISTS idx_payroll_period_status ON payroll_periods(status, start_date);
CREATE INDEX IF NOT EXISTS idx_payroll_records_status ON payroll_records(status, period_id);
CREATE INDEX IF NOT EXISTS idx_employee_active ON employees(is_active, department);
CREATE INDEX IF NOT EXISTS idx_absences_employee_status ON employee_absences(employee_id, status, start_date);
CREATE INDEX IF NOT EXISTS idx_overtime_employee_period ON employee_overtime(employee_id, period_id, status);

-- Índices para consultas académicas
CREATE INDEX IF NOT EXISTS idx_score_student_term ON student_scores(student_user_id, activity_id);
CREATE INDEX IF NOT EXISTS idx_score_value ON student_scores(score);
CREATE INDEX IF NOT EXISTS idx_score_date ON student_scores(score_date);
CREATE INDEX IF NOT EXISTS idx_score_active ON student_scores(is_active, score);

-- Índices para consultas de eventos
CREATE INDEX IF NOT EXISTS idx_event_date_range ON school_events(start_date, end_date);
CREATE INDEX IF NOT EXISTS idx_event_type_date ON school_events(event_type, start_date);
CREATE INDEX IF NOT EXISTS idx_event_active_date ON school_events(is_active, start_date);

-- Índices para consultas de pagos
CREATE INDEX IF NOT EXISTS idx_payment_status_date ON student_payments(tuition_status, payment_due_date);
CREATE INDEX IF NOT EXISTS idx_payment_method ON student_payments(payment_method);
CREATE INDEX IF NOT EXISTS idx_payment_date ON student_payments(payment_date);
CREATE INDEX IF NOT EXISTS idx_payment_active_status ON student_payments(is_active, tuition_status);

-- =============================================
-- MENSAJE DE CONFIRMACIÓN
-- =============================================

SELECT 'Base de datos ByFrost unificada creada/actualizada correctamente' AS status; 