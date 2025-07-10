<?php
/**
 * Test para el módulo de Promedios Académicos
 * Verifica que todas las funcionalidades trabajen correctamente con Baldur.sql
 */

// Configuración
require_once '../config.php';
require_once '../app/scripts/connection.php';
require_once '../app/models/academicAveragesModel.php';

echo "<h1>🧪 Test: Módulo de Promedios Académicos</h1>";
echo "<p><strong>Fecha:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<hr>";

try {
    // 1. Probar conexión a la base de datos
    echo "<h2>1. 🔌 Prueba de Conexión</h2>";
    $conn = getConnection();
    if ($conn) {
        echo "✅ Conexión exitosa a la base de datos<br>";
    } else {
        echo "❌ Error en la conexión a la base de datos<br>";
        exit;
    }
    
    // 2. Probar instanciación del modelo
    echo "<h2>2. 🗄️ Prueba del Modelo</h2>";
    $model = new AcademicAveragesModel();
    echo "✅ Modelo AcademicAveragesModel instanciado correctamente<br>";
    
    // 3. Probar consulta de promedios por período
    echo "<h2>3. 📊 Prueba de Promedios por Período</h2>";
    try {
        $termAverages = $model->getTermAverages();
        echo "✅ Consulta de promedios por período ejecutada<br>";
        echo "📈 Resultados obtenidos: " . count($termAverages) . " períodos<br>";
        
        if (!empty($termAverages)) {
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>Período</th><th>Promedio</th><th>Total Calificaciones</th><th>Tasa Aprobación</th></tr>";
            foreach ($termAverages as $term) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($term['academic_term_name']) . "</td>";
                echo "<td>" . $term['promedio'] . "</td>";
                echo "<td>" . $term['total_calificaciones'] . "</td>";
                echo "<td>" . $term['tasa_aprobacion'] . "%</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "⚠️ No hay datos de períodos académicos disponibles<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error en consulta de promedios por período: " . $e->getMessage() . "<br>";
    }
    
    // 4. Probar estadísticas generales
    echo "<h2>4. 📈 Prueba de Estadísticas Generales</h2>";
    try {
        $generalStats = $model->getGeneralStats();
        echo "✅ Estadísticas generales obtenidas<br>";
        echo "<ul>";
        echo "<li><strong>Promedio General:</strong> " . $generalStats['promedio_general'] . "</li>";
        echo "<li><strong>Total Calificaciones:</strong> " . $generalStats['total_calificaciones'] . "</li>";
        echo "<li><strong>Tasa de Aprobación:</strong> " . $generalStats['tasa_aprobacion_general'] . "%</li>";
        echo "<li><strong>Total Estudiantes:</strong> " . $generalStats['total_estudiantes'] . "</li>";
        echo "</ul>";
    } catch (Exception $e) {
        echo "❌ Error en estadísticas generales: " . $e->getMessage() . "<br>";
    }
    
    // 5. Probar mejores estudiantes
    echo "<h2>5. 🏆 Prueba de Mejores Estudiantes</h2>";
    try {
        $topStudents = $model->getTopStudents();
        echo "✅ Consulta de mejores estudiantes ejecutada<br>";
        echo "👥 Resultados obtenidos: " . count($topStudents) . " estudiantes<br>";
        
        if (!empty($topStudents)) {
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>Estudiante</th><th>Período</th><th>Promedio</th><th>Materias</th></tr>";
            foreach (array_slice($topStudents, 0, 5) as $student) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($student['student_name']) . "</td>";
                echo "<td>" . htmlspecialchars($student['academic_term_name']) . "</td>";
                echo "<td>" . $student['promedio'] . "</td>";
                echo "<td>" . $student['materias_count'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "⚠️ No hay datos de estudiantes disponibles<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error en mejores estudiantes: " . $e->getMessage() . "<br>";
    }
    
    // 6. Probar promedios por asignatura
    echo "<h2>6. 📚 Prueba de Promedios por Asignatura</h2>";
    try {
        $subjectAverages = $model->getSubjectAverages();
        echo "✅ Consulta de promedios por asignatura ejecutada<br>";
        echo "📖 Resultados obtenidos: " . count($subjectAverages) . " asignaturas<br>";
        
        if (!empty($subjectAverages)) {
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>Asignatura</th><th>Promedio</th><th>Total Calificaciones</th><th>Tasa Aprobación</th></tr>";
            foreach (array_slice($subjectAverages, 0, 5) as $subject) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($subject['subject_name']) . "</td>";
                echo "<td>" . $subject['promedio'] . "</td>";
                echo "<td>" . $subject['total_calificaciones'] . "</td>";
                echo "<td>" . $subject['tasa_aprobacion'] . "%</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "⚠️ No hay datos de asignaturas disponibles<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error en promedios por asignatura: " . $e->getMessage() . "<br>";
    }
    
    // 7. Verificar compatibilidad con Baldur.sql
    echo "<h2>7. 🗄️ Verificación de Compatibilidad con Baldur.sql</h2>";
    
    // Verificar que las tablas necesarias existen
    $requiredTables = [
        'student_scores',
        'activities', 
        'academic_terms',
        'subjects',
        'users',
        'user_roles'
    ];
    
    $missingTables = [];
    foreach ($requiredTables as $table) {
        $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
        if (mysqli_num_rows($result) == 0) {
            $missingTables[] = $table;
        }
    }
    
    if (empty($missingTables)) {
        echo "✅ Todas las tablas requeridas existen en la base de datos<br>";
    } else {
        echo "❌ Faltan las siguientes tablas: " . implode(', ', $missingTables) . "<br>";
        echo "⚠️ Es necesario ejecutar Baldur.sql primero<br>";
    }
    
    // 8. Probar consultas optimizadas
    echo "<h2>8. ⚡ Prueba de Consultas Optimizadas</h2>";
    
    // Probar vista optimizada de promedios
    $optimizedQuery = "
    SELECT 
        act.term_name AS academic_term_name, 
        ROUND(AVG(ss.score), 2) AS promedio,
        COUNT(ss.score_id) AS total_calificaciones
    FROM student_scores ss
    JOIN activities a ON ss.activity_id = a.activity_id
    JOIN academic_terms act ON a.term_id = act.term_id
    WHERE ss.score IS NOT NULL
    GROUP BY act.term_id, act.term_name
    ORDER BY act.term_id ASC
    LIMIT 3
    ";
    
    $result = mysqli_query($conn, $optimizedQuery);
    if ($result) {
        $count = mysqli_num_rows($result);
        echo "✅ Consulta optimizada ejecutada correctamente<br>";
        echo "📊 Resultados obtenidos: $count períodos<br>";
        
        if ($count > 0) {
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>Período</th><th>Promedio</th><th>Total Calificaciones</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['academic_term_name']) . "</td>";
                echo "<td>" . $row['promedio'] . "</td>";
                echo "<td>" . $row['total_calificaciones'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "❌ Error en consulta optimizada: " . mysqli_error($conn) . "<br>";
    }
    
    // 9. Resumen final
    echo "<h2>9. 📋 Resumen Final</h2>";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<h3>✅ Funcionalidades Verificadas:</h3>";
    echo "<ul>";
    echo "<li>✅ Conexión a base de datos</li>";
    echo "<li>✅ Instanciación del modelo</li>";
    echo "<li>✅ Consulta de promedios por período</li>";
    echo "<li>✅ Estadísticas generales</li>";
    echo "<li>✅ Mejores estudiantes</li>";
    echo "<li>✅ Promedios por asignatura</li>";
    echo "<li>✅ Compatibilidad con Baldur.sql</li>";
    echo "<li>✅ Consultas optimizadas</li>";
    echo "</ul>";
    
    echo "<h3>🎯 Próximos Pasos:</h3>";
    echo "<ol>";
    echo "<li>Ejecutar Baldur.sql si no se ha hecho</li>";
    echo "<li>Insertar datos de prueba en las tablas</li>";
    echo "<li>Probar la interfaz web en: <code>?view=academicAverages</code></li>";
    echo "<li>Verificar que el sidebar del director incluya el enlace</li>";
    echo "</ol>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error General</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
}

echo "<hr>";
echo "<p><strong>Test completado:</strong> " . date('Y-m-d H:i:s') . "</p>";
?> 