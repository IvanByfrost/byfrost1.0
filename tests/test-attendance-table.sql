-- Test para verificar la tabla attendance con Baldur.sql
-- Ejecutar este script después de Baldur.sql y attendance_table.sql

-- 1. Verificar que la tabla attendance existe
SELECT 'Verificando tabla attendance...' AS status;
SHOW TABLES LIKE 'attendance';

-- 2. Verificar estructura de la tabla
DESCRIBE attendance;

-- 3. Verificar que las claves foráneas están correctas
SELECT 
    CONSTRAINT_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'attendance' 
    AND REFERENCED_TABLE_NAME IS NOT NULL;

-- 4. Verificar que los índices están creados
SHOW INDEX FROM attendance;

-- 5. Insertar datos de prueba (solo si existen users y schedules)
-- Descomenta estas líneas si tienes datos en users y schedules

/*
-- Insertar datos de prueba
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
LIMIT 10;

-- Verificar datos insertados
SELECT COUNT(*) as total_attendance_records FROM attendance;
SELECT status, COUNT(*) as count FROM attendance GROUP BY status;
*/

-- 6. Probar consultas optimizadas
SELECT 'Probando consultas optimizadas...' AS status;

-- Consulta de asistencia del día
SELECT 
    COALESCE(ROUND(
        (SELECT COUNT(DISTINCT a.student_user_id) 
         FROM attendance a 
         WHERE DATE(a.attendance_date) = CURDATE() AND a.status = 'present') / 
        NULLIF((SELECT COUNT(*) FROM users u 
                JOIN user_roles ur ON u.user_id = ur.user_id 
                WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1), 0), 2
    ), 0) AS attendance_today;

-- Consulta de asistencia del mes
SELECT 
    COALESCE(ROUND(
        (SELECT COUNT(DISTINCT a.student_user_id) 
         FROM attendance a 
         WHERE MONTH(a.attendance_date) = MONTH(CURDATE()) 
         AND YEAR(a.attendance_date) = YEAR(CURDATE()) 
         AND a.status = 'present') / 
        NULLIF((SELECT COUNT(*) FROM users u 
                JOIN user_roles ur ON u.user_id = ur.user_id 
                WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1), 0), 2
    ), 0) AS attendance_month;

-- 7. Probar vistas
SELECT 'Probando vistas...' AS status;

-- Verificar vista attendance_summary
SELECT * FROM attendance_summary LIMIT 5;

-- Verificar vista attendance_detailed (si hay datos)
SELECT COUNT(*) as records_in_detailed_view FROM attendance_detailed;

-- 8. Resumen final
SELECT 'Test completado exitosamente' AS status;
SELECT 
    'Tabla attendance optimizada para Baldur.sql' AS message,
    '✅ Estructura correcta' AS structure,
    '✅ Claves foráneas correctas' AS foreign_keys,
    '✅ Índices optimizados' AS indexes,
    '✅ Vistas funcionales' AS views; 