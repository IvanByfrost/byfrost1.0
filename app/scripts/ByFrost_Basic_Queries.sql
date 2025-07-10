-- =============================================
-- BYFROST - CONSULTAS BÁSICAS ÚTILES
-- =============================================
-- Versión: 1.0
-- Fecha: 2025-01-27
-- Descripción: Consultas básicas para operaciones comunes
-- =============================================

-- =============================================
-- CONSULTAS DE USUARIOS
-- =============================================

-- 1. Listar todos los usuarios activos
SELECT 
    user_id,
    credential_number,
    CONCAT(first_name, ' ', last_name) AS full_name,
    email,
    phone,
    is_active,
    created_at
FROM users 
WHERE is_active = 1
ORDER BY last_name, first_name;

-- 2. Buscar usuario por número de documento
SELECT 
    user_id,
    credential_number,
    CONCAT(first_name, ' ', last_name) AS full_name,
    email,
    phone,
    is_active
FROM users 
WHERE credential_number = '12345678';

-- 3. Listar usuarios por rol
SELECT 
    u.user_id,
    u.credential_number,
    CONCAT(u.first_name, ' ', u.last_name) AS full_name,
    ur.role_type,
    u.email,
    u.phone
FROM users u
JOIN user_roles ur ON u.user_id = ur.user_id
WHERE u.is_active = 1 AND ur.is_active = 1
ORDER BY ur.role_type, u.last_name;

-- 4. Contar usuarios por rol
SELECT 
    ur.role_type,
    COUNT(*) AS total_users
FROM users u
JOIN user_roles ur ON u.user_id = ur.user_id
WHERE u.is_active = 1 AND ur.is_active = 1
GROUP BY ur.role_type
ORDER BY total_users DESC;

-- =============================================
-- CONSULTAS ACADÉMICAS
-- =============================================

-- 5. Listar todas las escuelas activas
SELECT 
    school_id,
    school_name,
    school_dane,
    total_quota,
    address,
    phone,
    email
FROM schools 
WHERE is_active = 1
ORDER BY school_name;

-- 6. Listar grados por escuela
SELECT 
    s.school_name,
    g.grade_name,
    g.grade_level,
    g.is_active
FROM grades g
JOIN schools s ON g.school_id = s.school_id
WHERE g.is_active = 1
ORDER BY s.school_name, g.grade_name;

-- 7. Listar materias activas
SELECT 
    subject_id,
    subject_name,
    subject_code,
    description,
    credits
FROM subjects 
WHERE is_active = 1
ORDER BY subject_name;

-- 8. Listar grupos de clase con información completa
SELECT 
    cg.class_group_id,
    cg.group_name,
    s.school_name,
    g.grade_name,
    CONCAT(u.first_name, ' ', u.last_name) AS director_name,
    cg.max_students,
    cg.classroom,
    cg.school_year
FROM class_groups cg
JOIN grades g ON cg.grade_id = g.grade_id
JOIN schools s ON g.school_id = s.school_id
JOIN users u ON cg.professor_user_id = u.user_id
WHERE cg.is_active = 1
ORDER BY s.school_name, g.grade_name, cg.group_name;

-- 9. Contar estudiantes por grupo
SELECT 
    cg.group_name,
    s.school_name,
    g.grade_name,
    COUNT(se.student_user_id) AS total_students,
    cg.max_students
FROM class_groups cg
JOIN grades g ON cg.grade_id = g.grade_id
JOIN schools s ON g.school_id = s.school_id
LEFT JOIN student_enrollment se ON cg.class_group_id = se.class_group_id AND se.is_active = 1
WHERE cg.is_active = 1
GROUP BY cg.class_group_id, cg.group_name, s.school_name, g.grade_name, cg.max_students
ORDER BY s.school_name, g.grade_name, cg.group_name;

-- 10. Listar estudiantes con sus padres
SELECT 
    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
    s.credential_number AS student_document,
    CONCAT(p.first_name, ' ', p.last_name) AS parent_name,
    p.credential_number AS parent_document,
    sp.relationship_type,
    sp.is_primary_contact
