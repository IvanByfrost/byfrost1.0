-- Script para crear las tablas del sistema de calificaciones
-- Ejecutar este script en tu base de datos MySQL

-- Tabla de estudiantes (si no existe)
CREATE TABLE IF NOT EXISTS student (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    date_of_birth DATE,
    address TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de materias (si no existe)
CREATE TABLE IF NOT EXISTS subject (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    subject_code VARCHAR(20),
    description TEXT,
    credits INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de calificaciones
CREATE TABLE IF NOT EXISTS student_scores (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    activity_name VARCHAR(100) NOT NULL,
    score DECIMAL(4,2) NOT NULL CHECK (score >= 0 AND score <= 10),
    score_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student(student_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subject(subject_id) ON DELETE CASCADE,
    INDEX idx_student_id (student_id),
    INDEX idx_subject_id (subject_id),
    INDEX idx_score_date (score_date)
);

-- Insertar algunos datos de ejemplo (opcional)
INSERT INTO student (student_name, email, phone) VALUES 
('Juan Pérez', 'juan.perez@email.com', '3001234567'),
('María López', 'maria.lopez@email.com', '3001234568'),
('Carlos Ruiz', 'carlos.ruiz@email.com', '3001234569'),
('Ana García', 'ana.garcia@email.com', '3001234570'),
('Luis Martínez', 'luis.martinez@email.com', '3001234571');

INSERT INTO subject (subject_name, subject_code, description) VALUES 
('Matemáticas', 'MATH101', 'Matemáticas básicas'),
('Física', 'PHYS101', 'Física fundamental'),
('Historia', 'HIST101', 'Historia universal'),
('Literatura', 'LIT101', 'Literatura española'),
('Química', 'CHEM101', 'Química general');

-- Insertar algunas calificaciones de ejemplo
INSERT INTO student_scores (student_id, subject_id, activity_name, score, score_date) VALUES 
(1, 1, 'Examen Parcial', 8.5, '2024-03-15'),
(1, 1, 'Tarea 1', 9.0, '2024-03-10'),
(2, 1, 'Examen Parcial', 7.8, '2024-03-15'),
(2, 2, 'Laboratorio', 9.2, '2024-03-12'),
(3, 3, 'Ensayo', 8.0, '2024-03-14'),
(4, 4, 'Presentación', 9.5, '2024-03-13'),
(5, 5, 'Examen Final', 8.8, '2024-03-16'); 