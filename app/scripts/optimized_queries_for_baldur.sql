-- Consultas optimizadas para Baldur.sql
-- Estas consultas aprovechan mejor la estructura del esquema

-- =============================================
-- CONSULTAS DE PAGOS OPTIMIZADAS
-- =============================================

-- 1. Estadísticas de pagos usando student_payments directamente
CREATE OR REPLACE VIEW payment_statistics_optimized AS
SELECT 
    COUNT(*) AS total_accounts,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_payments,
    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS completed_payments,
    SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) AS overdue_payments,
    ROUND(
        (SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1
    ) AS completion_rate,
    SUM(amount) AS total_revenue,
    SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) AS collected_revenue,
    SUM(CASE WHEN status IN ('pending', 'overdue') THEN amount ELSE 0 END) AS pending_revenue
FROM student_payments;

-- 2. Pagos atrasados con información de estudiante
CREATE OR REPLACE VIEW overdue_payments_optimized AS
SELECT 
    sp.payment_id,
    sp.student_user_id,
    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
    u.email,
    sp.amount AS tuition_amount,
    sp.due_date AS payment_due_date,
    sp.status AS tuition_status,
    DATEDIFF(CURDATE(), sp.due_date) AS days_overdue,
    sp.payment_date AS last_payment_date,
    sp.concept AS payment_notes
FROM student_payments sp
JOIN users u ON sp.student_user_id = u.user_id
WHERE sp.status IN ('pending', 'overdue')
    AND sp.due_date < CURDATE()
    AND u.is_active = 1
ORDER BY days_overdue DESC;

-- =============================================
-- CONSULTAS ACADÉMICAS OPTIMIZADAS
-- =============================================

-- 3. Calificaciones con información completa usando JOINs
CREATE OR REPLACE VIEW academic_scores_optimized AS
SELECT 
    ss.score_id,
    ss.student_user_id,
    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
    s.subject_name,
    a.activity_name,
    ss.score,
    ss.feedback,
    ss.graded_at,
    at.type_name AS activity_type,
    ps.professor_user_id,
    CONCAT(p.first_name, ' ', p.last_name) AS professor_name,
    cg.group_name,
    g.grade_name,
    sch.school_name,
    act.term_id AS academic_term_id,
    act.term_name AS academic_term_name
FROM student_scores ss
JOIN users u ON ss.student_user_id = u.user_id
JOIN activities a ON ss.activity_id = a.activity_id
JOIN subjects s ON a.professor_subject_id = s.subject_id
JOIN activity_types at ON a.activity_type_id = at.activity_type_id
JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
JOIN users p ON ps.professor_user_id = p.user_id
JOIN class_groups cg ON a.class_group_id = cg.class_group_id
JOIN grades g ON cg.grade_id = g.grade_id
JOIN schools sch ON g.school_id = sch.school_id
JOIN academic_terms act ON a.term_id = act.term_id
WHERE u.is_active = 1;

-- 4. Promedios por período académico optimizado
CREATE OR REPLACE VIEW term_averages_optimized AS
SELECT 
    act.term_name AS academic_term_name,
    act.term_id AS academic_term_id,
    ROUND(AVG(ss.score), 2) AS average_score,
    COUNT(ss.score_id) AS total_scores,
    MIN(ss.score) AS min_score,
    MAX(ss.score) AS max_score,
    ROUND(STDDEV(ss.score), 2) AS standard_deviation,
    COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
    COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores
FROM student_scores ss
JOIN activities a ON ss.activity_id = a.activity_id
JOIN academic_terms act ON a.term_id = act.term_id
GROUP BY act.term_id, act.term_name
ORDER BY act.term_id ASC;

-- 5. Promedios por asignatura optimizado
CREATE OR REPLACE VIEW subject_averages_optimized AS
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
FROM student_scores ss
JOIN activities a ON ss.activity_id = a.activity_id
JOIN subjects s ON a.professor_subject_id = s.subject_id
GROUP BY s.subject_id, s.subject_name
ORDER BY average_score DESC;

-- =============================================
-- CONSULTAS DE ESTUDIANTES OPTIMIZADAS
-- =============================================

