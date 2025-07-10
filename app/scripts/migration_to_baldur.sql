-- Script de migración para adaptar consultas al esquema Baldur.sql
-- Ejecutar este script después de Baldur.sql

-- =============================================
-- ADAPTACIONES PARA PAGOS
-- =============================================

-- Crear vista para compatibilidad con student_account
CREATE OR REPLACE VIEW student_account AS
SELECT 
    payment_id,
    student_user_id AS student_id,
    amount AS tuition_amount,
    CASE 
        WHEN status = 'pending' THEN 'pendiente'
        WHEN status = 'paid' THEN 'pagado'
        WHEN status = 'overdue' THEN 'atrasado'
        ELSE 'pendiente'
    END AS tuition_status,
    due_date AS payment_due_date,
    payment_date,
    'Transferencia' AS payment_method, -- Valor por defecto
    concept AS payment_notes,
    payment_date AS last_payment_date,
    1 AS is_active,
    created_at,
    created_at AS updated_at
FROM student_payments;

-- =============================================
-- ADAPTACIONES PARA CALIFICACIONES
-- =============================================

-- Crear vista para compatibilidad con subject_score
CREATE OR REPLACE VIEW subject_score AS
SELECT 
    score_id AS score_id,
    student_user_id AS student_id,
    (SELECT subject_id FROM activities a WHERE a.activity_id = ss.activity_id) AS subject_id,
    (SELECT professor_user_id FROM activities a 
     JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id 
     WHERE a.activity_id = ss.activity_id) AS teacher_id,
    (SELECT term_id FROM activities a WHERE a.activity_id = ss.activity_id) AS academic_term_id,
    score,
    'parcial' AS score_type,
    graded_at AS score_date,
    feedback AS comments,
    1 AS is_active,
    graded_at AS created_at,
    graded_at AS updated_at
FROM student_scores ss;

-- =============================================
-- ADAPTACIONES PARA ESTUDIANTES
-- =============================================

-- Crear vista para compatibilidad con student
CREATE OR REPLACE VIEW student AS
SELECT 
    user_id AS student_id,
    CONCAT(first_name, ' ', last_name) AS student_name,
    email,
    'Masculino' AS gender, -- Valor por defecto
    date_of_birth,
    phone,
    address,
    1 AS is_active,
    created_at,
    created_at AS updated_at
FROM users u
JOIN user_roles ur ON u.user_id = ur.user_id
WHERE ur.role_type = 'student' AND ur.is_active = 1;

-- =============================================
-- ADAPTACIONES PARA EVENTOS
-- =============================================

-- Crear vista para compatibilidad con event_school
CREATE OR REPLACE VIEW event_school AS
SELECT 
    event_id,
    event_type AS type_event,
    event_name AS title_event,
    description AS description_event,
    start_datetime AS start_date_event,
    end_datetime AS end_date_event,
    'Auditorio Principal' AS location_event, -- Valor por defecto
    1 AS is_active,
    created_at,
    created_at AS updated_at
FROM school_events;

-- =============================================
-- ADAPTACIONES PARA PERÍODOS ACADÉMICOS
-- =============================================

-- Crear vista para compatibilidad con academic_term
CREATE OR REPLACE VIEW academic_term AS
SELECT 
    term_id AS academic_term_id,
    term_name AS academic_term_name,
    start_date,
    end_date,
    school_year
FROM academic_terms;

-- =============================================
-- ADAPTACIONES PARA PROFESORES
-- =============================================

-- Crear vista para compatibilidad con teacher
CREATE OR REPLACE VIEW teacher AS
SELECT 
    user_id AS teacher_id,
    CONCAT(first_name, ' ', last_name) AS teacher_name,
    email,
    phone,
    address,
    1 AS is_active,
    created_at,
    created_at AS updated_at
FROM users u
JOIN user_roles ur ON u.user_id = ur.user_id
WHERE ur.role_type = 'professor' AND ur.is_active = 1;

-- =============================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =============================================

