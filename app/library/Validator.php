<?php
/**
 * Validator - ByFrost
 * Librería centralizada de validación y sanitización
 */

class Validator {
    
    /**
     * Validar y sanitizar email
     */
    public static function validateEmail($email) {
        $email = trim($email);
        if (empty($email)) {
            return ['valid' => false, 'error' => 'El email es requerido'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'error' => 'Formato de email inválido'];
        }
        
        if (strlen($email) > 255) {
            return ['valid' => false, 'error' => 'El email es demasiado largo'];
        }
        
        return ['valid' => true, 'value' => filter_var($email, FILTER_SANITIZE_EMAIL)];
    }
    
    /**
     * Validar y sanitizar string
     */
    public static function validateString($value, $maxLength = 255, $minLength = 1) {
        $value = trim($value);
        
        if (empty($value) && $minLength > 0) {
            return ['valid' => false, 'error' => 'El campo es requerido'];
        }
        
        if (strlen($value) < $minLength) {
            return ['valid' => false, 'error' => "Mínimo $minLength caracteres"];
        }
        
        if (strlen($value) > $maxLength) {
            return ['valid' => false, 'error' => "Máximo $maxLength caracteres"];
        }
        
        return ['valid' => true, 'value' => htmlspecialchars($value, ENT_QUOTES, 'UTF-8')];
    }
    
    /**
     * Validar y sanitizar número entero
     */
    public static function validateInt($value, $min = null, $max = null) {
        if (!is_numeric($value)) {
            return ['valid' => false, 'error' => 'Debe ser un número'];
        }
        
        $int = (int)$value;
        
        if ($min !== null && $int < $min) {
            return ['valid' => false, 'error' => "Mínimo $min"];
        }
        
        if ($max !== null && $int > $max) {
            return ['valid' => false, 'error' => "Máximo $max"];
        }
        
        return ['valid' => true, 'value' => $int];
    }
    
    /**
     * Validar y sanitizar número decimal
     */
    public static function validateFloat($value, $min = null, $max = null) {
        if (!is_numeric($value)) {
            return ['valid' => false, 'error' => 'Debe ser un número'];
        }
        
        $float = (float)$value;
        
        if ($min !== null && $float < $min) {
            return ['valid' => false, 'error' => "Mínimo $min"];
        }
        
        if ($max !== null && $float > $max) {
            return ['valid' => false, 'error' => "Máximo $max"];
        }
        
        return ['valid' => true, 'value' => $float];
    }
    
    /**
     * Validar y sanitizar fecha
     */
    public static function validateDate($date, $format = 'Y-m-d') {
        $date = trim($date);
        
        if (empty($date)) {
            return ['valid' => false, 'error' => 'La fecha es requerida'];
        }
        
        $d = DateTime::createFromFormat($format, $date);
        if (!$d || $d->format($format) !== $date) {
            return ['valid' => false, 'error' => 'Formato de fecha inválido'];
        }
        
        return ['valid' => true, 'value' => $date];
    }
    