FROM student_parents sp
JOIN users s ON sp.student_user_id = s.user_id
JOIN users p ON sp.parent_user_id = p.user_id
WHERE s.is_active = 1 AND p.is_active = 1
ORDER BY s.last_name, s.first_name;

-- =============================================
-- CONSULTAS DE HORARIOS
-- =============================================

-- 11. Listar horarios por grupo
SELECT 
    cg.group_name,
    s.school_name,
    g.grade_name,
    sub.subject_name,
    CONCAT(u.first_name, ' ', u.last_name) AS professor_name,
    sch.day_of_week,
    sch.start_time,
    sch.end_time,
    at.term_name
FROM schedules sch
JOIN class_groups cg ON sch.class_group_id = cg.class_group_id
JOIN grades g ON cg.grade_id = g.grade_id
JOIN schools s ON g.school_id = s.school_id
JOIN professor_subjects ps ON sch.professor_subject_id = ps.professor_subject_id
JOIN subjects sub ON ps.subject_id = sub.subject_id
JOIN users u ON ps.professor_user_id = u.user_id
JOIN academic_terms at ON sch.term_id = at.term_id
WHERE sch.is_active = 1
ORDER BY cg.group_name, sch.day_of_week, sch.start_time;

-- 12. Horarios de un profesor específico
SELECT 
    CONCAT(u.first_name, ' ', u.last_name) AS professor_name,
    sub.subject_name,
    cg.group_name,
    sch.day_of_week,
    sch.start_time,
    sch.end_time
FROM schedules sch
JOIN professor_subjects ps ON sch.professor_subject_id = ps.professor_subject_id
JOIN users u ON ps.professor_user_id = u.user_id
JOIN subjects sub ON ps.subject_id = sub.subject_id
JOIN class_groups cg ON sch.class_group_id = cg.class_group_id
WHERE u.user_id = 1 AND sch.is_active = 1
ORDER BY sch.day_of_week, sch.start_time;

-- =============================================
-- CONSULTAS DE ACTIVIDADES Y CALIFICACIONES
-- =============================================

-- 13. Listar actividades por grupo
SELECT 
    cg.group_name,
    sub.subject_name,
    a.activity_name,
    at.type_name,
    a.max_score,
    a.due_date,
    CONCAT(u.first_name, ' ', u.last_name) AS created_by
FROM activities a
JOIN class_groups cg ON a.class_group_id = cg.class_group_id
JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
JOIN subjects sub ON ps.subject_id = sub.subject_id
JOIN activity_types at ON a.activity_type_id = at.activity_type_id
JOIN users u ON a.created_by_user_id = u.user_id
WHERE a.is_active = 1
ORDER BY cg.group_name, sub.subject_name, a.due_date;

-- 14. Calificaciones de un estudiante
SELECT 
    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
    sub.subject_name,
    a.activity_name,
    ss.score,
    ss.score_date,
    ss.comments,
    CONCAT(p.first_name, ' ', p.last_name) AS recorded_by
FROM student_scores ss
JOIN users u ON ss.student_user_id = u.user_id
JOIN activities a ON ss.activity_id = a.activity_id
JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
JOIN subjects sub ON ps.subject_id = sub.subject_id
JOIN users p ON ss.recorded_by_user_id = p.user_id
WHERE u.user_id = 1 AND ss.is_active = 1
ORDER BY ss.score_date DESC;

-- 15. Promedio por estudiante y materia
SELECT 
    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
    sub.subject_name,
    ROUND(AVG(ss.score), 2) AS average_score,
    COUNT(ss.score_id) AS total_activities,
    MIN(ss.score) AS lowest_score,
    MAX(ss.score) AS highest_score