-- Índices para student_payments
CREATE INDEX IF NOT EXISTS idx_student_payments_status ON student_payments(status);
CREATE INDEX IF NOT EXISTS idx_student_payments_due_date ON student_payments(due_date);
CREATE INDEX IF NOT EXISTS idx_student_payments_student ON student_payments(student_user_id);

-- Índices para student_scores
CREATE INDEX IF NOT EXISTS idx_student_scores_student ON student_scores(student_user_id);
CREATE INDEX IF NOT EXISTS idx_student_scores_activity ON student_scores(activity_id);
CREATE INDEX IF NOT EXISTS idx_student_scores_graded_at ON student_scores(graded_at);

-- Índices para school_events
CREATE INDEX IF NOT EXISTS idx_school_events_start_datetime ON school_events(start_datetime);
CREATE INDEX IF NOT EXISTS idx_school_events_type ON school_events(event_type);
CREATE INDEX IF NOT EXISTS idx_school_events_school ON school_events(school_id);

-- =============================================
-- DATOS DE EJEMPLO PARA TESTING
-- =============================================

-- Insertar algunos pagos de ejemplo
INSERT IGNORE INTO student_payments (student_user_id, amount, concept, due_date, status) VALUES
(1, 500.00, 'Matrícula Enero', '2024-01-15', 'paid'),
(2, 500.00, 'Matrícula Enero', '2024-01-15', 'pending'),
(3, 500.00, 'Matrícula Enero', '2024-01-15', 'overdue'),
(4, 500.00, 'Matrícula Enero', '2024-01-15', 'paid'),
(5, 500.00, 'Matrícula Enero', '2024-01-15', 'pending');

-- Insertar algunas calificaciones de ejemplo
INSERT IGNORE INTO student_scores (student_user_id, activity_id, score, feedback, graded_by_user_id) VALUES
(1, 1, 4.5, 'Excelente trabajo', 1),
(2, 1, 3.8, 'Buen trabajo', 1),
(3, 1, 2.9, 'Necesita mejorar', 1),
(4, 1, 4.2, 'Muy bien', 1),
(5, 1, 3.5, 'Aceptable', 1);

-- Insertar algunos eventos de ejemplo
INSERT IGNORE INTO school_events (school_id, event_name, description, start_datetime, end_datetime, event_type, created_by_user_id) VALUES
(1, 'Reunión de Padres', 'Reunión general de padres de familia', '2024-01-20 09:00:00', '2024-01-20 11:00:00', 'Académico', 1),
(1, 'Torneo de Fútbol', 'Torneo intercolegial', '2024-01-22 14:00:00', '2024-01-22 18:00:00', 'Deportivo', 1),
(1, 'Festival de Arte', 'Exposición de trabajos artísticos', '2024-01-25 10:00:00', '2024-01-25 16:00:00', 'Cultural', 1);

-- =============================================
-- VISTAS PARA ESTADÍSTICAS
-- =============================================

-- Vista para estadísticas de pagos
CREATE OR REPLACE VIEW payment_statistics_view AS
SELECT 
    COUNT(*) AS total_accounts,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_payments,
    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS completed_payments,
    SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) AS overdue_payments,
    ROUND(
        (SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1
    ) AS completion_rate
FROM student_payments;

-- Vista para estadísticas académicas
CREATE OR REPLACE VIEW academic_statistics_view AS
SELECT 
    COUNT(ss.score_id) AS total_scores,
    ROUND(AVG(ss.score), 2) AS global_average,
    MIN(ss.score) AS lowest_score,
    MAX(ss.score) AS highest_score,
    COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
    COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
    ROUND(
        (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
    ) AS global_pass_rate
FROM student_scores ss;

-- Vista para estadísticas de eventos
CREATE OR REPLACE VIEW event_statistics_view AS
SELECT 
    COUNT(*) AS total_events,
    COUNT(CASE WHEN DATE(start_datetime) = CURDATE() THEN 1 END) AS today_events,
    COUNT(CASE WHEN start_datetime BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) THEN 1 END) AS upcoming_events,
    COUNT(DISTINCT event_type) AS event_types
FROM school_events;

-- Mostrar mensaje de confirmación
SELECT 'Migración a Baldur.sql completada exitosamente' AS status; 