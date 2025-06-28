<?php
/**
 * SecurityMiddleware - Validación y sanitización de rutas
 * 
 * Este middleware protege contra:
 * - Directory traversal
 * - Path injection
 * - Acceso directo a archivos sensibles
 * - XSS en URLs
 */
class SecurityMiddleware {
    
    /**
     * Valida y sanitiza una ruta
     * 
     * @param string $path Ruta a validar
     * @return array ['valid' => bool, 'sanitized' => string, 'error' => string]
     */
    public static function validatePath($path) {
        // Si la ruta está vacía, es válida (página principal)
        if (empty($path)) {
            return [
                'valid' => true,
                'sanitized' => '',
                'error' => ''
            ];
        }
        
        // Sanitizar la ruta
        $sanitized = self::sanitizePath($path);
        
        // Verificar patrones peligrosos
        if (self::hasDangerousPatterns($sanitized)) {
            return [
                'valid' => false,
                'sanitized' => '',
                'error' => 'Patrón peligroso detectado'
            ];
        }
        
        // Verificar directory traversal
        if (self::hasDirectoryTraversal($sanitized)) {
            return [
                'valid' => false,
                'sanitized' => '',
                'error' => 'Directory traversal detectado'
            ];
        }
        
        return [
            'valid' => true,
            'sanitized' => $sanitized,
            'error' => ''
        ];
    }
    
    /**
     * Sanitiza una ruta
     * 
     * @param string $path Ruta a sanitizar
     * @return string Ruta sanitizada
     */
    private static function sanitizePath($path) {
        // Remover caracteres peligrosos pero mantener algunos válidos
        $path = preg_replace('/[<>"|?*]/', '', $path);
        
        // Normalizar separadores
        $path = str_replace(['\\', '//'], '/', $path);
        
        // Remover puntos dobles (directory traversal)
        $path = preg_replace('/\.\./', '', $path);
        
        // Limpiar espacios y caracteres de control
        $path = trim($path, '/');
        
        // NO convertir a minúsculas para mantener compatibilidad
        // $path = strtolower($path);
        
        return $path;
    }
    
    /**
     * Verifica si la ruta tiene patrones peligrosos
     * 
     * @param string $path Ruta a verificar
     * @return bool True si tiene patrones peligrosos
     */
    private static function hasDangerousPatterns($path) {
        $dangerousPatterns = [
            'app/',
            'config.php',
            '.env',
            'vendor/',
            'node_modules/',
            '.git/',
            '.htaccess',
            '.htpasswd',
            '.sql',
            '.log',
            '.bak',
            '.backup',
            '.tmp',
            '.temp',
            'php://',
            'file://',
            'data://'
        ];
        
        foreach ($dangerousPatterns as $pattern) {
            if (strpos($path, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Verifica si la ruta tiene directory traversal
     * 
     * @param string $path Ruta a verificar
     * @return bool True si tiene directory traversal
     */
    private static function hasDirectoryTraversal($path) {
        // Verificar secuencias de directory traversal
        $traversalPatterns = [
            '/\.\./',
            '/\.\.\\\\/',
            '/\.\./',
            '/\.\.\\\\/',
            '/\.\./',
            '/\.\.\./',
            '/\.\.\.\./'
        ];
        
        foreach ($traversalPatterns as $pattern) {
            if (preg_match($pattern, $path)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Valida parámetros GET
     * 
     * @param array $params Parámetros a validar
     * @return bool True si los parámetros son válidos
     */
    public static function validateGetParams($params) {
        // Para desarrollo, ser más permisivo
        // Solo verificar que no haya patrones peligrosos
        foreach ($params as $key => $value) {
            if (is_string($value)) {
                if (self::hasDangerousPatterns($value)) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Sanitiza un parámetro
     * 
     * @param string $param Parámetro a sanitizar
     * @return string Parámetro sanitizado
     */
    private static function sanitizeParam($param) {
        // Remover caracteres peligrosos pero mantener algunos válidos
        $param = preg_replace('/[<>"|?*]/', '', $param);
        
        // Limpiar espacios
        $param = trim($param);
        
        // Limitar longitud
        if (strlen($param) > 255) {
            $param = substr($param, 0, 255);
        }
        
        return $param;
    }
    
    /**
     * Genera una URL segura
     * 
     * @param string $view Vista
     * @param string $action Acción
     * @param array $params Parámetros adicionales
     * @return string URL segura
     */
    public static function generateSecureUrl($view, $action = '', $params = []) {
        // Validar vista
        $view = self::sanitizeParam($view);
        
        // Validar acción
        $action = self::sanitizeParam($action);
        
        // Construir URL base
        $url = '?view=' . urlencode($view);
        
        if (!empty($action)) {
            $url .= '&action=' . urlencode($action);
        }
        
        // Agregar parámetros adicionales
        foreach ($params as $key => $value) {
            $key = self::sanitizeParam($key);
            $value = self::sanitizeParam($value);
            $url .= '&' . urlencode($key) . '=' . urlencode($value);
        }
        
        return $url;
    }
} 