    /**
     * Validar y sanitizar contraseña
     */
    public static function validatePassword($password, $minLength = 8) {
        $password = trim($password);
        
        if (empty($password)) {
            return ['valid' => false, 'error' => 'La contraseña es requerida'];
        }
        
        if (strlen($password) < $minLength) {
            return ['valid' => false, 'error' => "Mínimo $minLength caracteres"];
        }
        
        // Validar complejidad básica
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $password)) {
            return ['valid' => false, 'error' => 'Debe contener mayúsculas, minúsculas y números'];
        }
        
        return ['valid' => true, 'value' => $password];
    }
    
    /**
     * Validar y sanitizar teléfono
     */
    public static function validatePhone($phone) {
        $phone = preg_replace('/[^0-9+\-\(\)\s]/', '', $phone);
        
        if (empty($phone)) {
            return ['valid' => false, 'error' => 'El teléfono es requerido'];
        }
        
        if (strlen($phone) < 7) {
            return ['valid' => false, 'error' => 'Teléfono muy corto'];
        }
        
        return ['valid' => true, 'value' => $phone];
    }
    
    /**
     * Validar y sanitizar documento
     */
    public static function validateDocument($document) {
        $document = preg_replace('/[^0-9]/', '', $document);
        
        if (empty($document)) {
            return ['valid' => false, 'error' => 'El documento es requerido'];
        }
        
        if (strlen($document) < 8) {
            return ['valid' => false, 'error' => 'Documento muy corto'];
        }
        
        return ['valid' => true, 'value' => $document];
    }
    
    /**
     * Validar y sanitizar URL
     */
    public static function validateUrl($url) {
        $url = trim($url);
        
        if (empty($url)) {
            return ['valid' => false, 'error' => 'La URL es requerida'];
        }
        
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return ['valid' => false, 'error' => 'URL inválida'];
        }
        
        return ['valid' => true, 'value' => filter_var($url, FILTER_SANITIZE_URL)];
    }
    
    /**
     * Validar y sanitizar archivo
     */
    public static function validateFile($file, $allowedTypes = [], $maxSize = 5242880) {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return ['valid' => false, 'error' => 'No se seleccionó archivo'];
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'error' => 'Error al subir archivo'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['valid' => false, 'error' => 'Archivo demasiado grande'];
        }
        
        if (!empty($allowedTypes)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                return ['valid' => false, 'error' => 'Tipo de archivo no permitido'];
            }
        }
        
        return ['valid' => true, 'value' => $file];
    }
    
    /**
     * Validar y sanitizar array
     */
    public static function validateArray($array, $required = true) {
        if ($required && (empty($array) || !is_array($array))) {
            return ['valid' => false, 'error' => 'Array requerido'];
        }
        
        if (!is_array($array)) {
            return ['valid' => false, 'error' => 'Debe ser un array'];
        }
        
        return ['valid' => true, 'value' => $array];
    }
    
    /**
     * Validar y sanitizar JSON
     */
    public static function validateJson($json) {
        $json = trim($json);
        
        if (empty($json)) {
            return ['valid' => false, 'error' => 'JSON requerido'];
        }
        
        $decoded = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['valid' => false, 'error' => 'JSON inválido'];
        }
        
        return ['valid' => true, 'value' => $decoded];
    }
    
    /**
     * Sanitizar GET
     */
    public static function sanitizeGet($key, $default = null) {
        return isset($_GET[$key]) ? htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8') : $default;
    }
    
    /**
     * Sanitizar POST
     */
    public static function sanitizePost($key, $default = null) {
        return isset($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8') : $default;
    }
    
    /**
     * Sanitizar REQUEST
     */
    public static function sanitizeRequest($key, $default = null) {
        return isset($_REQUEST[$key]) ? htmlspecialchars($_REQUEST[$key], ENT_QUOTES, 'UTF-8') : $default;
    }
    
    /**
     * Validar sesión
     */
    public static function validateSession($key) {
        return isset($_SESSION[$key]) && !empty($_SESSION[$key]);
    }
    
    /**
     * Validar rol
     */
    public static function validateRole($requiredRole) {
        if (!isset($_SESSION['role'])) {
            return false;
        }
        
        return $_SESSION['role'] === $requiredRole;
    }
    
    /**
     * Validar permisos
     */
    public static function validatePermission($permission) {
        if (!isset($_SESSION['permissions'])) {
            return false;
        }
        
        return in_array($permission, $_SESSION['permissions']);
    }
    
    /**
     * Generar token CSRF
     */
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validar token CSRF
     */
    public static function validateCSRFToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Log de errores de validación
     */
    public static function logValidationError($field, $error, $user = null) {
        $logEntry = date('Y-m-d H:i:s') . " - Validation Error: $field - $error";
        if ($user) {
            $logEntry .= " - User: $user";
        }
        
        error_log($logEntry . "\n", 3, 'app/logs/validation_errors.log');
    }
}
?> 