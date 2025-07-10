<?php
// Script simple para verificar las columnas exactas en las tablas
echo "=== DEBUG DE COLUMNAS ===\n";

// Definir ROOT
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

echo "ROOT: " . ROOT . "\n";

// Incluir archivos necesarios
require_once ROOT . '/config.php';
require_once ROOT . '/app/scripts/connection.php';

try {
    // Crear conexión
    $dbConn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Conexión a base de datos exitosa\n";
    echo "Base de datos: $dbname\n";
    
    // Verificar tabla users
    echo "\n--- TABLA USERS ---\n";
    try {
        $stmt = $dbConn->query("DESCRIBE users");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Columnas en tabla users:\n";
        foreach ($columns as $column) {
            echo "  - {$column['Field']} ({$column['Type']}) - {$column['Key']}\n";
        }
    } catch (Exception $e) {
        echo "✗ Error al describir tabla users: " . $e->getMessage() . "\n";
    }
    
    // Verificar tabla user_roles
    echo "\n--- TABLA USER_ROLES ---\n";
    try {
        $stmt = $dbConn->query("DESCRIBE user_roles");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Columnas en tabla user_roles:\n";
        foreach ($columns as $column) {
            echo "  - {$column['Field']} ({$column['Type']}) - {$column['Key']}\n";
        }
    } catch (Exception $e) {
        echo "✗ Error al describir tabla user_roles: " . $e->getMessage() . "\n";
    }
    
    // Verificar tabla student_scores
    echo "\n--- TABLA STUDENT_SCORES ---\n";
    try {
        $stmt = $dbConn->query("DESCRIBE student_scores");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Columnas en tabla student_scores:\n";
        foreach ($columns as $column) {
            echo "  - {$column['Field']} ({$column['Type']}) - {$column['Key']}\n";
        }
    } catch (Exception $e) {
        echo "✗ Error al describir tabla student_scores: " . $e->getMessage() . "\n";
    }
    
    // Verificar tabla attendance
    echo "\n--- TABLA ATTENDANCE ---\n";
    try {
        $stmt = $dbConn->query("DESCRIBE attendance");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Columnas en tabla attendance:\n";
        foreach ($columns as $column) {
            echo "  - {$column['Field']} ({$column['Type']}) - {$column['Key']}\n";
        }
    } catch (Exception $e) {
        echo "✗ Error al describir tabla attendance: " . $e->getMessage() . "\n";
    }
    
    // Verificar datos de ejemplo
    echo "\n--- DATOS DE EJEMPLO ---\n";
    try {
        $stmt = $dbConn->query("SELECT * FROM users LIMIT 1");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            echo "Ejemplo de usuario:\n";
            print_r($user);
        } else {
            echo "No hay usuarios en la tabla\n";
        }
    } catch (Exception $e) {
        echo "✗ Error al consultar usuarios: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== DEBUG COMPLETADO ===\n";
    
} catch (Exception $e) {
    echo "✗ Error general: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?> 