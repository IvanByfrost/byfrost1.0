<?php
class DatabaseConnection {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $host = 'localhost';
        $dbName = 'baldur_db';
        
        // ✅ Nombres más seguros - Cambiar en producción
        $user = 'byfrost_app_user';
        $pass = 'ByFrost2024!Secure#';
        
        // 🔒 MEJOR PRÁCTICA: Usar variables de entorno
        // $user = $_ENV['DB_USER'] ?? 'byfrost_app_user';
        // $pass = $_ENV['DB_PASS'] ?? 'ByFrost2024!Secure#';

        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
            date_default_timezone_set('America/Bogota');
        } catch (PDOException $e) {
            // 🔍 DEBUG TEMPORAL - Mostrar error para diagnóstico
            error_log("Error de conexión a BD: " . $e->getMessage());
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevenir clonación del objeto
    private function __clone() {}
    
    // Prevenir deserialización
    public function __wakeup() {
        throw new Exception("No se puede deserializar un singleton");
    }
}

// Función de compatibilidad para mantener el código existente
function getConnection() {
    return DatabaseConnection::getInstance()->getConnection();
}