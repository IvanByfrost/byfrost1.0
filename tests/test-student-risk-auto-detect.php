<?php
// Script de prueba para verificar que studentRiskModel con detección automática funciona
echo "=== PRUEBA DE STUDENT RISK MODEL CON DETECCIÓN AUTOMÁTICA ===\n";

// Definir ROOT
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

echo "ROOT: " . ROOT . "\n";

// Incluir archivos necesarios
require_once ROOT . '/config.php';
require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/models/studentRiskModel.php';

try {
    // Crear conexión
    $dbConn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Conexión a base de datos exitosa\n";
    echo "Base de datos: $dbname\n";
    
    // Crear instancia del modelo
    $riskModel = new StudentRiskModel($dbConn);
    echo "✓ Modelo StudentRiskModel creado exitosamente\n";
    
    // Obtener información de debug
    $debugInfo = $riskModel->getDebugInfo();
    echo "\n--- INFORMACIÓN DE DETECCIÓN ---\n";
    echo "Columna de ID de usuario detectada: " . $debugInfo['userIdColumn'] . "\n";
    echo "Columna de ID de estudiante detectada: " . $debugInfo['studentUserIdColumn'] . "\n";
    
    // Verificar estructura de tablas
    echo "\n--- VERIFICANDO ESTRUCTURA DE TABLAS ---\n";
    try {
        $tables = ['users', 'user_roles', 'student_scores', 'attendance'];
        foreach ($tables as $table) {
            $stmt = $dbConn->query("DESCRIBE $table");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "✓ Tabla $table existe con " . count($columns) . " columnas\n";
        }
    } catch (Exception $e) {
        echo "✗ Error verificando tablas: " . $e->getMessage() . "\n";
    }
    
    // Probar getRiskStatistics
    echo "\n--- Probando getRiskStatistics() ---\n";
    try {
        $stats = $riskModel->getRiskStatistics();
        echo "✓ getRiskStatistics() ejecutado correctamente\n";
        echo "Resultados:\n";
        print_r($stats);
    } catch (Exception $e) {
        echo "✗ Error en getRiskStatistics(): " . $e->getMessage() . "\n";
        echo "Query que causó el error:\n";
        echo "userIdColumn: " . $debugInfo['userIdColumn'] . "\n";
        echo "studentUserIdColumn: " . $debugInfo['studentUserIdColumn'] . "\n";
    }
    
    // Probar getStudentsAtRiskByGrades
    echo "\n--- Probando getStudentsAtRiskByGrades() ---\n";
    try {
        $students = $riskModel->getStudentsAtRiskByGrades(3.0);
        echo "✓ getStudentsAtRiskByGrades() ejecutado correctamente\n";
        echo "Estudiantes en riesgo por notas: " . count($students) . "\n";
        if (!empty($students)) {
            echo "Primer estudiante:\n";
            print_r($students[0]);
        }
    } catch (Exception $e) {
        echo "✗ Error en getStudentsAtRiskByGrades(): " . $e->getMessage() . "\n";
    }
    
    // Probar getStudentsAtRiskByAttendance
    echo "\n--- Probando getStudentsAtRiskByAttendance() ---\n";
    try {
        $students = $riskModel->getStudentsAtRiskByAttendance(3, 1);
        echo "✓ getStudentsAtRiskByAttendance() ejecutado correctamente\n";
        echo "Estudiantes en riesgo por asistencia: " . count($students) . "\n";
        if (!empty($students)) {
            echo "Primer estudiante:\n";
            print_r($students[0]);
        }
    } catch (Exception $e) {
        echo "✗ Error en getStudentsAtRiskByAttendance(): " . $e->getMessage() . "\n";
    }
    
    // Probar getStudentsAtCombinedRisk
    echo "\n--- Probando getStudentsAtCombinedRisk() ---\n";
    try {
        $students = $riskModel->getStudentsAtCombinedRisk(3.0, 3);
        echo "✓ getStudentsAtCombinedRisk() ejecutado correctamente\n";
        echo "Estudiantes en riesgo combinado: " . count($students) . "\n";
        if (!empty($students)) {
            echo "Primer estudiante:\n";
            print_r($students[0]);
        }
    } catch (Exception $e) {
        echo "✗ Error en getStudentsAtCombinedRisk(): " . $e->getMessage() . "\n";
    }
    
    // Probar getRiskTrends
    echo "\n--- Probando getRiskTrends() ---\n";
    try {
        $trends = $riskModel->getRiskTrends(6);
        echo "✓ getRiskTrends() ejecutado correctamente\n";
        echo "Tendencias de riesgo: " . count($trends) . " meses\n";
        if (!empty($trends)) {
            echo "Primera tendencia:\n";
            print_r($trends[0]);
        }
    } catch (Exception $e) {
        echo "✗ Error en getRiskTrends(): " . $e->getMessage() . "\n";
    }
    
    echo "\n=== PRUEBA COMPLETADA ===\n";
    echo "Si todas las funciones se ejecutaron sin errores, el modelo está funcionando correctamente.\n";
    echo "El modelo detectó automáticamente las columnas correctas de la base de datos.\n";
    
} catch (Exception $e) {
    echo "✗ Error general: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?> 