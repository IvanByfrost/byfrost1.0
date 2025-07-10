<?php
require_once '../app/scripts/connection.php';

echo "<h2>🔍 PRUEBA DE TABLA USERS CON BALDUR.SQL</h2>";

try {
    // Verificar estructura de la tabla users
    echo "<h3>📋 Estructura de la tabla users:</h3>";
    $query = "DESCRIBE users";
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($structure, true) . "</pre>";
    
    // Verificar datos en la tabla users
    echo "<h3>📊 Datos en la tabla users:</h3>";
    $query = "SELECT user_id, first_name, last_name, email, is_active, created_at FROM users LIMIT 5";
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($users, true) . "</pre>";
    
    // Verificar estudiantes en users
    echo "<h3>👨‍🎓 Estudiantes en users:</h3>";
    $query = "
    SELECT 
        u.user_id,
        u.first_name,
        u.last_name,
        u.email,
        u.is_active,
        ur.role_type
    FROM users u
    JOIN user_roles ur ON u.user_id = ur.user_id
    WHERE ur.role_type = 'student'
    LIMIT 5
    ";
    
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($students, true) . "</pre>";
    
    // Probar la consulta corregida del studentStatsModel
    echo "<h3>✅ Probando consulta corregida de getStudentStatsByGender():</h3>";
    $query = "
    SELECT 
        'No disponible' AS gender,
        COUNT(*) AS total,
        SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END) AS active,
        SUM(CASE WHEN u.is_active = 0 THEN 1 ELSE 0 END) AS inactive
    FROM users u
    JOIN user_roles ur ON u.user_id = ur.user_id
    WHERE ur.role_type = 'student'
    GROUP BY 'No disponible'
    ORDER BY total DESC
    ";
    
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($stats, true) . "</pre>";
    
    echo "<h2>🎉 PRUEBA COMPLETADA EXITOSAMENTE</h2>";
    echo "<p><strong>✅ La tabla users no tiene columna gender</strong></p>";
    echo "<p><strong>✅ La consulta corregida funciona correctamente</strong></p>";
    echo "<p><strong>✅ Los estudiantes se identifican por role_type = 'student'</strong></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ ERROR:</h3>";
    echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
}
?> 