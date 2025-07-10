<?php
// Script para probar el StudentRiskModel corregido
echo "<h1>🧪 Prueba del StudentRiskModel Corregido</h1>";

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
        
        // Verificar que las columnas sean correctas para Baldur.sql
        if ($debugInfo['userIdColumn'] === 'user_id' && $debugInfo['studentUserIdColumn'] === 'student_user_id') {
            echo "<p style='color: green;'>✅ Columnas detectadas correctamente para Baldur.sql</p>";
        } else {
            echo "<p style='color: red;'>❌ Columnas incorrectas. Esperado: user_id, student_user_id. Obtenido: " . $debugInfo['userIdColumn'] . ", " . $debugInfo['studentUserIdColumn'] . "</p>";
        }
        
        // Probar método getRiskStatistics
        echo "<p><strong>Probando getRiskStatistics()...</strong></p>";
        $stats = $model->getRiskStatistics();
        
        if ($stats) {
            echo "<p style='color: green;'>✅ getRiskStatistics() funciona correctamente</p>";
            echo "<pre>" . print_r($stats, true) . "</pre>";
        } else {
            echo "<p style='color: orange;'>⚠️ getRiskStatistics() retorna null o vacío (puede ser normal si no hay datos)</p>";
        }
        
        // Probar método getStudentsAtRiskByGrades
        echo "<p><strong>Probando getStudentsAtRiskByGrades()...</strong></p>";
        $studentsAtRisk = $model->getStudentsAtRiskByGrades(3.0);
        
        if ($studentsAtRisk !== false) {
            echo "<p style='color: green;'>✅ getStudentsAtRiskByGrades() funciona correctamente</p>";
            echo "<p>Estudiantes en riesgo por notas: " . count($studentsAtRisk) . "</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ getStudentsAtRiskByGrades() retorna false (puede ser normal si no hay datos)</p>";
        }
        
        // Probar método getStudentsAtRiskByAttendance
        echo "<p><strong>Probando getStudentsAtRiskByAttendance()...</strong></p>";
        $studentsAtRiskAttendance = $model->getStudentsAtRiskByAttendance(3, 1);
        
        if ($studentsAtRiskAttendance !== false) {
            echo "<p style='color: green;'>✅ getStudentsAtRiskByAttendance() funciona correctamente</p>";
            echo "<p>Estudiantes en riesgo por asistencia: " . count($studentsAtRiskAttendance) . "</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ getStudentsAtRiskByAttendance() retorna false (puede ser normal si no hay datos)</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Clase StudentRiskModel NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en StudentRiskModel: " . $e->getMessage() . "</p>";
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