FROM student_scores ss
JOIN users u ON ss.student_user_id = u.user_id
JOIN activities a ON ss.activity_id = a.activity_id
JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
JOIN subjects sub ON ps.subject_id = sub.subject_id
WHERE ss.is_active = 1
GROUP BY u.user_id, sub.subject_id, u.first_name, u.last_name, sub.subject_name
ORDER BY u.last_name, u.first_name, sub.subject_name;

-- =============================================
-- CONSULTAS DE ASISTENCIA
-- =============================================

-- 16. Asistencia por fecha
SELECT 
    a.attendance_date,
    cg.group_name,
    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
    a.status,
    a.notes
FROM attendance a
JOIN users u ON a.student_user_id = u.user_id
JOIN schedules s ON a.schedule_id = s.schedule_id
JOIN class_groups cg ON s.class_group_id = cg.class_group_id
WHERE a.attendance_date = CURDATE()
ORDER BY cg.group_name, u.last_name;

-- 17. Resumen de asistencia por grupo
SELECT 
    cg.group_name,
    a.attendance_date,
    COUNT(CASE WHEN a.status = 'present' THEN 1 END) AS present,
    COUNT(CASE WHEN a.status = 'absent' THEN 1 END) AS absent,
    COUNT(CASE WHEN a.status = 'late' THEN 1 END) AS late,
    COUNT(CASE WHEN a.status = 'excused' THEN 1 END) AS excused,
    COUNT(*) AS total
FROM attendance a
JOIN schedules s ON a.schedule_id = s.schedule_id
JOIN class_groups cg ON s.class_group_id = cg.class_group_id
WHERE a.attendance_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()
GROUP BY cg.class_group_id, cg.group_name, a.attendance_date
ORDER BY a.attendance_date DESC, cg.group_name;

-- =============================================
-- CONSULTAS DE PAGOS
-- =============================================

-- 18. Estado de pagos por estudiante
SELECT 
    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
    u.credential_number,
    sp.tuition_amount,
    sp.tuition_status,
    sp.payment_due_date,
    sp.payment_date,
    sp.payment_method
FROM student_payments sp
JOIN users u ON sp.student_user_id = u.user_id
WHERE sp.is_active = 1
ORDER BY sp.tuition_status, sp.payment_due_date;

-- 19. Pagos pendientes
SELECT 
    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
    u.credential_number,
    sp.tuition_amount,
    sp.payment_due_date,
    DATEDIFF(CURDATE(), sp.payment_due_date) AS days_overdue
FROM student_payments sp
JOIN users u ON sp.student_user_id = u.user_id
WHERE sp.tuition_status IN ('pending', 'overdue') 
    AND sp.is_active = 1
ORDER BY sp.payment_due_date;

-- =============================================
-- CONSULTAS DE NÓMINA
-- =============================================

-- 20. Listar empleados activos
SELECT 
    e.employee_id,
    e.employee_code,
    CONCAT(u.first_name, ' ', u.last_name) AS employee_name,
    e.position,
    e.department,
    e.salary,
    e.contract_type,
    e.hire_date
FROM employees e
JOIN users u ON e.user_id = u.user_id
WHERE e.is_active = 1
ORDER BY e.department, u.last_name;

-- 21. Períodos de nómina
SELECT 
    period_id,
    period_name,
    start_date,
    end_date,
    payment_date,
    status,
    total_employees,
    total_payroll
FROM payroll_periods
ORDER BY start_date DESC;

-- 22. Conceptos de nómina
SELECT 
    concept_id,
    concept_name,
    concept_type,
    concept_category,
    is_percentage,
    default_value,
    is_mandatory
FROM payroll_concepts
WHERE is_active = 1
ORDER BY concept_type, concept_category, concept_name;

-- =============================================
-- CONSULTAS DE EVENTOS
-- =============================================

-- 23. Eventos próximos
SELECT 
    event_id,
    event_type,
    event_title,
    event_description,
    start_date,
    end_date,
    event_location,
    DATEDIFF(start_date, NOW()) AS days_until
FROM school_events
WHERE start_date >= NOW() AND is_active = 1
ORDER BY start_date;

