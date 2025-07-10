<?php
require_once ROOT . '/app/models/rootModel.php';
require_once ROOT . '/app/library/SessionManager.php';

/**
 * Clase para manejar permisos del sistema
 * Integra los permisos de la tabla role_permissions con el sistema de roles
 */
class PermissionManager {
    
    private $dbConn;
    private $sessionManager;
    private $rootModel;
    
    public function __construct($dbConn = null) {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($dbConn) {
            $this->dbConn = $dbConn;
        } else {
            require_once ROOT . '/app/scripts/connection.php';
            $this->dbConn = getConnection();
        }
        
        $this->sessionManager = new SessionManager();
        $this->rootModel = new RootModel();
    }
    
    /**
     * Obtiene los permisos efectivos del usuario actual
     * 
     * @return array Array con los permisos (can_create, can_read, can_update, can_delete)
     */
    public function getUserEffectivePermissions() {
        if (!$this->sessionManager->isLoggedIn()) {
            return $this->getDefaultDeniedPermissions();
        }
        
        $userRole = $this->sessionManager->getUserRole();
        if (!$userRole) {
            return $this->getDefaultDeniedPermissions();
        }
        
        // Obtener permisos del rol desde la tabla role_permissions
        $permissions = $this->rootModel->getPermissionsByRole($userRole);
        
        if (!$permissions) {
            return $this->getDefaultDeniedPermissions();
        }
        
        return [
            'can_create' => (bool)$permissions['can_create'],
            'can_read' => (bool)$permissions['can_read'],
            'can_update' => (bool)$permissions['can_update'],
            'can_delete' => (bool)$permissions['can_delete']
        ];
    }
    
    /**
     * Verifica si el usuario actual puede crear
     * 
     * @return bool True si puede crear
     */
    public function canCreate() {
        $permissions = $this->getUserEffectivePermissions();
        return $permissions['can_create'];
    }
    
    /**
     * Verifica si el usuario actual puede leer
     * 
     * @return bool True si puede leer
     */
    public function canRead() {
        $permissions = $this->getUserEffectivePermissions();
        return $permissions['can_read'];
    }
    
    /**
     * Verifica si el usuario actual puede actualizar
     * 
     * @return bool True si puede actualizar
     */
    public function canUpdate() {
        $permissions = $this->getUserEffectivePermissions();
        return $permissions['can_update'];
    }
    
    /**
     * Verifica si el usuario actual puede eliminar
     * 
     * @return bool True si puede eliminar
     */
    public function canDelete() {
        $permissions = $this->getUserEffectivePermissions();
        return $permissions['can_delete'];
    }
    
    /**
     * Verifica si el usuario tiene un permiso específico
     * 
     * @param string $permission Permiso a verificar (create, read, update, delete)
     * @return bool True si tiene el permiso
     */
    public function hasPermission($permission) {
        $permissions = $this->getUserEffectivePermissions();
        $permissionKey = 'can_' . strtolower($permission);
        
        return isset($permissions[$permissionKey]) && $permissions[$permissionKey];
    }
    
    /**
     * Verifica si el usuario tiene todos los permisos especificados
     * 
     * @param array $permissions Array de permisos a verificar
     * @return bool True si tiene todos los permisos
     */
    public function hasAllPermissions($permissions) {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Verifica si el usuario tiene al menos uno de los permisos especificados
     * 
     * @param array $permissions Array de permisos a verificar
     * @return bool True si tiene al menos uno de los permisos
     */
    public function hasAnyPermission($permissions) {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Obtiene permisos por defecto denegados
     * 
     * @return array Permisos denegados
     */
    private function getDefaultDeniedPermissions() {
        return [
            'can_create' => false,
            'can_read' => false,
            'can_update' => false,
            'can_delete' => false
        ];
    }
    
    /**
     * Obtiene información detallada de permisos del usuario actual
     * 
     * @return array Información detallada de permisos
     */
    public function getUserPermissionInfo() {
        $user = $this->sessionManager->getCurrentUser();
        $permissions = $this->getUserEffectivePermissions();
        
        return [
            'user_id' => $user['id'] ?? null,
            'user_email' => $user['email'] ?? null,
            'user_role' => $user['role'] ?? null,
            'is_logged_in' => $this->sessionManager->isLoggedIn(),
            'permissions' => $permissions,
            'can_create' => $permissions['can_create'],
            'can_read' => $permissions['can_read'],
            'can_update' => $permissions['can_update'],
            'can_delete' => $permissions['can_delete']
        ];
    }
    
    /**
     * Verifica permisos y redirige si no tiene acceso
     * 
     * @param string $permission Permiso requerido
     * @param string $redirectUrl URL de redirección si no tiene permisos
     * @return bool True si tiene permisos
     */
    public function requirePermission($permission, $redirectUrl = null) {
        if (!$this->hasPermission($permission)) {
            if ($redirectUrl) {
                header("Location: " . $redirectUrl);
            } else {
                header("Location: " . url . "?view=Error&action=unauthorized");
            }
            exit;
        }
        return true;
    }
    
    /**
     * Verifica permisos y devuelve respuesta JSON si es AJAX
     * 
     * @param string $permission Permiso requerido
     * @return bool True si tiene permisos
     */
    public function requirePermissionAjax($permission) {
        if (!$this->hasPermission($permission)) {
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'No tienes permisos para realizar esta acción',
                    'permission_required' => $permission
                ]);
                exit;
            } else {
                header("Location: " . url . "?view=Error&action=unauthorized");
                exit;
            }
        }
        return true;
    }
    
    /**
     * Verifica si la petición es AJAX
     * 
     * @return bool True si es AJAX
     */
    private function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
?> 