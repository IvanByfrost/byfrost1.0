-- =============================================
-- BYFROST - INSERCIONES BÁSICAS DE DATOS
-- =============================================
-- Versión: 1.0
-- Fecha: 2025-01-27
-- Descripción: Datos básicos para poblar la base de datos
-- =============================================

-- =============================================
-- INSERCIONES DE USUARIOS
-- =============================================

-- Insertar usuarios básicos
INSERT INTO users (credential_number, first_name, last_name, credential_type, date_of_birth, address, phone, email, password_hash, salt_password, is_active) VALUES
-- Administradores
('10000001', 'Admin', 'Sistema', 'CC', '1980-01-01', 'Calle 123 #45-67', '3001234567', 'admin@byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('10000002', 'Director', 'General', 'CC', '1975-05-15', 'Calle 456 #78-90', '3001234568', 'director@byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('10000003', 'Coordinador', 'Académico', 'CC', '1982-03-20', 'Calle 789 #12-34', '3001234569', 'coordinador@byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('10000004', 'Tesorero', 'Financiero', 'CC', '1985-07-10', 'Calle 321 #56-78', '3001234570', 'tesorero@byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),

-- Profesores
('20000001', 'María', 'González', 'CC', '1988-02-14', 'Calle 654 #98-76', '3001234571', 'maria.gonzalez@byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('20000002', 'Carlos', 'Rodríguez', 'CC', '1983-11-08', 'Calle 987 #21-43', '3001234572', 'carlos.rodriguez@byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('20000003', 'Ana', 'López', 'CC', '1990-09-25', 'Calle 147 #36-58', '3001234573', 'ana.lopez@byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('20000004', 'Luis', 'Martínez', 'CC', '1987-04-12', 'Calle 258 #47-69', '3001234574', 'luis.martinez@byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('20000005', 'Sofia', 'Hernández', 'CC', '1989-06-30', 'Calle 369 #58-70', '3001234575', 'sofia.hernandez@byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),

-- Estudiantes
('30000001', 'Juan', 'Pérez', 'TI', '2010-03-15', 'Calle 741 #85-96', '3001234576', 'juan.perez@estudiante.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('30000002', 'María', 'García', 'TI', '2010-07-22', 'Calle 852 #96-07', '3001234577', 'maria.garcia@estudiante.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('30000003', 'Carlos', 'López', 'TI', '2010-01-10', 'Calle 963 #07-18', '3001234578', 'carlos.lopez@estudiante.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('30000004', 'Ana', 'Martínez', 'TI', '2010-05-18', 'Calle 147 #18-29', '3001234579', 'ana.martinez@estudiante.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('30000005', 'Luis', 'Rodríguez', 'TI', '2010-11-05', 'Calle 258 #29-30', '3001234580', 'luis.rodriguez@estudiante.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('30000006', 'Sofia', 'Hernández', 'TI', '2010-08-12', 'Calle 369 #30-41', '3001234581', 'sofia.hernandez@estudiante.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('30000007', 'Diego', 'González', 'TI', '2010-02-28', 'Calle 741 #41-52', '3001234582', 'diego.gonzalez@estudiante.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('30000008', 'Valentina', 'Pérez', 'TI', '2010-06-14', 'Calle 852 #52-63', '3001234583', 'valentina.perez@estudiante.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('30000009', 'Andrés', 'López', 'TI', '2010-09-03', 'Calle 963 #63-74', '3001234584', 'andres.lopez@estudiante.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('30000010', 'Camila', 'Martínez', 'TI', '2010-12-20', 'Calle 147 #74-85', '3001234585', 'camila.martinez@estudiante.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),

-- Padres
('40000001', 'Roberto', 'Pérez', 'CC', '1980-04-15', 'Calle 741 #85-96', '3001234586', 'roberto.perez@padre.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('40000002', 'Carmen', 'García', 'CC', '1982-08-22', 'Calle 852 #96-07', '3001234587', 'carmen.garcia@padre.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('40000003', 'Miguel', 'López', 'CC', '1978-01-10', 'Calle 963 #07-18', '3001234588', 'miguel.lopez@padre.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('40000004', 'Patricia', 'Martínez', 'CC', '1985-05-18', 'Calle 147 #18-29', '3001234589', 'patricia.martinez@padre.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1),
('40000005', 'Fernando', 'Rodríguez', 'CC', '1983-11-05', 'Calle 258 #29-30', '3001234590', 'fernando.rodriguez@padre.byfrost.edu.co', UNHEX('dummy_hash'), UNHEX('dummy_salt'), 1);

-- Insertar roles de usuario
INSERT INTO user_roles (user_id, role_type, is_active) VALUES
-- Administradores
(1, 'root', 1),
(2, 'director', 1),
(3, 'coordinator', 1),
(4, 'treasurer', 1),

-- Profesores
(5, 'professor', 1),
(6, 'professor', 1),
(7, 'professor', 1),
(8, 'professor', 1),
(9, 'professor', 1),

-- Estudiantes
(10, 'student', 1),
(11, 'student', 1),
(12, 'student', 1),
(13, 'student', 1),
(14, 'student', 1),
(15, 'student', 1),
(16, 'student', 1),
(17, 'student', 1),
(18, 'student', 1),
(19, 'student', 1),

-- Padres
(20, 'parent', 1),
(21, 'parent', 1),
(22, 'parent', 1),
(23, 'parent', 1),
(24, 'parent', 1);

-- =============================================
-- INSERCIONES DE ESCUELAS
-- =============================================

-- Insertar escuelas
INSERT INTO schools (school_name, school_dane, school_document, total_quota, director_user_id, coordinator_user_id, address, phone, email, is_active) VALUES
('Colegio ByFrost - Sede Principal', '1234567890', '900123456', 500, 2, 3, 'Calle 123 #45-67, Bogotá', '6011234567', 'principal@byfrost.edu.co', 1),
('Colegio ByFrost - Sede Norte', '0987654321', '900654321', 300, 2, 3, 'Calle 456 #78-90, Bogotá', '6011234568', 'norte@byfrost.edu.co', 1);

-- =============================================
-- INSERCIONES DE GRADOS
-- =============================================

-- Insertar grados para la sede principal
INSERT INTO grades (grade_name, grade_level, school_id, is_active) VALUES
-- Primaria
('1ro', 'Primaria', 1, 1),
('2do', 'Primaria', 1, 1),
('3ro', 'Primaria', 1, 1),
('4to', 'Primaria', 1, 1),
('5to', 'Primaria', 1, 1),

-- Secundaria
('6to', 'Secundaria', 1, 1),
('7mo', 'Secundaria', 1, 1),
('8vo', 'Secundaria', 1, 1),
('9no', 'Secundaria', 1, 1),
('10mo', 'Secundaria', 1, 1),
('11mo', 'Secundaria', 1, 1);

-- =============================================
-- INSERCIONES DE MATERIAS
-- =============================================

-- Insertar materias básicas
INSERT INTO subjects (subject_name, subject_code, description, credits, is_active) VALUES
('Matemáticas', 'MATH', 'Matemáticas básicas y avanzadas', 4, 1),
('Español', 'SPAN', 'Lenguaje y literatura española', 4, 1),
('Ciencias Naturales', 'SCI', 'Biología, química y física', 4, 1),
('Ciencias Sociales', 'SOC', 'Historia, geografía y cívica', 3, 1),
('Inglés', 'ENG', 'Lengua extranjera inglés', 3, 1),
('Educación Física', 'PE', 'Deportes y actividad física', 2, 1),
('Arte', 'ART', 'Expresión artística y cultural', 2, 1),
('Tecnología', 'TECH', 'Informática y tecnología', 3, 1);

-- =============================================
-- INSERCIONES DE PROFESORES Y MATERIAS
-- =============================================

-- Asignar profesores a materias
INSERT INTO professor_subjects (professor_user_id, subject_id, school_id, is_active) VALUES
(5, 1, 1, 1), -- María González - Matemáticas
(6, 2, 1, 1), -- Carlos Rodríguez - Español
(7, 3, 1, 1), -- Ana López - Ciencias Naturales
(8, 4, 1, 1), -- Luis Martínez - Ciencias Sociales
(9, 5, 1, 1), -- Sofia Hernández - Inglés
(5, 6, 1, 1), -- María González - Educación Física
(6, 7, 1, 1), -- Carlos Rodríguez - Arte
(7, 8, 1, 1); -- Ana López - Tecnología

-- =============================================
-- INSERCIONES DE GRUPOS DE CLASE
-- =============================================

-- Insertar grupos de clase
INSERT INTO class_groups (group_name, grade_id, professor_user_id, max_students, classroom, school_year, is_active) VALUES
('A', 6, 5, 30, 'Aula 101', 2024, 1),
('B', 6, 6, 30, 'Aula 102', 2024, 1),
('A', 7, 7, 30, 'Aula 201', 2024, 1),
('B', 7, 8, 30, 'Aula 202', 2024, 1),
('A', 8, 9, 30, 'Aula 301', 2024, 1);

-- =============================================
-- INSERCIONES DE MATRÍCULA DE ESTUDIANTES
-- =============================================

-- Matricular estudiantes en grupos
INSERT INTO student_enrollment (student_user_id, class_group_id, enrollment_date, is_active, student_code) VALUES
(10, 1, '2024-01-15', 1, 'EST001'),
(11, 1, '2024-01-15', 1, 'EST002'),
(12, 1, '2024-01-15', 1, 'EST003'),
(13, 2, '2024-01-15', 1, 'EST004'),
(14, 2, '2024-01-15', 1, 'EST005'),
(15, 3, '2024-01-15', 1, 'EST006'),
(16, 3, '2024-01-15', 1, 'EST007'),
(17, 4, '2024-01-15', 1, 'EST008'),
(18, 4, '2024-01-15', 1, 'EST009'),
(19, 5, '2024-01-15', 1, 'EST010');

-- =============================================
-- INSERCIONES DE RELACIONES ESTUDIANTE-PADRES
-- =============================================

-- Asignar padres a estudiantes
INSERT INTO student_parents (student_user_id, parent_user_id, relationship_type, is_primary_contact) VALUES
(10, 20, 'Padre', 1),
(10, 21, 'Madre', 0),
(11, 22, 'Padre', 1),
(12, 23, 'Madre', 1),
(13, 24, 'Padre', 1),
(14, 20, 'Padre', 1),
(15, 21, 'Madre', 1),
(16, 22, 'Padre', 1),
(17, 23, 'Madre', 1),
(18, 24, 'Padre', 1),
(19, 20, 'Madre', 1);

-- =============================================
-- INSERCIONES DE PERÍODOS ACADÉMICOS
-- =============================================

-- Insertar períodos académicos
INSERT INTO academic_terms (term_name, start_date, end_date, school_year, is_active) VALUES
('Primer Trimestre 2024', '2024-01-15', '2024-04-15', 2024, 1),
('Segundo Trimestre 2024', '2024-04-16', '2024-07-15', 2024, 1),
('Tercer Trimestre 2024', '2024-07-16', '2024-10-15', 2024, 1),
('Cuarto Trimestre 2024', '2024-10-16', '2025-01-15', 2024, 1);

-- =============================================
-- INSERCIONES DE HORARIOS
-- =============================================

-- Insertar horarios básicos (Lunes a Viernes, 8:00 AM - 12:00 PM)
INSERT INTO schedules (class_group_id, professor_subject_id, day_of_week, start_time, end_time, term_id, is_active) VALUES
-- Grupo 6A - Lunes
(1, 1, 1, '08:00:00', '09:00:00', 1, 1), -- Matemáticas
(1, 2, 1, '09:00:00', '10:00:00', 1, 1), -- Español
(1, 3, 1, '10:00:00', '11:00:00', 1, 1), -- Ciencias
(1, 4, 1, '11:00:00', '12:00:00', 1, 1), -- Sociales

-- Grupo 6A - Martes
(1, 5, 2, '08:00:00', '09:00:00', 1, 1), -- Inglés
(1, 6, 2, '09:00:00', '10:00:00', 1, 1), -- Educación Física
(1, 7, 2, '10:00:00', '11:00:00', 1, 1), -- Arte
(1, 8, 2, '11:00:00', '12:00:00', 1, 1), -- Tecnología

-- Grupo 6B - Lunes
(2, 1, 1, '08:00:00', '09:00:00', 1, 1), -- Matemáticas
(2, 2, 1, '09:00:00', '10:00:00', 1, 1), -- Español
(2, 3, 1, '10:00:00', '11:00:00', 1, 1), -- Ciencias
(2, 4, 1, '11:00:00', '12:00:00', 1, 1); -- Sociales

-- =============================================
-- INSERCIONES DE ACTIVIDADES
-- =============================================

-- Insertar actividades básicas
INSERT INTO activities (activity_name, professor_subject_id, activity_type_id, class_group_id, term_id, max_score, due_date, description, created_by_user_id, is_active) VALUES
-- Matemáticas - Grupo 6A
('Examen Parcial 1 - Matemáticas', 1, 1, 1, 1, 10.0, '2024-02-15 23:59:59', 'Examen sobre álgebra básica', 5, 1),
('Tarea 1 - Ecuaciones', 1, 2, 1, 1, 5.0, '2024-02-10 23:59:59', 'Resolver ecuaciones de primer grado', 5, 1),
('Proyecto - Geometría', 1, 3, 1, 1, 15.0, '2024-03-01 23:59:59', 'Proyecto sobre figuras geométricas', 5, 1),

-- Español - Grupo 6A
('Examen Parcial 1 - Español', 2, 1, 1, 1, 10.0, '2024-02-20 23:59:59', 'Examen sobre gramática', 6, 1),
('Ensayo - Mi Familia', 2, 2, 1, 1, 8.0, '2024-02-25 23:59:59', 'Escribir un ensayo sobre la familia', 6, 1),

-- Ciencias - Grupo 6A
('Examen Parcial 1 - Ciencias', 3, 1, 1, 1, 10.0, '2024-02-18 23:59:59', 'Examen sobre el sistema solar', 7, 1),
('Laboratorio - Plantas', 3, 5, 1, 1, 12.0, '2024-02-28 23:59:59', 'Experimento con plantas', 7, 1);

-- =============================================
-- INSERCIONES DE CALIFICACIONES
-- =============================================

-- Insertar algunas calificaciones de ejemplo
INSERT INTO student_scores (student_user_id, activity_id, score, score_date, comments, recorded_by_user_id, is_active) VALUES
-- Calificaciones para Juan Pérez
(10, 1, 8.5, '2024-02-15', 'Excelente trabajo en álgebra', 5, 1),
(10, 2, 9.0, '2024-02-10', 'Muy bien resuelto', 5, 1),
(10, 4, 7.8, '2024-02-20', 'Buen dominio de la gramática', 6, 1),
(10, 5, 8.2, '2024-02-25', 'Ensayo bien estructurado', 6, 1),
(10, 6, 9.5, '2024-02-18', 'Excelente conocimiento del tema', 7, 1),

-- Calificaciones para María García
(11, 1, 9.2, '2024-02-15', 'Destacado en matemáticas', 5, 1),
(11, 2, 8.8, '2024-02-10', 'Muy buena comprensión', 5, 1),
(11, 4, 8.5, '2024-02-20', 'Buen manejo del idioma', 6, 1),
(11, 5, 9.0, '2024-02-25', 'Ensayo creativo y bien escrito', 6, 1),
(11, 6, 8.9, '2024-02-18', 'Muy buen conocimiento', 7, 1),

-- Calificaciones para Carlos López
(12, 1, 7.5, '2024-02-15', 'Necesita mejorar en álgebra', 5, 1),
(12, 2, 8.0, '2024-02-10', 'Aceptable', 5, 1),
(12, 4, 7.2, '2024-02-20', 'Requiere más práctica', 6, 1),
(12, 5, 7.8, '2024-02-25', 'Ensayo aceptable', 6, 1),
(12, 6, 8.1, '2024-02-18', 'Buen trabajo', 7, 1);

-- =============================================
-- INSERCIONES DE ASISTENCIA
-- =============================================

-- Insertar asistencia de ejemplo para hoy
INSERT INTO attendance (student_user_id, schedule_id, attendance_date, status, notes, recorded_by_user_id) VALUES
-- Grupo 6A - Lunes (Matemáticas)
(10, 1, CURDATE(), 'present', NULL, 5),
(11, 1, CURDATE(), 'present', NULL, 5),
(12, 1, CURDATE(), 'late', 'Llegó 10 minutos tarde', 5),

-- Grupo 6A - Lunes (Español)
(10, 2, CURDATE(), 'present', NULL, 6),
(11, 2, CURDATE(), 'present', NULL, 6),
(12, 2, CURDATE(), 'present', NULL, 6),

-- Grupo 6A - Lunes (Ciencias)
(10, 3, CURDATE(), 'present', NULL, 7),
(11, 3, CURDATE(), 'absent', 'Justificada por médico', 7),
(12, 3, CURDATE(), 'present', NULL, 7);

-- =============================================
-- INSERCIONES DE PAGOS
-- =============================================

-- Insertar pagos de estudiantes
INSERT INTO student_payments (student_user_id, tuition_amount, tuition_status, payment_due_date, payment_date, payment_method, payment_notes, is_active) VALUES
(10, 500000.00, 'paid', '2024-01-15', '2024-01-14 10:30:00', 'Transferencia', 'Pago completo realizado', 1),
(11, 500000.00, 'paid', '2024-01-15', '2024-01-13 15:45:00', 'Efectivo', 'Pago en oficina', 1),
(12, 500000.00, 'pending', '2024-01-15', NULL, NULL, 'Pendiente de pago', 1),
(13, 500000.00, 'paid', '2024-01-15', '2024-01-12 09:20:00', 'Tarjeta', 'Pago con tarjeta de crédito', 1),
(14, 500000.00, 'overdue', '2024-01-15', NULL, NULL, 'Pago atrasado', 1),
(15, 500000.00, 'partial', '2024-01-15', '2024-01-09 16:20:00', 'Transferencia', 'Pago parcial de 300000', 1);

-- =============================================
-- INSERCIONES DE EMPLEADOS
-- =============================================

-- Insertar empleados
INSERT INTO employees (user_id, employee_code, position, department, hire_date, salary, contract_type, work_schedule, bank_account, bank_name, is_active) VALUES
(5, 'EMP001', 'Profesor de Matemáticas', 'Académico', '2023-01-15', 2500000.00, 'full_time', 'Lunes a Viernes 8:00-16:00', '1234567890', 'Banco de Bogotá', 1),
(6, 'EMP002', 'Profesor de Español', 'Académico', '2023-02-01', 2500000.00, 'full_time', 'Lunes a Viernes 8:00-16:00', '0987654321', 'Banco de Bogotá', 1),
(7, 'EMP003', 'Profesor de Ciencias', 'Académico', '2023-02-15', 2500000.00, 'full_time', 'Lunes a Viernes 8:00-16:00', '1122334455', 'Banco de Bogotá', 1),
(8, 'EMP004', 'Profesor de Sociales', 'Académico', '2023-03-01', 2500000.00, 'full_time', 'Lunes a Viernes 8:00-16:00', '5566778899', 'Banco de Bogotá', 1),
(9, 'EMP005', 'Profesor de Inglés', 'Académico', '2023-03-15', 2500000.00, 'full_time', 'Lunes a Viernes 8:00-16:00', '9988776655', 'Banco de Bogotá', 1);

-- =============================================
-- INSERCIONES DE PERÍODOS DE NÓMINA
-- =============================================

-- Insertar períodos de nómina
INSERT INTO payroll_periods (period_name, start_date, end_date, payment_date, status, total_employees, total_payroll, created_by_user_id) VALUES
('Enero 2024', '2024-01-01', '2024-01-31', '2024-02-05', 'paid', 5, 12500000.00, 4),
('Febrero 2024', '2024-02-01', '2024-02-29', '2024-03-05', 'closed', 5, 12500000.00, 4),
('Marzo 2024', '2024-03-01', '2024-03-31', '2024-04-05', 'processing', 5, 12500000.00, 4);

-- =============================================
-- INSERCIONES DE EVENTOS ESCOLARES
-- =============================================

-- Insertar eventos escolares
INSERT INTO school_events (event_type, event_title, event_description, start_date, end_date, event_location, school_id, created_by_user_id, is_active) VALUES
('Académico', 'Reunión de Padres', 'Reunión general de padres de familia para discutir el progreso académico', '2024-02-20 09:00:00', '2024-02-20 11:00:00', 'Auditorio Principal', 1, 2, 1),
('Deportivo', 'Torneo de Fútbol', 'Torneo intercolegial de fútbol masculino y femenino', '2024-02-22 14:00:00', '2024-02-22 18:00:00', 'Cancha Deportiva', 1, 2, 1),
('Cultural', 'Festival de Arte', 'Exposición de trabajos artísticos de los estudiantes', '2024-02-25 10:00:00', '2024-02-25 16:00:00', 'Galería de Arte', 1, 2, 1),
('Académico', 'Exámenes Finales', 'Período de exámenes finales del primer trimestre', '2024-03-28 08:00:00', '2024-03-30 17:00:00', 'Todas las aulas', 1, 2, 1),
('Recreativo', 'Día de la Familia', 'Actividades recreativas para toda la familia escolar', '2024-03-02 09:00:00', '2024-03-02 15:00:00', 'Patio Central', 1, 2, 1);

-- =============================================
-- INSERCIONES DE NOTIFICACIONES
-- =============================================

-- Insertar notificaciones de ejemplo
INSERT INTO notifications (recipient_user_id, notification_type, notification_title, notification_message, is_read) VALUES
(10, 'academic', 'Nueva Calificación', 'Se ha registrado una nueva calificación en Matemáticas', 0),
(11, 'academic', 'Nueva Calificación', 'Se ha registrado una nueva calificación en Español', 0),
(12, 'academic', 'Nueva Calificación', 'Se ha registrado una nueva calificación en Ciencias', 0),
(20, 'parent', 'Reunión de Padres', 'Recordatorio: Reunión de padres el próximo 20 de febrero', 0),
(21, 'parent', 'Pago Pendiente', 'Su hijo tiene un pago pendiente por realizar', 0),
(5, 'teacher', 'Nueva Actividad', 'Se ha creado una nueva actividad para su grupo', 0),
(6, 'teacher', 'Reunión Docentes', 'Reunión de docentes el viernes a las 3:00 PM', 0);

-- =============================================
-- INSERCIONES DE REPORTES ACADÉMICOS
-- =============================================

-- Insertar reportes académicos
INSERT INTO academic_reports (student_user_id, term_id, report_type, report_date, academic_average, conduct_grade, attendance_percentage, observations, created_by_user_id, is_active) VALUES
(10, 1, 'quarterly', '2024-02-15', 8.4, 'A', 95.5, 'Excelente rendimiento académico. Muy participativo en clase.', 5, 1),
(11, 1, 'quarterly', '2024-02-15', 8.8, 'A', 98.2, 'Destacado en todas las materias. Líder natural del grupo.', 5, 1),
(12, 1, 'quarterly', '2024-02-15', 7.6, 'B', 88.5, 'Buen rendimiento. Necesita mejorar en puntualidad.', 5, 1);

-- =============================================
-- INSERCIONES DE REPORTES DE CONDUCTA
-- =============================================

-- Insertar reportes de conducta
INSERT INTO conduct_reports (student_user_id, report_date, incident_type, incident_description, severity_level, disciplinary_action, reported_by_user_id, is_resolved) VALUES
(12, '2024-02-10', 'Falta de respeto', 'Interrumpió la clase varias veces', 'medium', 'Amonestación verbal y llamada a padres', 5, 1),
(15, '2024-02-12', 'Falta de puntualidad', 'Llegó 20 minutos tarde a clase', 'low', 'Advertencia y compromiso de mejora', 6, 1);

-- =============================================
-- INSERCIONES DE REUNIONES CON PADRES
-- =============================================

-- Insertar reuniones con padres
INSERT INTO parent_meetings (student_user_id, parent_user_id, meeting_date, meeting_type, meeting_subject, meeting_notes, scheduled_by_user_id, is_completed) VALUES
(12, 22, '2024-02-15 14:00:00', 'behavioral', 'Problemas de conducta', 'Discutir mejoras en comportamiento', 5, 0),
(15, 24, '2024-02-18 15:00:00', 'academic', 'Rendimiento académico', 'Revisar progreso en matemáticas', 5, 0);

-- =============================================
-- MENSAJE DE CONFIRMACIÓN
-- =============================================

SELECT 'Datos básicos ByFrost insertados correctamente' AS status; 