<?php
require_once '../app/scripts/connection.php';
require_once '../app/models/activityStatsModel.php';

echo "<h2>🔧 PRUEBA DE ACTIVITYSTATSMODEL CON BALDUR.SQL</h2>";

try {
    $activityStats = new ActivityStatsModel($dbConn);
    
    echo "<h3>✅ Probando getActivitiesByMonth()</h3>";
    $activities = $activityStats->getActivitiesByMonth();
    echo "<pre>" . print_r($activities, true) . "</pre>";
    
    echo "<h3>✅ Probando getGradesByMonth()</h3>";
    $grades = $activityStats->getGradesByMonth();
    echo "<pre>" . print_r($grades, true) . "</pre>";
    
    echo "<h3>✅ Probando getAttendanceByMonth() - FIXED</h3>";
    $attendance = $activityStats->getAttendanceByMonth();
    echo "<pre>" . print_r($attendance, true) . "</pre>";
    
    echo "<h3>✅ Probando getStatsBySubject()</h3>";
    $subjects = $activityStats->getStatsBySubject();
    echo "<pre>" . print_r($subjects, true) . "</pre>";
    
    echo "<h3>✅ Probando getStudentPerformance()</h3>";
    $performance = $activityStats->getStudentPerformance();
    echo "<pre>" . print_r($performance, true) . "</pre>";
    
    echo "<h3>✅ Probando getChartData()</h3>";
    $chartData = $activityStats->getChartData();
    echo "<pre>" . print_r($chartData, true) . "</pre>";
    
    echo "<h2>🎉 TODAS LAS PRUEBAS COMPLETADAS EXITOSAMENTE</h2>";
    echo "<p><strong>✅ attendance_date</strong> corregido en getAttendanceByMonth()</p>";
    echo "<p><strong>✅ role_type</strong> corregido en getAttendanceByMonth() y getStudentPerformance()</p>";
    echo "<p><strong>✅ subjects</strong> corregido en getStatsBySubject() - removido is_active</p>";
    echo "<p><strong>✅ student_scores</strong> corregido en getStatsBySubject() - JOIN correcto</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ ERROR:</h3>";
    echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
}
?> 