-- 6. Estadísticas de estudiantes usando users + user_roles
CREATE OR REPLACE VIEW student_statistics_optimized AS
SELECT 
    COUNT(*) AS total_students,
    SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END) AS active_students,
    SUM(CASE WHEN u.is_active = 0 THEN 1 ELSE 0 END) AS inactive_students,
    ROUND(
        (SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1
    ) AS active_percentage
FROM users u
JOIN user_roles ur ON u.user_id = ur.user_id
WHERE ur.role_type = 'student' AND ur.is_active = 1;

-- 7. Mejores estudiantes con información completa
CREATE OR REPLACE VIEW top_students_optimized AS
SELECT 
    u.user_id AS student_id,
    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
    u.email,
    act.term_name AS academic_term_name,
    ROUND(AVG(ss.score), 2) AS average_score,
    COUNT(ss.score_id) AS total_scores,
    COUNT(DISTINCT s.subject_id) AS subjects_count
FROM users u
JOIN user_roles ur ON u.user_id = ur.user_id
JOIN student_scores ss ON u.user_id = ss.student_user_id
JOIN activities a ON ss.activity_id = a.activity_id
JOIN subjects s ON a.professor_subject_id = s.subject_id
JOIN academic_terms act ON a.term_id = act.term_id
WHERE ur.role_type = 'student' 
    AND ur.is_active = 1 
    AND u.is_active = 1
GROUP BY u.user_id, u.first_name, u.last_name, u.email, act.term_id, act.term_name
HAVING COUNT(ss.score_id) >= 3
ORDER BY average_score DESC;

-- =============================================
-- CONSULTAS DE EVENTOS OPTIMIZADAS
-- =============================================

-- 8. Eventos próximos con información de escuela
CREATE OR REPLACE VIEW upcoming_events_optimized AS
SELECT 
    se.event_id,
    se.event_type AS type_event,
    se.event_name AS title_event,
    se.description AS description_event,
    se.start_datetime AS start_date_event,
    se.end_datetime AS end_date_event,
    sch.school_name,
    CONCAT(u.first_name, ' ', u.last_name) AS created_by_name,
    DATEDIFF(se.start_datetime, NOW()) AS days_until,
    CASE 
        WHEN DATEDIFF(se.start_datetime, NOW()) = 0 THEN 'Hoy'
        WHEN DATEDIFF(se.start_datetime, NOW()) = 1 THEN 'Mañana'
        WHEN DATEDIFF(se.start_datetime, NOW()) <= 3 THEN 'Próximo'
        ELSE 'Futuro'
    END AS urgency_level
FROM school_events se
JOIN schools sch ON se.school_id = sch.school_id
JOIN users u ON se.created_by_user_id = u.user_id
WHERE se.start_datetime >= NOW()
ORDER BY se.start_datetime ASC;

-- =============================================
-- CONSULTAS DE ASISTENCIA OPTIMIZADAS
-- =============================================

-- 9. Asistencia con información completa
CREATE OR REPLACE VIEW attendance_optimized AS
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

-- =============================================
-- CONSULTAS DE RIESGO ESTUDIANTIL OPTIMIZADAS
-- =============================================

-- 10. Estudiantes en riesgo por calificaciones bajas
CREATE OR REPLACE VIEW students_at_risk_optimized AS
SELECT 
    u.user_id AS student_id,
    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
    u.email,
    ROUND(AVG(ss.score), 2) AS average_score,
    COUNT(ss.score_id) AS total_activities,
    MAX(ss.graded_at) AS last_activity,
    COUNT(DISTINCT s.subject_id) AS subjects_count,
    COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_activities
FROM users u
JOIN user_roles ur ON u.user_id = ur.user_id
LEFT JOIN student_scores ss ON u.user_id = ss.student_user_id
LEFT JOIN activities a ON ss.activity_id = a.activity_id
LEFT JOIN subjects s ON a.professor_subject_id = s.subject_id
WHERE ur.role_type = 'student' 
    AND ur.is_active = 1 
    AND u.is_active = 1
GROUP BY u.user_id, u.first_name, u.last_name, u.email
HAVING average_score < 3.0 AND total_activities > 0
ORDER BY average_score ASC;

-- =============================================
-- CONSULTAS DE ACTIVIDADES OPTIMIZADAS
-- =============================================

-- 11. Actividades con información completa
CREATE OR REPLACE VIEW activities_optimized AS
SELECT 
    a.activity_id,
    a.activity_name,
    a.max_score,
    a.due_date,
    a.description,
    at.type_name AS activity_type,
    at.weight_percentage,
    s.subject_name,
    CONCAT(u.first_name, ' ', u.last_name) AS professor_name,
    cg.group_name,
    g.grade_name,
    sch.school_name,
    act.term_name AS academic_term_name,
    COUNT(ss.score_id) AS submitted_count,
    ROUND(AVG(ss.score), 2) AS average_score
FROM activities a
JOIN activity_types at ON a.activity_type_id = at.activity_type_id
JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
JOIN subjects s ON ps.subject_id = s.subject_id
JOIN users u ON ps.professor_user_id = u.user_id
JOIN class_groups cg ON a.class_group_id = cg.class_group_id
JOIN grades g ON cg.grade_id = g.grade_id
JOIN schools sch ON g.school_id = sch.school_id
JOIN academic_terms act ON a.term_id = act.term_id
LEFT JOIN student_scores ss ON a.activity_id = ss.activity_id
GROUP BY a.activity_id, a.activity_name, a.max_score, a.due_date, a.description,
         at.type_name, at.weight_percentage, s.subject_name, u.first_name, u.last_name,
         cg.group_name, g.grade_name, sch.school_name, act.term_name
ORDER BY a.due_date DESC;

-- =============================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =============================================

-- Índices para las consultas optimizadas
CREATE INDEX IF NOT EXISTS idx_student_payments_status_due ON student_payments(status, due_date);
CREATE INDEX IF NOT EXISTS idx_student_scores_student_activity ON student_scores(student_user_id, activity_id);
CREATE INDEX IF NOT EXISTS idx_activities_term_subject ON activities(term_id, professor_subject_id);
CREATE INDEX IF NOT EXISTS idx_school_events_datetime_type ON school_events(start_datetime, event_type);
CREATE INDEX IF NOT EXISTS idx_attendance_student_date_status ON attendance(student_user_id, attendance_date, status);
CREATE INDEX IF NOT EXISTS idx_users_role_active ON users(user_id, is_active);
CREATE INDEX IF NOT EXISTS idx_user_roles_type_active ON user_roles(user_id, role_type, is_active);

-- Mostrar mensaje de confirmación
SELECT 'Consultas optimizadas para Baldur.sql creadas exitosamente' AS status; 