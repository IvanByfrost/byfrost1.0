-- Script para crear/actualizar tabla de eventos escolares
-- Ejecutar este script en la base de datos Byfrost

-- Verificar si la tabla event_school existe, si no, crearla
CREATE TABLE IF NOT EXISTS `event_school` (
    `event_id` int(11) NOT NULL AUTO_INCREMENT,
    `type_event` varchar(100) NOT NULL,
    `title_event` varchar(255) NOT NULL,
    `description_event` text,
    `start_date_event` datetime NOT NULL,
    `end_date_event` datetime DEFAULT NULL,
    `location_event` varchar(255) DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`event_id`),
    KEY `idx_start_date` (`start_date_event`),
    KEY `idx_type_event` (`type_event`),
    KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar eventos de ejemplo
INSERT IGNORE INTO `event_school` (`type_event`, `title_event`, `description_event`, `start_date_event`, `end_date_event`, `location_event`) VALUES
('Académico', 'Reunión de Padres', 'Reunión general de padres de familia para discutir el progreso académico', '2024-01-20 09:00:00', '2024-01-20 11:00:00', 'Auditorio Principal'),
('Deportivo', 'Torneo de Fútbol', 'Torneo intercolegial de fútbol masculino y femenino', '2024-01-22 14:00:00', '2024-01-22 18:00:00', 'Cancha Deportiva'),
('Cultural', 'Festival de Arte', 'Exposición de trabajos artísticos de los estudiantes', '2024-01-25 10:00:00', '2024-01-25 16:00:00', 'Galería de Arte'),
('Académico', 'Exámenes Finales', 'Período de exámenes finales del primer semestre', '2024-01-28 08:00:00', '2024-01-30 17:00:00', 'Todas las aulas'),
('Recreativo', 'Día de la Familia', 'Actividades recreativas para toda la familia escolar', '2024-02-02 09:00:00', '2024-02-02 15:00:00', 'Patio Central'),
('Académico', 'Conferencia de Ciencias', 'Conferencia sobre innovaciones en ciencias naturales', '2024-02-05 15:00:00', '2024-02-05 17:00:00', 'Laboratorio de Ciencias'),
('Deportivo', 'Clase de Natación', 'Clase especial de natación para estudiantes de primaria', '2024-02-08 10:00:00', '2024-02-08 12:00:00', 'Piscina Municipal'),
('Cultural', 'Concierto de Música', 'Presentación musical de la banda escolar', '2024-02-10 19:00:00', '2024-02-10 21:00:00', 'Auditorio Principal'),
('Académico', 'Feria de Proyectos', 'Exposición de proyectos de investigación estudiantil', '2024-02-12 09:00:00', '2024-02-12 16:00:00', 'Gimnasio'),
('Recreativo', 'Carnaval Escolar', 'Celebración del carnaval con disfraces y actividades', '2024-02-15 10:00:00', '2024-02-15 14:00:00', 'Patio Central');

-- Crear vista para eventos próximos
CREATE OR REPLACE VIEW `upcoming_events_view` AS
SELECT 
    event_id,
    type_event,
    title_event,
    description_event,
    start_date_event,
    end_date_event,
    location_event,
    DATEDIFF(start_date_event, NOW()) AS days_until,
    CASE 
        WHEN DATEDIFF(start_date_event, NOW()) = 0 THEN 'Hoy'
        WHEN DATEDIFF(start_date_event, NOW()) = 1 THEN 'Mañana'
        WHEN DATEDIFF(start_date_event, NOW()) <= 3 THEN 'Próximo'
        ELSE 'Futuro'
    END AS urgency_level
FROM event_school
WHERE start_date_event >= NOW() 
    AND is_active = 1
ORDER BY start_date_event ASC;

-- Crear vista para estadísticas de eventos
CREATE OR REPLACE VIEW `event_statistics_view` AS
SELECT 
    COUNT(*) AS total_events,
    COUNT(CASE WHEN DATE(start_date_event) = CURDATE() THEN 1 END) AS today_events,
    COUNT(CASE WHEN start_date_event BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) THEN 1 END) AS upcoming_events,
    COUNT(CASE WHEN start_date_event < NOW() THEN 1 END) AS past_events,
    COUNT(DISTINCT type_event) AS event_types,
    COUNT(CASE WHEN DATEDIFF(start_date_event, NOW()) <= 3 THEN 1 END) AS urgent_events
FROM event_school
WHERE is_active = 1;

-- Crear índices para optimizar consultas
CREATE INDEX IF NOT EXISTS `idx_event_date_range` ON `event_school` (`start_date_event`, `end_date_event`);
CREATE INDEX IF NOT EXISTS `idx_event_type_date` ON `event_school` (`type_event`, `start_date_event`);
CREATE INDEX IF NOT EXISTS `idx_event_active_date` ON `event_school` (`is_active`, `start_date_event`);

-- Mostrar mensaje de confirmación
SELECT 'Tabla de eventos escolares creada/actualizada correctamente' AS status; 