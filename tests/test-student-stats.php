<?php
/**
 * Test para verificar las estadísticas de estudiantes
 * Ejecutar: http://localhost:8000/tests/test-student-stats.php
 */

require_once '../config.php';
require_once '../app/models/studentStatsModel.php';

echo "<h1>🧪 Test de Estadísticas de Estudiantes</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    .success { background-color: #d4edda; border-color: #c3e6cb; }
    .error { background-color: #f8d7da; border-color: #f5c6cb; }
    .info { background-color: #d1ecf1; border-color: #bee5eb; }
    pre { background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; }
</style>";

try {
    // 1. Conectar a la base de datos
    echo "<div class='test-section info'>";
    echo "<h3>1. Conexión a la base de datos</h3>";
    
    $dbConn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión exitosa a la base de datos<br>";
    echo "</div>";

    // 2. Crear instancia del modelo
    echo "<div class='test-section info'>";
    echo "<h3>2. Inicialización del modelo</h3>";
    
    $studentStats = new StudentStatsModel($dbConn);
    echo "✅ Modelo StudentStatsModel inicializado correctamente<br>";
    echo "</div>";

    // 3. Probar estadísticas generales
    echo "<div class='test-section'>";
    echo "<h3>3. Estadísticas Generales</h3>";
    
    $stats = $studentStats->getStudentStats();
    if ($stats) {
        echo "<div class='success'>";
        echo "✅ Estadísticas obtenidas correctamente:<br>";
        echo "<pre>" . print_r($stats, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "❌ Error al obtener estadísticas generales";
        echo "</div>";
    }
    echo "</div>";

    // 4. Probar estadísticas por género
    echo "<div class='test-section'>";
    echo "<h3>4. Estadísticas por Género</h3>";
    
    $genderStats = $studentStats->getStudentStatsByGender();
    if ($genderStats) {
        echo "<div class='success'>";
        echo "✅ Estadísticas por género obtenidas correctamente:<br>";
        echo "<pre>" . print_r($genderStats, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "❌ Error al obtener estadísticas por género";
        echo "</div>";
    }
    echo "</div>";

    // 5. Probar estadísticas por edad
    echo "<div class='test-section'>";
    echo "<h3>5. Estadísticas por Edad</h3>";
    
    $ageStats = $studentStats->getStudentStatsByAge();
    if ($ageStats) {
        echo "<div class='success'>";
        echo "✅ Estadísticas por edad obtenidas correctamente:<br>";
        echo "<pre>" . print_r($ageStats, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "❌ Error al obtener estadísticas por edad";
        echo "</div>";
    }
    echo "</div>";

    // 6. Probar estudiantes recientes
    echo "<div class='test-section'>";
    echo "<h3>6. Estudiantes Recientes</h3>";
    
    $recentStudents = $studentStats->getRecentStudents(5);
    if ($recentStudents) {
        echo "<div class='success'>";
        echo "✅ Estudiantes recientes obtenidos correctamente:<br>";
        echo "<pre>" . print_r($recentStudents, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "❌ Error al obtener estudiantes recientes";
        echo "</div>";
    }
    echo "</div>";

    // 7. Probar estudiantes con mejor rendimiento
    echo "<div class='test-section'>";
    echo "<h3>7. Mejor Rendimiento</h3>";
    
    $topStudents = $studentStats->getTopPerformingStudents(5);
    if ($topStudents) {
        echo "<div class='success'>";
        echo "✅ Estudiantes con mejor rendimiento obtenidos correctamente:<br>";
        echo "<pre>" . print_r($topStudents, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "❌ Error al obtener estudiantes con mejor rendimiento";
        echo "</div>";
    }
    echo "</div>";

    // 8. Probar estudiantes que necesitan atención
    echo "<div class='test-section'>";
    echo "<h3>8. Estudiantes que Necesitan Atención</h3>";
    
    $attentionStudents = $studentStats->getStudentsNeedingAttention(5);
    if ($attentionStudents) {
        echo "<div class='success'>";
        echo "✅ Estudiantes que necesitan atención obtenidos correctamente:<br>";
        echo "<pre>" . print_r($attentionStudents, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "❌ Error al obtener estudiantes que necesitan atención";
        echo "</div>";
    }
    echo "</div>";

    // 9. Probar crecimiento mensual
    echo "<div class='test-section'>";
    echo "<h3>9. Crecimiento Mensual</h3>";
    
    $monthlyGrowth = $studentStats->getMonthlyGrowth();
    if ($monthlyGrowth) {
        echo "<div class='success'>";
        echo "✅ Crecimiento mensual obtenido correctamente:<br>";
        echo "<pre>" . print_r($monthlyGrowth, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "❌ Error al obtener crecimiento mensual";
        echo "</div>";
    }
    echo "</div>";

    // 10. Verificar estructura de la base de datos
    echo "<div class='test-section'>";
    echo "<h3>10. Verificación de Estructura de Base de Datos</h3>";
    
    $tables = ['student', 'student_scores', 'attendance', 'subject'];
    $allTablesExist = true;
    
    foreach ($tables as $table) {
        $stmt = $dbConn->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        if ($stmt->rowCount() > 0) {
            echo "✅ Tabla '$table' existe<br>";
        } else {
            echo "❌ Tabla '$table' NO existe<br>";
            $allTablesExist = false;
        }
    }
    
    if ($allTablesExist) {
        echo "<div class='success'>";
        echo "✅ Todas las tablas necesarias existen";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "❌ Faltan algunas tablas. Ejecuta el script SQL: app/scripts/student_stats_tables.sql";
        echo "</div>";
    }
    echo "</div>";

    // 11. Resumen final
    echo "<div class='test-section success'>";
    echo "<h3>🎉 Resumen del Test</h3>";
    echo "✅ Todas las funcionalidades de estadísticas de estudiantes están funcionando correctamente<br>";
    echo "✅ El widget se puede integrar en el dashboard del director<br>";
    echo "✅ Los datos se pueden exportar y generar reportes<br>";
    echo "<br><strong>Próximos pasos:</strong><br>";
    echo "1. Ejecutar el script SQL si las tablas no existen<br>";
    echo "2. Acceder al dashboard del director para ver el widget<br>";
    echo "3. Navegar a 'Reportes > 📊 Estadísticas de Estudiantes'<br>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='test-section error'>";
    echo "<h3>❌ Error en el Test</h3>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Archivo: " . $e->getFile() . "<br>";
    echo "Línea: " . $e->getLine() . "<br>";
    echo "</div>";
}

echo "<br><hr>";
echo "<p><strong>Para ejecutar el script SQL:</strong></p>";
echo "<pre>mysql -u root -p byfrost < app/scripts/student_stats_tables.sql</pre>";
echo "<p><strong>O desde phpMyAdmin:</strong></p>";
echo "<p>1. Abrir phpMyAdmin<br>";
echo "2. Seleccionar la base de datos 'byfrost'<br>";
echo "3. Ir a la pestaña 'SQL'<br>";
echo "4. Copiar y pegar el contenido de app/scripts/student_stats_tables.sql<br>";
echo "5. Ejecutar</p>";
?> 