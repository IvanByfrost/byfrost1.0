<?php
// Script para revisar y probar todas las consultas del studentRiskModel
echo "=== REVISIÓN DE CONSULTAS STUDENT RISK MODEL ===\n";

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
        
        // Verificar que los valores son numéricos
        if (isset($stats['total_students']) && is_numeric($stats['total_students'])) {
            echo "✓ total_students es numérico: " . $stats['total_students'] . "\n";
        } else {
            echo "⚠ total_students no es numérico\n";
        }
        
        if (isset($stats['risk_percentage']) && is_numeric($stats['risk_percentage'])) {
            echo "✓ risk_percentage es numérico: " . $stats['risk_percentage'] . "\n";
        } else {
            echo "⚠ risk_percentage no es numérico\n";
        }
        
    } catch (Exception $e) {
        echo "✗ Error en getRiskStatistics(): " . $e->getMessage() . "\n";
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
            
            // Verificar estructura de datos
            $requiredFields = ['student_id', 'student_name', 'average_score', 'total_activities'];
            foreach ($requiredFields as $field) {
                if (isset($students[0][$field])) {
                    echo "✓ Campo $field presente\n";
                } else {
                    echo "⚠ Campo $field ausente\n";
                }
            }
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
            
            // Verificar estructura de datos
            $requiredFields = ['student_id', 'student_name', 'absences', 'total_absences'];
            foreach ($requiredFields as $field) {
                if (isset($students[0][$field])) {
                    echo "✓ Campo $field presente\n";
                } else {
                    echo "⚠ Campo $field ausente\n";
                }
            }
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
            
            // Verificar estructura de datos
            $requiredFields = ['student_id', 'student_name', 'average_score', 'absences', 'risk_type'];
            foreach ($requiredFields as $field) {
                if (isset($students[0][$field])) {
                    echo "✓ Campo $field presente\n";
                } else {
                    echo "⚠ Campo $field ausente\n";
                }
            }
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
            
            // Verificar estructura de datos
            $requiredFields = ['month', 'low_grades_count', 'high_absences_count'];
            foreach ($requiredFields as $field) {
                if (isset($trends[0][$field])) {
                    echo "✓ Campo $field presente\n";
                } else {
                    echo "⚠ Campo $field ausente\n";
                }
            }
        }
    } catch (Exception $e) {
        echo "✗ Error en getRiskTrends(): " . $e->getMessage() . "\n";
    }
    
    // Probar getRiskRecommendations
    echo "\n--- Probando getRiskRecommendations() ---\n";
    try {
        // Obtener un ID de estudiante válido
        $stmt = $dbConn->query("SELECT {$debugInfo['userIdColumn']} FROM users LIMIT 1");
        $studentId = $stmt->fetchColumn();
        
        if ($studentId) {
            $recommendation = $riskModel->getRiskRecommendations($studentId);
            echo "✓ getRiskRecommendations() ejecutado correctamente\n";
            echo "Recomendación para estudiante $studentId:\n";
            print_r($recommendation);
        } else {
            echo "⚠ No hay estudiantes disponibles para probar getRiskRecommendations()\n";
        }
    } catch (Exception $e) {
        echo "✗ Error en getRiskRecommendations(): " . $e->getMessage() . "\n";
    }
    
    echo "\n=== REVISIÓN COMPLETADA ===\n";
    echo "Si todas las funciones se ejecutaron sin errores, las consultas están correctas.\n";
    echo "Las consultas ahora incluyen:\n";
    echo "- COALESCE para manejar valores NULL\n";
    echo "- CASE WHEN para evitar división por cero\n";
    echo "- Detección automática de nombres de columnas\n";
    echo "- Manejo robusto de casos edge\n";
    
} catch (Exception $e) {
    echo "✗ Error general: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?> 