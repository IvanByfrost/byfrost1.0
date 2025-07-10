-- Script para crear/actualizar tablas académicas
-- Ejecutar este script en la base de datos Byfrost

-- Verificar si la tabla academic_term existe, si no, crearla
CREATE TABLE IF NOT EXISTS `academic_term` (
    `academic_term_id` int(11) NOT NULL AUTO_INCREMENT,
    `academic_term_name` varchar(100) NOT NULL,
    `start_date` date NOT NULL,
    `end_date` date NOT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`academic_term_id`),
    KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verificar si la tabla subject_score existe, si no, crearla
CREATE TABLE IF NOT EXISTS `subject_score` (
    `score_id` int(11) NOT NULL AUTO_INCREMENT,
    `student_id` int(11) NOT NULL,
    `subject_id` int(11) NOT NULL,
    `teacher_id` int(11) NOT NULL,
    `academic_term_id` int(11) NOT NULL,
    `score` decimal(3,2) NOT NULL,
    `score_type` enum('parcial','final','extra') DEFAULT 'parcial',
    `score_date` date NOT NULL,
    `comments` text,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`score_id`),
    KEY `student_id` (`student_id`),
    KEY `subject_id` (`subject_id`),
    KEY `teacher_id` (`teacher_id`),
    KEY `academic_term_id` (`academic_term_id`),
    KEY `score` (`score`),
    KEY `is_active` (`is_active`),
    CONSTRAINT `fk_subject_score_student` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_subject_score_subject` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_subject_score_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_subject_score_academic_term` FOREIGN KEY (`academic_term_id`) REFERENCES `academic_term` (`academic_term_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar períodos académicos de ejemplo
INSERT IGNORE INTO `academic_term` (`academic_term_name`, `start_date`, `end_date`) VALUES
('Primer Trimestre 2024', '2024-01-15', '2024-04-15'),
('Segundo Trimestre 2024', '2024-04-16', '2024-07-15'),
('Tercer Trimestre 2024', '2024-07-16', '2024-10-15'),
('Cuarto Trimestre 2024', '2024-10-16', '2025-01-15');

-- Insertar calificaciones de ejemplo
INSERT IGNORE INTO `subject_score` (`student_id`, `subject_id`, `teacher_id`, `academic_term_id`, `score`, `score_type`, `score_date`, `comments`) VALUES
-- Primer Trimestre
(1, 1, 1, 1, 4.5, 'parcial', '2024-02-15', 'Excelente rendimiento'),
(1, 2, 2, 1, 4.2, 'parcial', '2024-02-20', 'Buen trabajo'),
(1, 3, 3, 1, 3.8, 'parcial', '2024-02-25', 'Aceptable'),
(2, 1, 1, 1, 4.8, 'parcial', '2024-02-15', 'Destacado'),
(2, 2, 2, 1, 4.0, 'parcial', '2024-02-20', 'Buen rendimiento'),
(2, 3, 3, 1, 3.5, 'parcial', '2024-02-25', 'Aceptable'),
(3, 1, 1, 1, 3.2, 'parcial', '2024-02-15', 'Necesita mejorar'),
(3, 2, 2, 1, 3.0, 'parcial', '2024-02-20', 'Aprobado'),
(3, 3, 3, 1, 2.8, 'parcial', '2024-02-25', 'Requiere apoyo'),
(4, 1, 1, 1, 4.0, 'parcial', '2024-02-15', 'Buen trabajo'),
(4, 2, 2, 1, 4.5, 'parcial', '2024-02-20', 'Excelente'),
(4, 3, 3, 1, 3.9, 'parcial', '2024-02-25', 'Buen rendimiento'),
(5, 1, 1, 1, 3.5, 'parcial', '2024-02-15', 'Aceptable'),
(5, 2, 2, 1, 3.2, 'parcial', '2024-02-20', 'Aprobado'),
(5, 3, 3, 1, 2.9, 'parcial', '2024-02-25', 'Necesita mejorar'),

-- Segundo Trimestre
(1, 1, 1, 2, 4.3, 'parcial', '2024-05-15', 'Mantiene buen nivel'),
(1, 2, 2, 2, 4.1, 'parcial', '2024-05-20', 'Consistente'),
(1, 3, 3, 2, 3.9, 'parcial', '2024-05-25', 'Mejoró'),
(2, 1, 1, 2, 4.6, 'parcial', '2024-05-15', 'Excelente progreso'),
(2, 2, 2, 2, 4.2, 'parcial', '2024-05-20', 'Buen trabajo'),
(2, 3, 3, 2, 3.8, 'parcial', '2024-05-25', 'Aceptable'),
(3, 1, 1, 2, 3.5, 'parcial', '2024-05-15', 'Mejoró'),
(3, 2, 2, 2, 3.2, 'parcial', '2024-05-20', 'Aprobado'),
(3, 3, 3, 2, 3.0, 'parcial', '2024-05-25', 'Aceptable'),
(4, 1, 1, 2, 4.2, 'parcial', '2024-05-15', 'Consistente'),
(4, 2, 2, 2, 4.4, 'parcial', '2024-05-20', 'Excelente'),
(4, 3, 3, 2, 4.0, 'parcial', '2024-05-25', 'Buen rendimiento'),
(5, 1, 1, 2, 3.8, 'parcial', '2024-05-15', 'Mejoró'),
(5, 2, 2, 2, 3.5, 'parcial', '2024-05-20', 'Aceptable'),
(5, 3, 3, 2, 3.2, 'parcial', '2024-05-25', 'Aprobado'),

-- Tercer Trimestre
(1, 1, 1, 3, 4.4, 'parcial', '2024-08-15', 'Mantiene excelencia'),
(1, 2, 2, 3, 4.3, 'parcial', '2024-08-20', 'Consistente'),
(1, 3, 3, 3, 4.0, 'parcial', '2024-08-25', 'Mejoró significativamente'),
(2, 1, 1, 3, 4.7, 'parcial', '2024-08-15', 'Destacado'),
(2, 2, 2, 3, 4.4, 'parcial', '2024-08-20', 'Excelente'),
(2, 3, 3, 3, 4.1, 'parcial', '2024-08-25', 'Buen progreso'),
(3, 1, 1, 3, 3.8, 'parcial', '2024-08-15', 'Mejoró'),
(3, 2, 2, 3, 3.5, 'parcial', '2024-08-20', 'Aceptable'),
(3, 3, 3, 3, 3.2, 'parcial', '2024-08-25', 'Aprobado'),
(4, 1, 1, 3, 4.3, 'parcial', '2024-08-15', 'Consistente'),
(4, 2, 2, 3, 4.5, 'parcial', '2024-08-20', 'Excelente'),
(4, 3, 3, 3, 4.2, 'parcial', '2024-08-25', 'Buen trabajo'),
(5, 1, 1, 3, 4.0, 'parcial', '2024-08-15', 'Mejoró'),
(5, 2, 2, 3, 3.8, 'parcial', '2024-08-20', 'Aceptable'),
(5, 3, 3, 3, 3.5, 'parcial', '2024-08-25', 'Buen rendimiento');

-- Crear vista para estadísticas generales
CREATE OR REPLACE VIEW `academic_general_stats_view` AS
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
    COUNT(DISTINCT ss.student_id) AS total_students,
    COUNT(DISTINCT ss.subject_id) AS total_subjects
FROM subject_score ss
WHERE ss.is_active = 1;

-- Crear vista para promedios por período
CREATE OR REPLACE VIEW `academic_term_averages_view` AS
SELECT 
    at.academic_term_name,
    at.academic_term_id,
    ROUND(AVG(ss.score), 2) AS average_score,
    COUNT(ss.score_id) AS total_scores,
    MIN(ss.score) AS min_score,
    MAX(ss.score) AS max_score,
    ROUND(STDDEV(ss.score), 2) AS standard_deviation,
    COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
    COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores
FROM subject_score ss
JOIN academic_term at ON ss.academic_term_id = at.academic_term_id
WHERE ss.is_active = 1
GROUP BY at.academic_term_id, at.academic_term_name
ORDER BY at.academic_term_id ASC;

-- Crear vista para promedios por asignatura
CREATE OR REPLACE VIEW `academic_subject_averages_view` AS
SELECT 
    s.subject_name,
    s.subject_id,
    ROUND(AVG(ss.score), 2) AS average_score,
    COUNT(ss.score_id) AS total_scores,
    MIN(ss.score) AS min_score,
    MAX(ss.score) AS max_score,
    COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
    COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
    ROUND(
        (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
    ) AS pass_rate
FROM subject_score ss
JOIN subject s ON ss.subject_id = s.subject_id
WHERE ss.is_active = 1
GROUP BY s.subject_id, s.subject_name
ORDER BY average_score DESC;

-- Crear vista para mejores estudiantes
CREATE OR REPLACE VIEW `academic_top_students_view` AS
SELECT 
    st.student_name,
    st.student_id,
    at.academic_term_name,
    ROUND(AVG(ss.score), 2) AS average_score,
    COUNT(ss.score_id) AS total_scores
FROM subject_score ss
JOIN student st ON ss.student_id = st.student_id
JOIN academic_term at ON ss.academic_term_id = at.academic_term_id
WHERE ss.is_active = 1
GROUP BY st.student_id, st.student_name, at.academic_term_id, at.academic_term_name
HAVING COUNT(ss.score_id) >= 3
ORDER BY average_score DESC;

-- Crear índices para optimizar consultas
CREATE INDEX IF NOT EXISTS `idx_score_student_term` ON `subject_score` (`student_id`, `academic_term_id`);
CREATE INDEX IF NOT EXISTS `idx_score_subject_term` ON `subject_score` (`subject_id`, `academic_term_id`);
CREATE INDEX IF NOT EXISTS `idx_score_teacher_term` ON `subject_score` (`teacher_id`, `academic_term_id`);
CREATE INDEX IF NOT EXISTS `idx_score_value` ON `subject_score` (`score`);
CREATE INDEX IF NOT EXISTS `idx_score_date` ON `subject_score` (`score_date`);
CREATE INDEX IF NOT EXISTS `idx_score_active` ON `subject_score` (`is_active`, `score`);

-- Mostrar mensaje de confirmación
SELECT 'Tablas académicas creadas/actualizadas correctamente' AS status; 