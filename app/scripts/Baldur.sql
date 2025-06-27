-- =============================================
-- ESQUEMA MYSQL PARA SISTEMA ESCOLAR
-- =============================================

-- Crear base de datos
-- CREATE DATABASE IF NOT EXISTS baldur_db 
-- CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- USE baldur_db;

-- CREATE USER 'byfrost_app_user'@'localhost' IDENTIFIED BY 'ByFrost2024!Secure#';
-- GRANT ALL PRIVILEGES ON baldur_db.* TO 'byfrost_app_user'@'localhost';
-- FLUSH PRIVILEGES;
-- =============================================
-- TABLAS CORE DE USUARIOS Y AUTENTICACIÓN
-- =============================================

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    credential_number VARCHAR(40) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(60) NOT NULL,
    credential_type VARCHAR(2) NOT NULL COMMENT 'CC, TI, etc.',
    date_of_birth DATE NOT NULL,
    address VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    password_hash VARBINARY(255) NOT NULL,
    salt_password VARBINARY(255) NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_credential (credential_number),
    INDEX idx_email (email)
) ENGINE=InnoDB;

CREATE TABLE user_roles (
    user_role_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_type ENUM('student', 'parent', 'professor', 'coordinator', 'director', 'treasurer', 'root') NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY idx_user_role (user_id, role_type),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- ESTRUCTURA ACADÉMICA
-- =============================================

CREATE TABLE schools (
    school_id INT AUTO_INCREMENT PRIMARY KEY,
    school_name VARCHAR(100) NOT NULL COMMENT 'Incluye sede: Colegio ABC - Sede Norte',
    total_quota SMALLINT NOT NULL,
    director_user_id INT NOT NULL,
    coordinator_user_id INT NOT NULL,
    address VARCHAR(200),
    phone VARCHAR(20),
    email VARCHAR(100),
    is_active BIT NOT NULL DEFAULT 1,
    
    FOREIGN KEY (director_user_id) REFERENCES users(user_id),
    FOREIGN KEY (coordinator_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE grades (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    grade_name VARCHAR(20) NOT NULL COMMENT '1ro, 2do, etc.',
    grade_level VARCHAR(30) NOT NULL COMMENT 'Primaria, Secundaria',
    school_id INT NOT NULL,
    
    FOREIGN KEY (school_id) REFERENCES schools(school_id)
) ENGINE=InnoDB;

CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL COMMENT 'Matemáticas, Español, etc.'
) ENGINE=InnoDB;

CREATE TABLE professor_subjects (
    professor_subject_id INT AUTO_INCREMENT PRIMARY KEY,
    professor_user_id INT NOT NULL,
    subject_id INT NOT NULL,
    school_id INT NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    
    UNIQUE KEY idx_professor_subject_school (professor_user_id, subject_id, school_id),
    FOREIGN KEY (professor_user_id) REFERENCES users(user_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (school_id) REFERENCES schools(school_id)
) ENGINE=InnoDB;

CREATE TABLE class_groups (
    class_group_id INT AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(10) NOT NULL COMMENT 'A, B, etc.',
    grade_id INT NOT NULL,
    professor_user_id INT NOT NULL COMMENT 'Director de grupo',
    max_students TINYINT NOT NULL,
    classroom VARCHAR(20),
    school_year YEAR NOT NULL,
    
    FOREIGN KEY (grade_id) REFERENCES grades(grade_id),
    FOREIGN KEY (professor_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- =============================================
-- ESTUDIANTES Y RELACIONES FAMILIARES
-- =============================================

CREATE TABLE student_enrollment (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    class_group_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    is_active BIT NOT NULL DEFAULT 1,
    student_code VARCHAR(20) UNIQUE,
    
    UNIQUE KEY idx_student_group (student_user_id, class_group_id),
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (class_group_id) REFERENCES class_groups(class_group_id)
) ENGINE=InnoDB;

CREATE TABLE student_parents (
    student_parent_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    parent_user_id INT NOT NULL,
    relationship_type VARCHAR(20) NOT NULL COMMENT 'Padre, Madre, Tutor',
    is_primary_contact BIT NOT NULL DEFAULT 0,
    
    UNIQUE KEY idx_student_parent (student_user_id, parent_user_id),
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (parent_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- =============================================
-- HORARIOS Y PROGRAMACIÓN ACADÉMICA
-- =============================================

CREATE TABLE academic_terms (
    term_id INT AUTO_INCREMENT PRIMARY KEY,
    term_name VARCHAR(30) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    school_year YEAR NOT NULL
) ENGINE=InnoDB;

CREATE TABLE schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    class_group_id INT NOT NULL,
    professor_subject_id INT NOT NULL COMMENT 'Relaciona profesor con materia',
    day_of_week TINYINT NOT NULL COMMENT '1=Lunes, 7=Domingo',
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    term_id INT NOT NULL,
    
    FOREIGN KEY (class_group_id) REFERENCES class_groups(class_group_id),
    FOREIGN KEY (professor_subject_id) REFERENCES professor_subjects(professor_subject_id),
    FOREIGN KEY (term_id) REFERENCES academic_terms(term_id)
) ENGINE=InnoDB;

-- =============================================
-- SISTEMA DE CALIFICACIONES Y ACTIVIDADES
-- =============================================

CREATE TABLE activity_types (
    activity_type_id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL COMMENT 'Examen, Tarea, Proyecto',
    weight_percentage DECIMAL(5,2) COMMENT 'Peso en la calificación final'
) ENGINE=InnoDB;

CREATE TABLE activities (
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
    
    FOREIGN KEY (professor_subject_id) REFERENCES professor_subjects(professor_subject_id),
    FOREIGN KEY (activity_type_id) REFERENCES activity_types(activity_type_id),
    FOREIGN KEY (class_group_id) REFERENCES class_groups(class_group_id),
    FOREIGN KEY (term_id) REFERENCES academic_terms(term_id),
    FOREIGN KEY (created_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE student_scores (
    score_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    activity_id INT NOT NULL,
    score DECIMAL(5,2),
    feedback TEXT,
    graded_by_user_id INT NOT NULL,
    graded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY idx_student_activity (student_user_id, activity_id),
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (activity_id) REFERENCES activities(activity_id),
    FOREIGN KEY (graded_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- =============================================
-- ASISTENCIA
-- =============================================

CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    schedule_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused') NOT NULL,
    notes TEXT,
    recorded_by_user_id INT NOT NULL,
    recorded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY idx_student_schedule_date (student_user_id, schedule_id, attendance_date),
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id),
    FOREIGN KEY (recorded_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- =============================================
-- DISCIPLINA Y COMUNICACIÓN
-- =============================================

CREATE TABLE conduct_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    reported_by_user_id INT NOT NULL,
    incident_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    severity ENUM('low', 'medium', 'high') NOT NULL,
    incident_date DATETIME NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('open', 'resolved', 'escalated') NOT NULL DEFAULT 'open',
    
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (reported_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE parent_meetings (
    meeting_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    parent_user_id INT NOT NULL,
    requested_by_user_id INT NOT NULL,
    reason TEXT NOT NULL,
    scheduled_date DATETIME NOT NULL,
    status ENUM('scheduled', 'completed', 'cancelled') NOT NULL DEFAULT 'scheduled',
    notes TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_user_id) REFERENCES users(user_id),
    FOREIGN KEY (parent_user_id) REFERENCES users(user_id),
    FOREIGN KEY (requested_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- =============================================
-- FINANZAS (SIMPLIFICADO)
-- =============================================

CREATE TABLE student_payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    concept VARCHAR(100) NOT NULL,
    due_date DATE NOT NULL,
    payment_date DATE,
    status ENUM('pending', 'paid', 'overdue') NOT NULL DEFAULT 'pending',
    transaction_reference VARCHAR(100),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- =============================================
-- EVENTOS Y COMUNICACIONES
-- =============================================

CREATE TABLE school_events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    school_id INT NOT NULL,
    event_name VARCHAR(100) NOT NULL,
    description TEXT,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    created_by_user_id INT NOT NULL,
    
    FOREIGN KEY (school_id) REFERENCES schools(school_id),
    FOREIGN KEY (created_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    notification_type VARCHAR(50) NOT NULL,
    is_read BIT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    
    FOREIGN KEY (recipient_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- =============================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =============================================

-- Índices para consultas frecuentes
CREATE INDEX idx_schedules_class_day ON schedules(class_group_id, day_of_week);
CREATE INDEX idx_attendance_student_date ON attendance(student_user_id, attendance_date);
CREATE INDEX idx_activities_group_term ON activities(class_group_id, term_id);
CREATE INDEX idx_student_scores_student ON student_scores(student_user_id);
CREATE INDEX idx_conduct_reports_student ON conduct_reports(student_user_id);
CREATE INDEX idx_notifications_recipient_read ON notifications(recipient_user_id, is_read);

-- =============================================
-- DATOS INICIALES
-- =============================================

-- Insertar tipos de actividades básicas
INSERT INTO activity_types (type_name, weight_percentage) VALUES
('Examen', 40.00),
('Quiz', 15.00),
('Tarea', 20.00),
('Proyecto', 25.00);

-- Insertar materias básicas
INSERT INTO subjects (subject_name) VALUES
('Matemáticas'),
('Español'),
('Ciencias Naturales'),
('Ciencias Sociales'),
('Inglés'),
('Educación Física'),
('Artes'),
('Informática');

-- Insertar términos académicos ejemplo
INSERT INTO academic_terms (term_name, start_date, end_date, school_year) VALUES
('Primer Período', '2024-02-01', '2024-04-30', 2024),
('Segundo Período', '2024-05-01', '2024-07-31', 2024),
('Tercer Período', '2024-08-01', '2024-11-30', 2024);