-- Script para insertar permisos por defecto para todos los roles
-- Ejecutar después de crear la tabla role_permissions

INSERT INTO role_permissions (role_type, can_create, can_read, can_update, can_delete) VALUES
('student', 0, 1, 0, 0),
('parent', 0, 1, 0, 0),
('professor', 1, 1, 1, 0),
('coordinator', 1, 1, 1, 1),
('director', 1, 1, 1, 1),
('treasurer', 1, 1, 1, 0),
('root', 1, 1, 1, 1)
ON DUPLICATE KEY UPDATE
can_create = VALUES(can_create),
can_read = VALUES(can_read),
can_update = VALUES(can_update),
can_delete = VALUES(can_delete);

-- Verificar que se insertaron correctamente
SELECT * FROM role_permissions ORDER BY role_type; 