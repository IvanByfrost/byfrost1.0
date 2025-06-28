<?php
/**
 * HeaderManager - Gestión segura de headers HTTP
 * 
 * Esta clase maneja el envío de headers de forma segura, evitando
 * el error "headers already sent" y proporcionando logging detallado.
 */
class HeaderManager {
    
    /**
     * Envía un header de forma segura
     * 
     * @param string $header Header a enviar
     * @param bool $replace Si reemplazar el header existente
     * @param int $response_code Código de respuesta HTTP
     * @return bool True si el header se envió correctamente
     */
    public static function sendHeader($header, $replace = true, $response_code = null) {
        if (headers_sent($file, $line)) {
            error_log("HeaderManager Error: No se puede enviar header '$header' - headers ya enviados en {$file} línea {$line}");
            return false;
        }
        
        try {
            if ($response_code !== null) {
                header($header, $replace, $response_code);
            } else {
                header($header, $replace);
            }
            
            error_log("HeaderManager: Header enviado correctamente: $header");
            return true;
        } catch (Exception $e) {
            error_log("HeaderManager Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Envía un header de redirección
     * 
     * @param string $url URL de destino
     * @param int $status_code Código de estado HTTP (301, 302, 303, 307, 308)
     * @return bool True si el header se envió correctamente
     */
    public static function redirect($url, $status_code = 302) {
        $validCodes = [301, 302, 303, 307, 308];
        if (!in_array($status_code, $validCodes)) {
            $status_code = 302;
        }
        
        return self::sendHeader("Location: $url", true, $status_code);
    }
    
    /**
     * Envía headers de contenido JSON
     * 
     * @return bool True si los headers se enviaron correctamente
     */
    public static function sendJsonHeaders() {
        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'Cache-Control: no-cache, must-revalidate',
            'Expires: Mon, 26 Jul 1997 05:00:00 GMT'
        ];
        
        foreach ($headers as $header) {
            if (!self::sendHeader($header)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Envía headers de contenido HTML
     * 
     * @return bool True si los headers se enviaron correctamente
     */
    public static function sendHtmlHeaders() {
        return self::sendHeader('Content-Type: text/html; charset=utf-8');
    }
    
    /**
     * Envía headers de CORS
     * 
     * @param string $origin Origen permitido (default: *)
     * @return bool True si los headers se enviaron correctamente
     */
    public static function sendCorsHeaders($origin = '*') {
        $headers = [
            "Access-Control-Allow-Origin: $origin",
            'Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers: Content-Type, X-Requested-With, Authorization',
            'Access-Control-Allow-Credentials: true'
        ];
        
        foreach ($headers as $header) {
            if (!self::sendHeader($header)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Verifica si se pueden enviar headers
     * 
     * @return bool True si se pueden enviar headers
     */
    public static function canSendHeaders() {
        return !headers_sent();
    }
    
    /**
     * Obtiene información de debug sobre headers
     * 
     * @return array Información de debug
     */
    public static function getDebugInfo() {
        $headersSent = headers_sent($file, $line);
        
        return [
            'headers_sent' => $headersSent,
            'file' => $file ?? null,
            'line' => $line ?? null,
            'output_buffering' => ob_get_level() > 0,
            'output_buffer_level' => ob_get_level(),
            'session_status' => session_status(),
            'session_active' => session_status() === PHP_SESSION_ACTIVE
        ];
    }
    
    /**
     * Limpia cualquier salida en el buffer
     * 
     * @return bool True si se limpió correctamente
     */
    public static function cleanOutputBuffer() {
        if (ob_get_level() > 0) {
            ob_clean();
            return true;
        }
        return false;
    }
    
    /**
     * Inicia output buffering si no está activo
     * 
     * @return bool True si se inició correctamente
     */
    public static function startOutputBuffer() {
        if (ob_get_level() === 0) {
            ob_start();
            return true;
        }
        return false;
    }
} 