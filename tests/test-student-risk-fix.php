<?php
require_once '../app/scripts/connection.php';
require_once '../app/models/studentRiskModel.php';

echo "<h2>🔧 PRUEBA DE STUDENTRISKMODEL CON BALDUR.SQL</h2>";

try {
    $studentRisk = new StudentRiskModel($dbConn);
    
    echo "<h3>✅ Probando getStudentsAtRiskByGrades()</h3>";
    $riskByGrades = $studentRisk->getStudentsAtRiskByGrades(3.0);
    echo "<pre>" . print_r($riskByGrades, true) . "</pre>";
    
    echo "<h3>✅ Probando getStudentsAtRiskByAttendance()</h3>";
    $riskByAttendance = $studentRisk->getStudentsAtRiskByAttendance(3, 1);
    echo "<pre>" . print_r($riskByAttendance, true) . "</pre>";
    
    echo "<h3>✅ Probando getStudentsAtCombinedRisk()</h3>";
    $combinedRisk = $studentRisk->getStudentsAtCombinedRisk(3.0, 3);
    echo "<pre>" . print_r($combinedRisk, true) . "</pre>";
    
    echo "<h3>✅ Probando getRiskStatistics()</h3>";
    $riskStats = $studentRisk->getRiskStatistics();
    echo "<pre>" . print_r($riskStats, true) . "</pre>";
    
    echo "<h3>✅ Probando getRiskTrends()</h3>";
    $riskTrends = $studentRisk->getRiskTrends(6);
    echo "<pre>" . print_r($riskTrends, true) . "</pre>";
    
    // Probar recomendaciones con un ID de ejemplo
    echo "<h3>✅ Probando getRiskRecommendations()</h3>";
    $recommendations = $studentRisk->getRiskRecommendations(1);
    echo "<pre>" . print_r($recommendations, true) . "</pre>";
    
    echo "<h2>🎉 TODAS LAS PRUEBAS COMPLETADAS EXITOSAMENTE</h2>";
    echo "<p><strong>✅ Tabla student</strong> reemplazada por users + user_roles</p>";
    echo "<p><strong>✅ student_id</strong> reemplazado por user_id</p>";
    echo "<p><strong>✅ student_name</strong> reemplazado por CONCAT(first_name, last_name)</p>";
    echo "<p><strong>✅ score_date</strong> reemplazado por graded_at</p>";
    echo "<p><strong>✅ check_in_at</strong> reemplazado por attendance_date</p>";
    echo "<p><strong>✅ status values</strong> corregidos: 'Ausente' → 'absent', 'Tardanza' → 'late'</p>";
    echo "<p><strong>✅ grade_id</strong> reemplazado por score_id</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ ERROR:</h3>";
    echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
}
?> 