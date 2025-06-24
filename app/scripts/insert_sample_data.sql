-- =============================================
-- SCRIPT PARA INSERTAR DATOS DE EJEMPLO
-- Basado en la estructura de Baldur.sql
-- =============================================
-- Insertar usuarios de ejemplo
INSERT INTO users (
        credential_number,
        first_name,
        last_name,
        credential_type,
        date_of_birth,
        address,
        phone,
        email,
        password_hash,
        salt_password,
        is_active
    )
VALUES -- Administradores
    (
        'ADMIN001',
        'Admin',
        'Sistema',
        'CC',
        '1980-01-01',
        'Calle Admin 123',
        '555-0001',
        'admin@sistema.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    -- Rectores
    (
        'RECTOR001',
        'María',
        'González',
        'CC',
        '1975-05-15',
        'Calle Rector 456',
        '555-0002',
        'rectora@colegio.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    -- Coordinadores
    (
        'COORD001',
        'Carlos',
        'Rodríguez',
        'CC',
        '1982-03-20',
        'Calle Coord 789',
        '555-0003',
        'coordinador@colegio.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    -- Profesores
    (
        'PROF001',
        'Ana',
        'Martínez',
        'CC',
        '1985-07-10',
        'Calle Prof 101',
        '555-0004',
        'ana.martinez@colegio.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    (
        'PROF002',
        'Luis',
        'Hernández',
        'CC',
        '1983-11-25',
        'Calle Prof 102',
        '555-0005',
        'luis.hernandez@colegio.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    (
        'PROF003',
        'Carmen',
        'López',
        'CC',
        '1987-09-08',
        'Calle Prof 103',
        '555-0006',
        'carmen.lopez@colegio.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    (
        'PROF004',
        'Roberto',
        'Díaz',
        'CC',
        '1981-12-03',
        'Calle Prof 104',
        '555-0007',
        'roberto.diaz@colegio.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    -- Estudiantes
    (
        'EST001',
        'Juan',
        'Pérez',
        'TI',
        '2010-04-15',
        'Calle Est 201',
        '555-0008',
        'juan.perez@estudiante.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    (
        'EST002',
        'María',
        'García',
        'TI',
        '2010-06-22',
        'Calle Est 202',
        '555-0009',
        'maria.garcia@estudiante.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    (
        'EST003',
        'Carlos',
        'López',
        'TI',
        '2010-08-10',
        'Calle Est 203',
        '555-0010',
        'carlos.lopez@estudiante.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    (
        'EST004',
        'Ana',
        'Rodríguez',
        'TI',
        '2010-02-28',
        'Calle Est 204',
        '555-0011',
        'ana.rodriguez@estudiante.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    (
        'EST005',
        'Luis',
        'Martínez',
        'TI',
        '2010-10-12',
        'Calle Est 205',
        '555-0012',
        'luis.martinez@estudiante.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    -- Padres
    (
        'PAD001',
        'Pedro',
        'Pérez',
        'CC',
        '1980-03-15',
        'Calle Padre 301',
        '555-0013',
        'pedro.perez@padre.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    (
        'PAD002',
        'Rosa',
        'García',
        'CC',
        '1982-07-20',
        'Calle Padre 302',
        '555-0014',
        'rosa.garcia@padre.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    ),
    (
        'PAD003',
        'Miguel',
        'López',
        'CC',
        '1978-11-08',
        'Calle Padre 303',
        '555-0015',
        'miguel.lopez@padre.edu',
        UNHEX('736563726574'),
        UNHEX('73616c74'),
        1
    );
-- Insertar roles de usuario
INSERT INTO user_roles (user_id, role_type, is_active)
VALUES -- Administrador
    (1, 'headmaster', 1),
    -- Rector
    (2, 'headmaster', 1),
    -- Coordinador
    (3, 'coordinator', 1),
    -- Profesores
    (4, 'professor', 1),
    (5, 'professor', 1),
    (6, 'professor', 1),
    (7, 'professor', 1),
    -- Estudiantes
    (8, 'student', 1),
    (9, 'student', 1),
    (10, 'student', 1),
    (11, 'student', 1),
    (12, 'student', 1),
    -- Padres
    (13, 'parent', 1),
    (14, 'parent', 1),
    (15, 'parent', 1);
-- Insertar escuelas
INSERT INTO schools (
        school_name,
        total_quota,
        headmaster_user_id,
        coordinator_user_id,
        address,
        phone,
        email,
        is_active
    )
VALUES (
        'Colegio San José - Sede Principal',
        500,
        2,
        3,
        'Calle Principal 123, Bogotá',
        '555-0100',
        'info@colegiosanjose.edu',
        1
    ),
    (
        'Colegio San José - Sede Norte',
        300,
        2,
        3,
        'Avenida Norte 456, Bogotá',
        '555-0101',
        'norte@colegiosanjose.edu',
        1
    );
-- Insertar grados
INSERT INTO grades (grade_name, grade_level, school_id)
VALUES -- Primaria
    ('1ro', 'Primaria', 1),
    ('2do', 'Primaria', 1),
    ('3ro', 'Primaria', 1),
    ('4to', 'Primaria', 1),
    ('5to', 'Primaria', 1),
    -- Secundaria
    ('6to', 'Secundaria', 1),
    ('7mo', 'Secundaria', 1),
    ('8vo', 'Secundaria', 1),
    ('9no', 'Secundaria', 1),
    ('10mo', 'Secundaria', 1),
    ('11mo', 'Secundaria', 1);
-- Insertar materias de profesor
INSERT INTO professor_subjects (
        professor_user_id,
        subject_id,
        school_id,
        is_active
    )
VALUES -- Profesor 1 - Ana Martínez
    (4, 1, 1, 1),
    -- Matemáticas
    (4, 5, 1, 1),
    -- Inglés
    -- Profesor 2 - Luis Hernández
    (5, 2, 1, 1),
    -- Español
    (5, 3, 1, 1),
    -- Ciencias Naturales
    -- Profesor 3 - Carmen López
    (6, 4, 1, 1),
    -- Ciencias Sociales
    (6, 6, 1, 1),
    -- Educación Física
    -- Profesor 4 - Roberto Díaz
    (7, 7, 1, 1),
    -- Artes
    (7, 8, 1, 1);
-- Informática
-- Insertar grupos de clase
INSERT INTO class_groups (
        group_name,
        grade_id,
        professor_user_id,
        max_students,
        classroom,
        school_year
    )
VALUES ('A', 6, 4, 30, 'Aula 601', 2024),
    ('B', 6, 5, 30, 'Aula 602', 2024),
    ('A', 7, 6, 30, 'Aula 701', 2024),
    ('B', 7, 7, 30, 'Aula 702', 2024),
    ('A', 8, 4, 30, 'Aula 801', 2024),
    ('B', 8, 5, 30, 'Aula 802', 2024);
-- Insertar matrículas de estudiantes
INSERT INTO student_enrollment (
        student_user_id,
        class_group_id,
        enrollment_date,
        is_active,
        student_code
    )
VALUES (8, 1, '2024-01-15', 1, 'EST2024001'),
    (9, 1, '2024-01-15', 1, 'EST2024002'),
    (10, 2, '2024-01-15', 1, 'EST2024003'),
    (11, 2, '2024-01-15', 1, 'EST2024004'),
    (12, 3, '2024-01-15', 1, 'EST2024005');
-- Insertar relaciones padre-estudiante
INSERT INTO student_parents (
        student_user_id,
        parent_user_id,
        relationship_type,
        is_primary_contact
    )
VALUES (8, 13, 'Padre', 1),
    (9, 14, 'Madre', 1),
    (10, 15, 'Padre', 1),
    (11, 13, 'Padre', 0),
    (12, 14, 'Madre', 0);
-- Insertar términos académicos adicionales
INSERT INTO academic_terms (term_name, start_date, end_date, school_year)
VALUES (
        'Cuarto Período',
        '2024-12-01',
        '2024-12-15',
        2024
    );
-- Insertar horarios de ejemplo
INSERT INTO schedules (
        class_group_id,
        professor_subject_id,
        day_of_week,
        start_time,
        end_time,
        term_id
    )
VALUES -- 6to A - Lunes
    (1, 1, 1, '07:00:00', '08:30:00', 1),
    -- Matemáticas
    (1, 2, 1, '08:30:00', '10:00:00', 1),
    -- Español
    (1, 3, 1, '10:30:00', '12:00:00', 1),
    -- Ciencias Naturales
    -- 6to A - Martes
    (1, 4, 2, '07:00:00', '08:30:00', 1),
    -- Ciencias Sociales
    (1, 5, 2, '08:30:00', '10:00:00', 1),
    -- Inglés
    (1, 6, 2, '10:30:00', '12:00:00', 1);
-- Educación Física
-- Insertar tipos de actividad adicionales
INSERT INTO activity_types (type_name, weight_percentage)
VALUES ('Presentación', 15.00),
    ('Laboratorio', 20.00),
    ('Trabajo en clase', 10.00);
-- Insertar actividades de ejemplo
INSERT INTO activities (
        activity_name,
        professor_subject_id,
        activity_type_id,
        class_group_id,
        term_id,
        max_score,
        due_date,
        description,
        created_by_user_id
    )
VALUES -- Actividades de Matemáticas
    (
        'Tarea: Números Enteros',
        1,
        3,
        1,
        1,
        10.0,
        '2024-03-15 23:59:00',
        'Resolver ejercicios de la página 45 del libro de texto',
        4
    ),
    (
        'Examen: Fracciones',
        1,
        1,
        1,
        1,
        20.0,
        '2024-03-20 14:00:00',
        'Examen sobre fracciones y operaciones básicas',
        4
    ),
    (
        'Quiz: Geometría Básica',
        1,
        2,
        1,
        1,
        15.0,
        '2024-03-18 10:00:00',
        'Evaluación corta sobre figuras geométricas',
        4
    ),
    -- Actividades de Español
    (
        'Ensayo: Mi Familia',
        3,
        3,
        2,
        1,
        25.0,
        '2024-03-25 23:59:00',
        'Escribir un ensayo de 2 páginas sobre la familia',
        5
    ),
    (
        'Presentación: Poemas',
        3,
        6,
        2,
        1,
        20.0,
        '2024-03-22 14:00:00',
        'Presentar un poema de memoria ante la clase',
        5
    ),
    -- Actividades de Ciencias
    (
        'Proyecto: Ecosistemas',
        4,
        4,
        3,
        1,
        30.0,
        '2024-03-28 23:59:00',
        'Investigación sobre ecosistemas locales',
        6
    ),
    (
        'Laboratorio: Plantas',
        4,
        7,
        3,
        1,
        25.0,
        '2024-03-30 16:00:00',
        'Experimento de germinación de semillas',
        6
    ),
    -- Actividades de Inglés
    (
        'Quiz: Verbos Irregulares',
        2,
        2,
        1,
        1,
        15.0,
        '2024-03-18 10:00:00',
        'Evaluación sobre verbos irregulares en presente',
        4
    ),
    (
        'Conversación: Mi Rutina',
        2,
        6,
        1,
        1,
        20.0,
        '2024-03-24 14:00:00',
        'Presentación oral sobre rutina diaria',
        4
    );
-- Insertar algunas calificaciones de ejemplo
INSERT INTO student_scores (
        student_user_id,
        activity_id,
        score,
        feedback,
        graded_by_user_id
    )
VALUES (
        8,
        1,
        9.5,
        'Excelente trabajo, muy bien organizado',
        4
    ),
    (
        9,
        1,
        8.0,
        'Buen trabajo, revisar algunos cálculos',
        4
    ),
    (8, 2, 18.5, 'Muy buen desempeño en el examen', 4),
    (
        9,
        2,
        16.0,
        'Aceptable, necesita practicar más',
        4
    ),
    (10, 4, 22.0, 'Ensayo muy bien estructurado', 5),
    (
        11,
        4,
        20.5,
        'Buen contenido, mejorar ortografía',
        5
    );
-- Insertar asistencias de ejemplo
INSERT INTO attendance (
        student_user_id,
        schedule_id,
        attendance_date,
        status,
        notes,
        recorded_by_user_id
    )
VALUES (8, 1, '2024-03-11', 'present', NULL, 4),
    (9, 1, '2024-03-11', 'present', NULL, 4),
    (
        8,
        1,
        '2024-03-12',
        'late',
        'Llegó 10 minutos tarde',
        4
    ),
    (
        9,
        1,
        '2024-03-12',
        'absent',
        'Ausencia justificada',
        4
    );
-- Insertar eventos escolares
INSERT INTO school_events (
        school_id,
        event_name,
        description,
        start_datetime,
        end_datetime,
        event_type,
        created_by_user_id
    )
VALUES (
        1,
        'Día del Estudiante',
        'Celebración del día del estudiante con actividades recreativas',
        '2024-06-15 08:00:00',
        '2024-06-15 16:00:00',
        'Celebración',
        2
    ),
    (
        1,
        'Reunión de Padres',
        'Reunión general de padres de familia',
        '2024-03-20 18:00:00',
        '2024-03-20 20:00:00',
        'Reunión',
        3
    ),
    (
        1,
        'Feria de Ciencias',
        'Exposición de proyectos científicos de los estudiantes',
        '2024-04-10 09:00:00',
        '2024-04-10 17:00:00',
        'Académico',
        3
    );
-- Insertar notificaciones de ejemplo
INSERT INTO notifications (
        recipient_user_id,
        title,
        message,
        notification_type,
        is_read
    )
VALUES (
        8,
        'Nueva Tarea',
        'Se ha asignado una nueva tarea de Matemáticas',
        'academic',
        0
    ),
    (
        9,
        'Nueva Tarea',
        'Se ha asignado una nueva tarea de Matemáticas',
        'academic',
        0
    ),
    (
        13,
        'Calificación Publicada',
        'Su hijo Juan Pérez tiene una nueva calificación',
        'academic',
        0
    ),
    (
        14,
        'Calificación Publicada',
        'Su hija María García tiene una nueva calificación',
        'academic',
        0
    );
-- Mensaje de confirmación
SELECT 'Datos de ejemplo insertados correctamente' as mensaje;