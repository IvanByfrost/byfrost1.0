<?php
require_once 'mainModel.php';

class RootModel extends mainModel
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
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Obtiene los permisos actuales asignados a un role_type
     */
    public function getPermissionsByRole($role_type) {
        $sql = "SELECT * FROM role_permissions WHERE role_type = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$role_type]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no existen permisos


}
