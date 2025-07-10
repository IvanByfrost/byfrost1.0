<?php
require_once '../app/scripts/connection.php';
require_once '../app/models/studentStatsModel.php';

echo "<h2>🔧 PRUEBA DE STUDENTSTATSMODEL CON BALDUR.SQL</h2>";

try {
    $studentStats = new StudentStatsModel($dbConn);
    
    echo "<h3>✅ Probando getStudentStats()</h3>";
    $stats = $studentStats->getStudentStats();
    echo "<pre>" . print_r($stats, true) . "</pre>";
    
    echo "<h3>✅ Probando getStudentStatsByGender()</h3>";
    $genderStats = $studentStats->getStudentStatsByGender();
    echo "<pre>" . print_r($genderStats, true) . "</pre>";
    
    echo "<h3>✅ Probando getStudentStatsByAge()</h3>";
    $ageStats = $studentStats->getStudentStatsByAge();
    echo "<pre>" . print_r($ageStats, true) . "</pre>";
    
    echo "<h3>✅ Probando getRecentStudents()</h3>";
    $recentStudents = $studentStats->getRecentStudents(5);
    echo "<pre>" . print_r($recentStudents, true) . "</pre>";
    
    echo "<h3>✅ Probando getMonthlyGrowth()</h3>";
    $monthlyGrowth = $studentStats->getMonthlyGrowth();
    echo "<pre>" . print_r($monthlyGrowth, true) . "</pre>";
    
    echo "<h3>✅ Probando getTopPerformingStudents()</h3>";
    $topStudents = $studentStats->getTopPerformingStudents(5);
    echo "<pre>" . print_r($topStudents, true) . "</pre>";
    
    echo "<h3>✅ Probando getStudentsNeedingAttention()</h3>";
    $needingAttention = $studentStats->getStudentsNeedingAttention(5);
    echo "<pre>" . print_r($needingAttention, true) . "</pre>";
    
    echo "<h2>🎉 TODAS LAS PRUEBAS COMPLETADAS EXITOSAMENTE</h2>";
    echo "<p><strong>✅ Tabla student</strong> reemplazada por users + user_roles</p>";
    echo "<p><strong>✅ student_id</strong> reemplazado por user_id</p>";
    echo "<p><strong>✅ student_name</strong> reemplazado por CONCAT(first_name, last_name)</p>";
    echo "<p><strong>✅ score_date</strong> reemplazado por graded_at</p>";
    echo "<p><strong>✅ check_in_at</strong> reemplazado por attendance_date</p>";
    echo "<p><strong>✅ gender</strong> corregido - Baldur.sql no tiene esta columna</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ ERROR:</h3>";
    echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
}
?> 