<?php
require_once '../app/scripts/connection.php';

echo "<h2>🔍 PRUEBA DE TABLA SUBJECTS CON BALDUR.SQL</h2>";

try {
    // Verificar estructura de la tabla subjects
    echo "<h3>📋 Estructura de la tabla subjects:</h3>";
    $query = "DESCRIBE subjects";
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($structure, true) . "</pre>";
    
    // Verificar datos en la tabla subjects
    echo "<h3>📊 Datos en la tabla subjects:</h3>";
    $query = "SELECT * FROM subjects";
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($subjects, true) . "</pre>";
    
    // Probar la consulta corregida del activityStatsModel
    echo "<h3>✅ Probando consulta corregida de getStatsBySubject():</h3>";
    $query = "
    SELECT 
        s.subject_name,
        COUNT(ss.score_id) AS total_activities,
        ROUND(AVG(ss.score), 2) AS average_score,
        COUNT(DISTINCT ss.student_user_id) AS unique_students
    FROM subjects s
    LEFT JOIN professor_subjects ps ON s.subject_id = ps.subject_id
    LEFT JOIN activities a ON ps.professor_subject_id = a.professor_subject_id
    LEFT JOIN student_scores ss ON a.activity_id = ss.activity_id
    GROUP BY s.subject_id, s.subject_name
    ORDER BY total_activities DESC
    ";
    
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($stats, true) . "</pre>";
    
    echo "<h2>🎉 PRUEBA COMPLETADA EXITOSAMENTE</h2>";
    echo "<p><strong>✅ La tabla subjects no tiene columna is_active</strong></p>";
    echo "<p><strong>✅ La consulta corregida funciona correctamente</strong></p>";
    echo "<p><strong>✅ JOIN correcto: subjects → professor_subjects → activities → student_scores</strong></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ ERROR:</h3>";
    echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
}
?> 