<?php
/**
 * SessionMiddleware - Middleware para gestión de sesiones
 * 
 * Este middleware se ejecuta antes de cualquier controlador para asegurar
 * que la sesión esté correctamente configurada y mantenida.
 */
class SessionMiddleware {
    
    /**
     * Ejecuta el middleware de sesión
     * 
     * @param callable $next Función a ejecutar después del middleware
     * @return mixed Resultado de la función next
     */
    public static function handle($next) {
        // Iniciar sesión de forma segura
        self::ensureSessionStarted();
        
        // Verificar si la sesión está activa
        if (!self::isSessionActive()) {
            error_log("SessionMiddleware: Sesión no activa");
            return self::handleSessionError();
        }
        
        // Verificar si la sesión ha expirado
        if (self::isSessionExpired()) {
            error_log("SessionMiddleware: Sesión expirada");
            return self::handleSessionExpired();
        }
        
        // Renovar la sesión si es necesario
        self::renewSessionIfNeeded();
        
        // Ejecutar la función siguiente
        return $next();
    }
    
    /**
     * Asegura que la sesión esté iniciada
     */
    private static function ensureSessionStarted() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            // Verificar si ya se enviaron headers
            if (headers_sent($file, $line)) {
                error_log("SessionMiddleware Error: Headers ya enviados en {$file} línea {$line}");
                return false;
            }
            
            try {
                // Configurar opciones de sesión
                ini_set('session.cookie_httponly', 1);
                ini_set('session.use_only_cookies', 1);
                ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS
                
                if (session_start()) {
                    error_log("SessionMiddleware: Sesión iniciada correctamente");
                    return true;
                }
            } catch (Exception $e) {
                error_log("SessionMiddleware Error: " . $e->getMessage());
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Verifica si la sesión está activa
     */
    private static function isSessionActive() {
        return session_status() === PHP_SESSION_ACTIVE && session_id() !== '';
    }
    
    /**
     * Verifica si la sesión ha expirado
     */
    private static function isSessionExpired() {
        if (!isset($_SESSION['login_time'])) {
            return true;
        }
        
        $timeout = 1800; // 30 minutos
        $currentTime = time();
        $loginTime = $_SESSION['login_time'];
        
        return ($currentTime - $loginTime) > $timeout;
    }
    
    /**
     * Renueva la sesión si es necesario
     */
    private static function renewSessionIfNeeded() {
        if (isset($_SESSION['login_time'])) {
            $timeout = 900; // 15 minutos
            $currentTime = time();
            $loginTime = $_SESSION['login_time'];
            
            if (($currentTime - $loginTime) > $timeout) {
                // Renovar la sesión
                session_regenerate_id(true);
                $_SESSION['login_time'] = $currentTime;
                error_log("SessionMiddleware: Sesión renovada");
            }
        }
    }
    
    /**
     * Maneja errores de sesión
     */
    private static function handleSessionError() {
        if (self::isAjaxRequest()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error de sesión. Por favor, inicia sesión nuevamente.',
                'redirect' => '/?view=index&action=login'
            ]);
        } else {
            header('Location: /?view=index&action=login');
        }
        exit;
    }
    
    /**
     * Maneja sesión expirada
     */
    private static function handleSessionExpired() {
        // Limpiar datos de sesión
        session_unset();
        session_destroy();
        
        if (self::isAjaxRequest()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Sesión expirada. Por favor, inicia sesión nuevamente.',
                'redirect' => '/?view=index&action=login'
            ]);
        } else {
            header('Location: /?view=index&action=login');
        }
        exit;
    }
    
    /**
     * Verifica si es una petición AJAX
     */
    private static function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * Obtiene información de debug de la sesión
     */
    public static function getSessionDebugInfo() {
        return [
            'session_id' => session_id(),
            'session_status' => session_status(),
            'session_name' => session_name(),
            'session_save_path' => session_save_path(),
            'session_data' => $_SESSION ?? [],
            'headers_sent' => headers_sent(),
            'is_ajax' => self::isAjaxRequest()
        ];
    }
} 