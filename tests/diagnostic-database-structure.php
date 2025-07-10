<?php
// Script de diagnóstico para verificar la estructura real de las tablas
echo "=== DIAGNÓSTICO DE ESTRUCTURA DE BASE DE DATOS ===\n";

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
    
    // Verificar todas las tablas
    echo "\n--- TABLAS DISPONIBLES ---\n";
    $stmt = $dbConn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "✓ $table\n";
    }
    
    // Verificar estructura de tabla users
    echo "\n--- ESTRUCTURA DE TABLA USERS ---\n";
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
    
    // Verificar estructura de tabla user_roles
    echo "\n--- ESTRUCTURA DE TABLA USER_ROLES ---\n";
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
    
    // Verificar estructura de tabla student_scores
    echo "\n--- ESTRUCTURA DE TABLA STUDENT_SCORES ---\n";
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
    
    // Verificar estructura de tabla attendance
    echo "\n--- ESTRUCTURA DE TABLA ATTENDANCE ---\n";
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
    
    // Verificar usuarios
    try {
        $stmt = $dbConn->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Total de usuarios: " . $result['total'] . "\n";
        
        if ($result['total'] > 0) {
            $stmt = $dbConn->query("SELECT * FROM users LIMIT 1");
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Ejemplo de usuario:\n";
            print_r($user);
        }
    } catch (Exception $e) {
        echo "✗ Error al consultar usuarios: " . $e->getMessage() . "\n";
    }
    
    // Verificar roles de usuario
    try {
        $stmt = $dbConn->query("SELECT COUNT(*) as total FROM user_roles");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Total de roles de usuario: " . $result['total'] . "\n";
        
        if ($result['total'] > 0) {
            $stmt = $dbConn->query("SELECT * FROM user_roles LIMIT 1");
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Ejemplo de rol de usuario:\n";
            print_r($role);
        }
    } catch (Exception $e) {
        echo "✗ Error al consultar roles de usuario: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== DIAGNÓSTICO COMPLETADO ===\n";
    echo "Revisa la estructura de las tablas para identificar el nombre correcto de las columnas.\n";
    
} catch (Exception $e) {
    echo "✗ Error general: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?> 