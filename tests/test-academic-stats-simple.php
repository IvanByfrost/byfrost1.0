<?php
// Script simple para probar AcademicStatsModel
echo "<h1>🧪 Prueba Simple del AcademicStatsModel</h1>";

// Cargar configuración
require_once 'config.php';
echo "<p style='color: green;'>✅ config.php cargado</p>";

// Conectar a la base de datos
try {
    require_once 'app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<p style='color: green;'>✅ Conexión a BD exitosa</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
    exit;
}

// Verificar tabla student_scores
try {
    $stmt = $dbConn->query("SHOW TABLES LIKE 'student_scores'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Tabla student_scores existe</p>";
    } else {
        echo "<p style='color: red;'>❌ Tabla student_scores NO existe</p>";
        exit;
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error verificando tabla: " . $e->getMessage() . "</p>";
    exit;
}

// Probar AcademicStatsModel
try {
    require_once 'app/models/academicStatsModel.php';
    $model = new AcademicStatsModel($dbConn);
    echo "<p style='color: green;'>✅ AcademicStatsModel creado correctamente</p>";
    
    // Probar getGeneralStats
    $generalStats = $model->getGeneralStats();
    echo "<p style='color: green;'>✅ getGeneralStats() ejecutado: " . count($generalStats) . " estadísticas</p>";
    echo "<pre>" . print_r($generalStats, true) . "</pre>";
    
    // Probar getAveragesByTerm
    $averagesByTerm = $model->getAveragesByTerm();
    echo "<p style='color: green;'>✅ getAveragesByTerm() ejecutado: " . count($averagesByTerm) . " períodos</p>";
    
    // Probar getAveragesBySubject
    $averagesBySubject = $model->getAveragesBySubject();
    echo "<p style='color: green;'>✅ getAveragesBySubject() ejecutado: " . count($averagesBySubject) . " asignaturas</p>";
    
    // Probar getTopStudents
    $topStudents = $model->getTopStudents(5);
    echo "<p style='color: green;'>✅ getTopStudents() ejecutado: " . count($topStudents) . " estudiantes</p>";
    
    // Probar getScoreDistribution
    $scoreDistribution = $model->getScoreDistribution();
    echo "<p style='color: green;'>✅ getScoreDistribution() ejecutado: " . count($scoreDistribution) . " rangos</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en AcademicStatsModel: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>✅ Prueba completada</h2>";
echo "<p>Si ves ✅ en todos los puntos, el AcademicStatsModel funciona correctamente.</p>";
echo "<p><a href='/?view=directorDashboard' target='_blank'>Probar Dashboard Director</a></p>";
?> 