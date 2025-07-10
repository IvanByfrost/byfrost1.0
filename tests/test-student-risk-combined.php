<?php
// Script para probar específicamente el método getStudentsAtCombinedRisk
echo "<h1>🧪 Prueba del Método getStudentsAtCombinedRisk</h1>";

// Definir ROOT si no está definido
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

// Cargar configuración
if (file_exists(ROOT . '/config.php')) {
    require_once ROOT . '/config.php';
    echo "<p style='color: green;'>✅ config.php cargado</p>";
} else {
    echo "<p style='color: red;'>❌ config.php no existe</p>";
    exit;
}

// Conectar a la base de datos
try {
    require_once ROOT . '/app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<p style='color: green;'>✅ Conexión a BD exitosa</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
    exit;
}

// Probar el modelo StudentRiskModel
echo "<h2>🧪 Prueba del StudentRiskModel</h2>";
try {
    require_once ROOT . '/app/models/studentRiskModel.php';
    
    if (class_exists('StudentRiskModel')) {
        echo "<p style='color: green;'>✅ Clase StudentRiskModel existe</p>";
        
        $model = new StudentRiskModel($dbConn);
        
        // Obtener información de debug
        $debugInfo = $model->getDebugInfo();
        echo "<p><strong>Información de debug:</strong></p>";
        echo "<ul>";
        echo "<li>userIdColumn: " . $debugInfo['userIdColumn'] . "</li>";
        echo "<li>studentUserIdColumn: " . $debugInfo['studentUserIdColumn'] . "</li>";
        echo "</ul>";
        
        // Probar método getStudentsAtCombinedRisk
        echo "<p><strong>Probando getStudentsAtCombinedRisk()...</strong></p>";
        
        try {
            $studentsAtRisk = $model->getStudentsAtCombinedRisk(3.0, 3);
            echo "<p style='color: green;'>✅ getStudentsAtCombinedRisk() ejecutado correctamente</p>";
            echo "<p>Estudiantes en riesgo combinado: " . count($studentsAtRisk) . "</p>";
            
            if (!empty($studentsAtRisk)) {
                echo "<p><strong>Primer estudiante en riesgo:</strong></p>";
                echo "<pre>" . print_r($studentsAtRisk[0], true) . "</pre>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getStudentsAtCombinedRisk(): " . $e->getMessage() . "</p>";
            echo "<p><strong>Stack trace:</strong></p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        // Probar método getRiskStatistics
        echo "<p><strong>Probando getRiskStatistics()...</strong></p>";
        
        try {
            $stats = $model->getRiskStatistics();
            echo "<p style='color: green;'>✅ getRiskStatistics() ejecutado correctamente</p>";
            echo "<pre>" . print_r($stats, true) . "</pre>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getRiskStatistics(): " . $e->getMessage() . "</p>";
            echo "<p><strong>Stack trace:</strong></p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        // Probar método getStudentsAtRiskByGrades
        echo "<p><strong>Probando getStudentsAtRiskByGrades()...</strong></p>";
        
        try {
            $studentsAtRiskGrades = $model->getStudentsAtRiskByGrades(3.0);
            echo "<p style='color: green;'>✅ getStudentsAtRiskByGrades() ejecutado correctamente</p>";
            echo "<p>Estudiantes en riesgo por notas: " . count($studentsAtRiskGrades) . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getStudentsAtRiskByGrades(): " . $e->getMessage() . "</p>";
            echo "<p><strong>Stack trace:</strong></p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        // Probar método getStudentsAtRiskByAttendance
        echo "<p><strong>Probando getStudentsAtRiskByAttendance()...</strong></p>";
        
        try {
            $studentsAtRiskAttendance = $model->getStudentsAtRiskByAttendance(3, 1);
            echo "<p style='color: green;'>✅ getStudentsAtRiskByAttendance() ejecutado correctamente</p>";
            echo "<p>Estudiantes en riesgo por asistencia: " . count($studentsAtRiskAttendance) . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getStudentsAtRiskByAttendance(): " . $e->getMessage() . "</p>";
            echo "<p><strong>Stack trace:</strong></p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Clase StudentRiskModel NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error general en StudentRiskModel: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>🔗 Prueba del Dashboard del Director</h2>";
echo "<p>Ahora puedes probar el dashboard del director:</p>";
echo "<ul>";
echo "<li><a href='/?view=directorDashboard' target='_blank'>Dashboard Director</a></li>";
echo "</ul>";

echo "<h2>💡 Estado</h2>";
echo "<p>Si todas las pruebas pasan (✅), el dashboard del director debería funcionar sin errores.</p>";
echo "<p>Si hay errores (❌), necesitamos revisar más a fondo.</p>";
?> 