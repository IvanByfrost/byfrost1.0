-- Script para crear la tabla de asistencia optimizada para Baldur.sql
-- Ejecutar este script en tu base de datos MySQL

-- Tabla de asistencia optimizada para Baldur.sql
CREATE TABLE IF NOT EXISTS attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_user_id INT NOT NULL,
    schedule_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused') DEFAULT 'present',
    notes TEXT,
    recorded_by_user_id INT NOT NULL,
    recorded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY idx_student_schedule_date (student_user_id, schedule_id, attendance_date),
    FOREIGN KEY (student_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    INDEX idx_student_user_id (student_user_id),
    INDEX idx_schedule_id (schedule_id),
    INDEX idx_attendance_date (attendance_date),
    INDEX idx_status (status),
    INDEX idx_student_date (student_user_id, attendance_date),
    INDEX idx_student_date_status (student_user_id, attendance_date, status)
);

-- Insertar algunos datos de ejemplo para pruebas (requiere que existan users, schedules, etc.)
-- NOTA: Estos datos son de ejemplo. Ajusta los IDs según tu base de datos

-- Primero, verificar que existan los datos necesarios
-- INSERT INTO attendance (student_user_id, schedule_id, attendance_date, status, recorded_by_user_id) VALUES 
-- (1, 1, CURDATE(), 'present', 1),
-- (2, 1, CURDATE(), 'present', 1),
-- (3, 1, CURDATE(), 'present', 1),
-- (4, 1, CURDATE(), 'present', 1),
-- (5, 1, CURDATE(), 'present', 1),
-- (1, 2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'present', 1),
-- (2, 2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'present', 1),
-- (3, 2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'absent', 1),
-- (4, 2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'present', 1),
-- (5, 2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'late', 1),
-- (1, 3, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'present', 1),
-- (2, 3, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'present', 1),
-- (3, 3, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'present', 1),
-- (4, 3, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'absent', 1),
-- (5, 3, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'present', 1);

-- Script para insertar datos de prueba (descomenta después de tener users y schedules)
/*
INSERT INTO attendance (student_user_id, schedule_id, attendance_date, status, recorded_by_user_id) 
SELECT 
    u.user_id,
    s.schedule_id,
    CURDATE(),
    CASE WHEN RAND() > 0.2 THEN 'present' ELSE 'absent' END,
    1
FROM users u
JOIN user_roles ur ON u.user_id = ur.user_id
CROSS JOIN schedules s
WHERE ur.role_type = 'student' 
    AND u.is_active = 1 
    AND ur.is_active = 1
    AND s.schedule_id <= 3
LIMIT 15;
*/

-- Crear vista para facilitar consultas de asistencia optimizada para Baldur.sql
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

-- Crear vista adicional para estadísticas detalladas
CREATE OR REPLACE VIEW attendance_detailed AS
SELECT 
    a.attendance_id,
    a.student_user_id,
    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
    a.attendance_date,
    a.status,
    a.notes,
    s.schedule_id,
    cg.group_name,
    g.grade_name,
    sch.school_name,
    CONCAT(p.first_name, ' ', p.last_name) AS professor_name,
    sub.subject_name
FROM attendance a
JOIN users u ON a.student_user_id = u.user_id
JOIN schedules s ON a.schedule_id = s.schedule_id
JOIN class_groups cg ON s.class_group_id = cg.class_group_id
JOIN grades g ON cg.grade_id = g.grade_id
JOIN schools sch ON g.school_id = sch.school_id
JOIN professor_subjects ps ON s.professor_subject_id = ps.professor_subject_id
JOIN users p ON ps.professor_user_id = p.user_id
JOIN subjects sub ON ps.subject_id = sub.subject_id
WHERE u.is_active = 1
ORDER BY a.attendance_date DESC; 