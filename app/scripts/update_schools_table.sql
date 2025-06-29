-- Script para actualizar la tabla schools
-- Permite que coordinator_user_id sea NULL

-- Modificar la columna coordinator_user_id para permitir NULL
ALTER TABLE schools MODIFY COLUMN coordinator_user_id INT NULL;

-- Agregar comentario explicativo
ALTER TABLE schools MODIFY COLUMN coordinator_user_id INT NULL COMMENT 'ID del coordinador (opcional)';

-- Verificar que la modificación fue exitosa
DESCRIBE schools; 