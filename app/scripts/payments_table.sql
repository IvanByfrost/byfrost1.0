-- Script para crear/actualizar tabla de pagos estudiantiles
-- Ejecutar este script en la base de datos Byfrost

-- Verificar si la tabla student_account existe, si no, crearla
CREATE TABLE IF NOT EXISTS `student_account` (
    `payment_id` int(11) NOT NULL AUTO_INCREMENT,
    `student_id` int(11) NOT NULL,
    `tuition_amount` decimal(10,2) NOT NULL,
    `tuition_status` enum('pendiente','pagado','atrasado','parcial') DEFAULT 'pendiente',
    `payment_due_date` date NOT NULL,
    `payment_date` datetime DEFAULT NULL,
    `payment_method` varchar(50) DEFAULT NULL,
    `payment_notes` text,
    `last_payment_date` datetime DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`payment_id`),
    KEY `student_id` (`student_id`),
    KEY `tuition_status` (`tuition_status`),
    KEY `payment_due_date` (`payment_due_date`),
    KEY `is_active` (`is_active`),
    CONSTRAINT `fk_student_account_student` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de ejemplo para pagos
INSERT IGNORE INTO `student_account` (`student_id`, `tuition_amount`, `tuition_status`, `payment_due_date`, `payment_date`, `payment_method`, `payment_notes`) VALUES
(1, 500.00, 'pagado', '2024-01-15', '2024-01-14 10:30:00', 'Transferencia', 'Pago completo realizado'),
(2, 500.00, 'pagado', '2024-01-15', '2024-01-13 15:45:00', 'Efectivo', 'Pago en oficina'),
(3, 500.00, 'pendiente', '2024-01-15', NULL, NULL, 'Pendiente de pago'),
(4, 500.00, 'pagado', '2024-01-15', '2024-01-12 09:20:00', 'Tarjeta', 'Pago con tarjeta de crédito'),
(5, 500.00, 'atrasado', '2024-01-15', NULL, NULL, 'Pago atrasado'),
(6, 500.00, 'pagado', '2024-01-15', '2024-01-11 14:15:00', 'Transferencia', 'Pago realizado'),
(7, 500.00, 'pendiente', '2024-01-15', NULL, NULL, 'Esperando confirmación'),
(8, 500.00, 'pagado', '2024-01-15', '2024-01-10 11:30:00', 'Efectivo', 'Pago en efectivo'),
(9, 500.00, 'parcial', '2024-01-15', '2024-01-09 16:20:00', 'Transferencia', 'Pago parcial de 300.00'),
(10, 500.00, 'atrasado', '2024-01-15', NULL, NULL, 'Pago vencido hace 5 días');

-- Crear vista para estadísticas de pagos
CREATE OR REPLACE VIEW `payment_statistics_view` AS
SELECT 
    COUNT(*) AS total_accounts,
    SUM(CASE WHEN tuition_status = 'pendiente' THEN 1 ELSE 0 END) AS pending_payments,
    SUM(CASE WHEN tuition_status = 'pagado' THEN 1 ELSE 0 END) AS completed_payments,
    SUM(CASE WHEN tuition_status = 'atrasado' THEN 1 ELSE 0 END) AS overdue_payments,
    SUM(CASE WHEN tuition_status = 'parcial' THEN 1 ELSE 0 END) AS partial_payments,
    ROUND(
        (SUM(CASE WHEN tuition_status = 'pagado' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1
    ) AS completion_rate
FROM student_account
WHERE is_active = 1;

-- Crear vista para resumen de ingresos
CREATE OR REPLACE VIEW `revenue_summary_view` AS
SELECT 
    SUM(tuition_amount) AS total_revenue,
    SUM(CASE WHEN tuition_status = 'pagado' THEN tuition_amount ELSE 0 END) AS collected_revenue,
    SUM(CASE WHEN tuition_status IN ('pendiente', 'atrasado') THEN tuition_amount ELSE 0 END) AS pending_revenue,
    ROUND(
        (SUM(CASE WHEN tuition_status = 'pagado' THEN tuition_amount ELSE 0 END) / SUM(tuition_amount)) * 100, 1
    ) AS collection_rate
FROM student_account
WHERE is_active = 1;

-- Crear vista para pagos atrasados
CREATE OR REPLACE VIEW `overdue_payments_view` AS
SELECT 
    sa.payment_id,
    sa.student_id,
    s.student_name,
    s.email,
    sa.tuition_amount,
    sa.payment_due_date,
    sa.tuition_status,
    DATEDIFF(CURDATE(), sa.payment_due_date) AS days_overdue,
    sa.last_payment_date,
    sa.payment_notes
FROM student_account sa
JOIN student s ON sa.student_id = s.student_id
WHERE sa.tuition_status IN ('pendiente', 'atrasado')
    AND sa.payment_due_date < CURDATE()
    AND sa.is_active = 1
ORDER BY days_overdue DESC;

-- Crear índices para optimizar consultas
CREATE INDEX IF NOT EXISTS `idx_payment_status_date` ON `student_account` (`tuition_status`, `payment_due_date`);
CREATE INDEX IF NOT EXISTS `idx_payment_method` ON `student_account` (`payment_method`);
CREATE INDEX IF NOT EXISTS `idx_payment_date` ON `student_account` (`payment_date`);
CREATE INDEX IF NOT EXISTS `idx_payment_active_status` ON `student_account` (`is_active`, `tuition_status`);

-- Mostrar mensaje de confirmación
SELECT 'Tabla de pagos estudiantiles creada/actualizada correctamente' AS status; 