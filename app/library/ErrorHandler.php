<?php
/**
 * Global Error Handler - ByFrost
 * Sistema unificado de manejo de errores
 */

class ErrorHandler {
    private static $logFile = "app/logs/validation_errors.log";
    private static $debugMode = true;
    
    /**
     * Maneja errores de PHP
     */
    public static function handleError($errno, $errstr, $errfile, $errline) {
        $errorMessage = date("Y-m-d H:i:s") . " - Error: [$errno] $errstr in $errfile on line $errline\n";
        error_log($errorMessage, 3, self::$logFile);
        
        if (self::$debugMode) {
            echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px; border-radius: 4px;'>";
            echo "<strong>Error:</strong> $errstr<br>";
            echo "<strong>Archivo:</strong> $errfile<br>";
            echo "<strong>Línea:</strong> $errline";
            echo "</div>";
        }
        
        return true;
    }
    
    /**
     * Maneja excepciones
     */
    public static function handleException($exception) {
        $errorMessage = date("Y-m-d H:i:s") . " - Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";
        error_log($errorMessage, 3, self::$logFile);
        
        if (self::$debugMode) {
            echo "<div style='background: #fff3e0; border: 1px solid #ff9800; padding: 10px; margin: 10px; border-radius: 4px;'>";
            echo "<strong>Excepción:</strong> " . $exception->getMessage() . "<br>";
            echo "<strong>Archivo:</strong> " . $exception->getFile() . "<br>";
            echo "<strong>Línea:</strong> " . $exception->getLine();
            echo "</div>";
        } else {
            $_SESSION["error"] = "Ha ocurrido un error inesperado. Por favor, inténtelo de nuevo.";
            header("Location: " . url . "?view=error&action=500");
            exit();
        }
    }
    
    /**
     * Maneja errores fatales
     */
    public static function handleFatalError() {
        $error = error_get_last();
        if ($error !== null && $error['type'] === E_ERROR) {
            $errorMessage = date("Y-m-d H:i:s") . " - Fatal Error: " . $error['message'] . " in " . $error['file'] . " on line " . $error['line'] . "\n";
            error_log($errorMessage, 3, self::$logFile);
            
            if (self::$debugMode) {
                echo "<div style='background: #ffebee; border: 1px solid #d32f2f; padding: 10px; margin: 10px; border-radius: 4px;'>";
                echo "<strong>Error Fatal:</strong> " . $error['message'] . "<br>";
                echo "<strong>Archivo:</strong> " . $error['file'] . "<br>";
                echo "<strong>Línea:</strong> " . $error['line'];
                echo "</div>";
            }
        }
    }
    
    /**
     * Registra un error personalizado
     */
    public static function logError($message, $context = []) {
        $contextStr = !empty($context) ? " - Context: " . json_encode($context) : "";
        $errorMessage = date("Y-m-d H:i:s") . " - Custom Error: $message$contextStr\n";
        error_log($errorMessage, 3, self::$logFile);
    }
    
    /**
     * Configura el modo debug
     */
    public static function setDebugMode($enabled) {
        self::$debugMode = $enabled;
    }
    
    /**
     * Inicializa los manejadores de errores
     */
    public static function init() {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleFatalError']);
    }
}

// Inicializar el manejador de errores
ErrorHandler::init();
?>