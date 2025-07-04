<?php
/**
 * SessionManager - Gestión de sesiones de usuario
 * 
 * Esta clase maneja las sesiones de usuario de forma segura y eficiente.
 * Proporciona métodos para autenticación, verificación de roles y gestión de datos de sesión.
 */
class SessionManager {
    private $sessionStarted = false;
    
    /**
     * Constructor - NO inicia la sesión automáticamente
     */
    public function __construct() {
        // No iniciar sesión aquí - esto causaba el error
    }
    
    /**
     * Inicia la sesión de forma segura
     * 
     * @return bool True si la sesión se inició correctamente
     */
    private function startSessionSafely() {
        // Si ya está iniciada, no hacer nada
        if ($this->sessionStarted || session_status() === PHP_SESSION_ACTIVE) {
            return true;
        }
        
        // Verificar si ya se enviaron headers
        if (headers_sent($file, $line)) {
            error_log("SessionManager Error: No se puede iniciar sesión - headers ya enviados en {$file} línea {$line}");
            return false;
        }
        
        try {
            if (session_start()) {
                $this->sessionStarted = true;
                return true;
            }
        } catch (Exception $e) {
            error_log("SessionManager Error: " . $e->getMessage());
            return false;
        }
        
        return false;
    }
    
    /**
     * Inicia una sesión de usuario
     * 
     * @param array $userData Datos del usuario (id, email, role, etc.)
     * @return bool True si la sesión se inició correctamente
     */
    public function login($userData) {
        try {
            // Iniciar sesión de forma segura ANTES de usarla
            if (!$this->startSessionSafely()) {
                return false;
            }
            
            // Validar datos del usuario
            if (empty($userData['id']) || empty($userData['email'])) {
                return false;
            }
            
            // Establecer datos de sesión
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_email'] = $userData['email'];
            $_SESSION['user_role'] = $userData['role'] ?? 'user';
            $_SESSION['user_name'] = $userData['first_name'] ?? '';
            $_SESSION['user_lastname'] = $userData['last_name'] ?? '';
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();
            
            // Regenerar ID de sesión para seguridad
            session_regenerate_id(true);
            
            return true;
        } catch (Exception $e) {
            error_log("Error en SessionManager::login: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verifica si el usuario está logueado
     * 
     * @return bool True si el usuario está logueado
     */
    public function isLoggedIn() {
        if (!$this->startSessionSafely()) {
            return false;
        }
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Obtiene el ID del usuario actual
     * 
     * @return int|null ID del usuario o null si no está logueado
     */
    public function getUserId() {
        if (!$this->startSessionSafely()) {
            return null;
        }
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Obtiene el email del usuario actual
     * 
     * @return string|null Email del usuario o null si no está logueado
     */
    public function getUserEmail() {
        if (!$this->startSessionSafely()) {
            return null;
        }
        return $_SESSION['user_email'] ?? null;
    }
    
    /**
     * Obtiene el rol del usuario actual
     * 
     * @return string|null Rol del usuario o null si no está logueado
     */
    public function getUserRole() {
        if (!$this->startSessionSafely()) {
            return null;
        }
        return $_SESSION['user_role'] ?? null;
    }
    
    /**
     * Obtiene el nombre completo del usuario
     * 
     * @return string Nombre completo del usuario
     */
    public function getUserFullName() {
        if (!$this->startSessionSafely()) {
            return '';
        }
        $firstName = $_SESSION['user_name'] ?? '';
        $lastName = $_SESSION['user_lastname'] ?? '';
        return trim($firstName . ' ' . $lastName);
    }
    
    /**
     * Obtiene todos los datos del usuario actual
     * 
     * @return array Datos del usuario o array vacío si no está logueado
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return [];
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role'],
            'name' => $_SESSION['user_name'],
            'lastname' => $_SESSION['user_lastname'],
            'full_name' => $this->getUserFullName(),
            'login_time' => $_SESSION['login_time'] ?? null
        ];
    }
    
    /**
     * Verifica si el usuario tiene un rol específico o uno de varios roles (insensible a mayúsculas)
     * 
     * @param string|array $role Rol o array de roles a verificar
     * @return bool True si el usuario tiene el rol
     */
    public function hasRole($role) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        $userRole = strtolower($_SESSION['user_role']);
        if (is_array($role)) {
            return in_array($userRole, array_map('strtolower', $role));
        }
        return $userRole === strtolower($role);
    }
    
    /**
     * Verifica si el usuario tiene uno de los roles especificados
     * 
     * @param array $roles Array de roles permitidos
     * @return bool True si el usuario tiene uno de los roles
     */
    public function hasAnyRole($roles) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        return in_array($_SESSION['user_role'], $roles);
    }
    
    /**
     * Establece un dato en la sesión
     * 
     * @param string $key Clave del dato
     * @param mixed $value Valor del dato
     */
    public function setSessionData($key, $value) {
        if ($this->startSessionSafely()) {
            $_SESSION[$key] = $value;
        }
    }
    
    /**
     * Obtiene un dato de la sesión
     * 
     * @param string $key Clave del dato
     * @param mixed $default Valor por defecto si no existe
     * @return mixed Valor del dato o el valor por defecto
     */
    public function getSessionData($key, $default = null) {
        if (!$this->startSessionSafely()) {
            return $default;
        }
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Elimina un dato específico de la sesión
     * 
     * @param string $key Clave del dato a eliminar
     */
    public function removeSessionData($key) {
        if ($this->startSessionSafely() && isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Cierra la sesión del usuario
     */
    public function logout() {
        if (!$this->startSessionSafely()) {
            return;
        }
        
        // Limpiar todos los datos de sesión
        $_SESSION = array();
        
        // Destruir la cookie de sesión si existe
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destruir la sesión
        session_destroy();
        $this->sessionStarted = false;
    }
    
    /**
     * Verifica si la sesión ha expirado
     * 
     * @param int $timeout Tiempo de expiración en segundos (por defecto 30 minutos)
     * @return bool True si la sesión ha expirado
     */
    public function isSessionExpired($timeout = 1800) {
        if (!$this->isLoggedIn()) {
            return true;
        }
        
        $loginTime = $_SESSION['login_time'] ?? 0;
        return (time() - $loginTime) > $timeout;
    }
    
    /**
     * Renueva la sesión (actualiza el tiempo de login)
     */
    public function renewSession() {
        if ($this->isLoggedIn()) {
            $_SESSION['login_time'] = time();
        }
    }
}
?>