-- 24. Eventos por tipo
SELECT 
    event_type,
    COUNT(*) AS total_events,
    COUNT(CASE WHEN start_date >= NOW() THEN 1 END) AS upcoming_events,
    COUNT(CASE WHEN start_date < NOW() THEN 1 END) AS past_events
FROM school_events
WHERE is_active = 1
GROUP BY event_type
ORDER BY total_events DESC;

-- =============================================
-- CONSULTAS DE NOTIFICACIONES
-- =============================================

-- 25. Notificaciones no leídas por usuario
SELECT 
    CONCAT(u.first_name, ' ', u.last_name) AS recipient_name,
    n.notification_type,
    n.notification_title,
    n.notification_message,
    n.created_at
FROM notifications n
JOIN users u ON n.recipient_user_id = u.user_id
WHERE n.is_read = 0
ORDER BY n.created_at DESC;

-- =============================================
-- CONSULTAS ESTADÍSTICAS
-- =============================================

-- 26. Estadísticas generales del sistema
SELECT 
    (SELECT COUNT(*) FROM users WHERE is_active = 1) AS total_users,
    (SELECT COUNT(*) FROM schools WHERE is_active = 1) AS total_schools,
    (SELECT COUNT(*) FROM class_groups WHERE is_active = 1) AS total_groups,
    (SELECT COUNT(*) FROM student_enrollment WHERE is_active = 1) AS total_enrollments,
    (SELECT COUNT(*) FROM activities WHERE is_active = 1) AS total_activities,
    (SELECT COUNT(*) FROM student_scores WHERE is_active = 1) AS total_scores,
    (SELECT COUNT(*) FROM attendance WHERE attendance_date = CURDATE()) AS today_attendance,
    (SELECT COUNT(*) FROM school_events WHERE start_date >= NOW() AND is_active = 1) AS upcoming_events;

-- 27. Rendimiento académico por escuela
SELECT 
    s.school_name,
    COUNT(DISTINCT se.student_user_id) AS total_students,
    ROUND(AVG(ss.score), 2) AS average_score,
    COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
    COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores
FROM schools s
LEFT JOIN grades g ON s.school_id = g.school_id
LEFT JOIN class_groups cg ON g.grade_id = cg.grade_id
LEFT JOIN student_enrollment se ON cg.class_group_id = se.class_group_id AND se.is_active = 1
LEFT JOIN student_scores ss ON se.student_user_id = ss.student_user_id AND ss.is_active = 1
WHERE s.is_active = 1
GROUP BY s.school_id, s.school_name
ORDER BY average_score DESC;

-- =============================================
-- CONSULTAS DE REPORTES
-- =============================================

-- 28. Reportes académicos por estudiante
SELECT 
    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
    ar.report_type,
    ar.report_date,
    ar.academic_average,
    ar.conduct_grade,
    ar.attendance_percentage,
    ar.observations
FROM academic_reports ar
JOIN users u ON ar.student_user_id = u.user_id
WHERE ar.is_active = 1
ORDER BY ar.report_date DESC;

-- 29. Reportes de conducta
SELECT 
    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
    cr.incident_type,
    cr.incident_description,
    cr.severity_level,
    cr.report_date,
    cr.is_resolved
FROM conduct_reports cr
JOIN users s ON cr.student_user_id = s.user_id
ORDER BY cr.report_date DESC;

-- 30. Reuniones con padres
SELECT 
    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
    CONCAT(p.first_name, ' ', p.last_name) AS parent_name,
    pm.meeting_type,
    pm.meeting_subject,
    pm.meeting_date,
    pm.is_completed
FROM parent_meetings pm
JOIN users s ON pm.student_user_id = s.user_id
JOIN users p ON pm.parent_user_id = p.user_id
ORDER BY pm.meeting_date DESC;

-- =============================================
-- MENSAJE DE CONFIRMACIÓN
-- =============================================

SELECT 'Consultas básicas ByFrost cargadas correctamente' AS status; 