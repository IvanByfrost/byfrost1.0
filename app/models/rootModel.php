<?php
require_once 'mainModel.php';

class RootModel extends MainModel
{
    //Constructor de la clase.
    public function __construct()
    {
        parent::__construct();
    }

    //Función para obtener los datos del usuario.
    //Función para insertar los datos del usuario.
    //Función para actualizar los datos del usuario.
    //Función para eliminar los datos del usuario.

    public function getAllRoleTypes() {
        $sql = "SELECT DISTINCT role_type FROM user_roles ORDER BY role_type ASC";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Obtiene los permisos actuales asignados a un role_type
     */
    public function getPermissionsByRole($role_type) {
        $sql = "SELECT * FROM role_permissions WHERE role_type = ?";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute([$role_type]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no existen permisos para este rol, crear unos por defecto
        if (!$result) {
            $defaultPermissions = $this->getDefaultPermissions($role_type);
            $this->insertDefaultPermissions($role_type, $defaultPermissions);
            return $defaultPermissions;
        }

        return $result;
    }

    /**
     * Actualiza los permisos de un role_type
     */
    public function updatePermissions($role_type, $data) {
        // Verificar si existen permisos para este rol
        $sql = "SELECT COUNT(*) FROM role_permissions WHERE role_type = ?";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute([$role_type]);
        $exists = $stmt->fetchColumn() > 0;
        
        if ($exists) {
            // Actualizar permisos existentes
            $sql = "UPDATE role_permissions SET 
                    can_create = ?, 
                    can_read = ?, 
                    can_update = ?, 
                    can_delete = ? 
                    WHERE role_type = ?";
            $stmt = $this->dbConn->prepare($sql);
            return $stmt->execute([
                $data['can_create'],
                $data['can_read'],
                $data['can_update'],
                $data['can_delete'],
                $role_type
            ]);
        } else {
            // Crear nuevos permisos
            $sql = "INSERT INTO role_permissions (role_type, can_create, can_read, can_update, can_delete) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->dbConn->prepare($sql);
            return $stmt->execute([
                $role_type,
                $data['can_create'],
                $data['can_read'],
                $data['can_update'],
                $data['can_delete']
            ]);
        }
    }

    /**
     * Obtiene los permisos por defecto para un role_type
     */
    private function getDefaultPermissions($role_type) {
        $defaultPermissions = [
            'student' => ['can_create' => 0, 'can_read' => 1, 'can_update' => 0, 'can_delete' => 0],
            'parent' => ['can_create' => 0, 'can_read' => 1, 'can_update' => 0, 'can_delete' => 0],
            'professor' => ['can_create' => 1, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 0],
            'coordinator' => ['can_create' => 1, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 1],
            'director' => ['can_create' => 1, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 1],
            'treasurer' => ['can_create' => 1, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 0],
            'root' => ['can_create' => 1, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 1]
        ];

        $permissions = $defaultPermissions[$role_type] ?? ['can_create' => 0, 'can_read' => 1, 'can_update' => 0, 'can_delete' => 0];
        
        return [
            'role_type' => $role_type,
            'can_create' => $permissions['can_create'],
            'can_read' => $permissions['can_read'],
            'can_update' => $permissions['can_update'],
            'can_delete' => $permissions['can_delete']
        ];
    }

    /**
     * Inserta permisos por defecto directamente en la base de datos
     */
    private function insertDefaultPermissions($role_type, $permissions) {
        $sql = "INSERT INTO role_permissions (role_type, can_create, can_read, can_update, can_delete) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->dbConn->prepare($sql);
        return $stmt->execute([
            $role_type,
            $permissions['can_create'],
            $permissions['can_read'],
            $permissions['can_update'],
            $permissions['can_delete']
        ]);
    }
}
