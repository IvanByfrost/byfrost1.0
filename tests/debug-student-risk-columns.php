<?php
// Script para debuggear las columnas de la base de datos
echo "<h1>🔍 Debug de Columnas - StudentRiskModel</h1>";

// Definir ROOT si no está definido
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

// Cargar configuración
if (file_exists(ROOT . '/config.php')) {
    require_once ROOT . '/config.php';
    echo "<p style='color: green;'>✅ config.php cargado</p>";
} else {
    echo "<p style='color: red;'>❌ config.php no existe en: " . ROOT . '/config.php' . "</p>";
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

// Verificar estructura de tabla users
echo "<h2>📋 Estructura de tabla 'users'</h2>";
try {
    $stmt = $dbConn->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Por defecto</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Buscar columnas de ID
    $idColumns = [];
    foreach ($columns as $column) {
        if (strpos(strtolower($column['Field']), 'id') !== false) {
            $idColumns[] = $column['Field'];
        }
    }
    
    echo "<p><strong>Columnas que contienen 'id':</strong> " . implode(', ', $idColumns) . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error al describir tabla users: " . $e->getMessage() . "</p>";
}

// Verificar estructura de tabla user_roles
echo "<h2>📋 Estructura de tabla 'user_roles'</h2>";
try {
    $stmt = $dbConn->query("DESCRIBE user_roles");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Por defecto</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error al describir tabla user_roles: " . $e->getMessage() . "</p>";
}

// Verificar estructura de tabla student_scores
echo "<h2>📋 Estructura de tabla 'student_scores'</h2>";
try {
    $stmt = $dbConn->query("DESCRIBE student_scores");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Por defecto</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error al describir tabla student_scores: " . $e->getMessage() . "</p>";
}

// Verificar estructura de tabla attendance
echo "<h2>📋 Estructura de tabla 'attendance'</h2>";
try {
    $stmt = $dbConn->query("DESCRIBE attendance");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Por defecto</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error al describir tabla attendance: " . $e->getMessage() . "</p>";
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
        
        // Probar método getRiskStatistics
        echo "<p><strong>Probando getRiskStatistics()...</strong></p>";
        $stats = $model->getRiskStatistics();
        
        if ($stats) {
            echo "<p style='color: green;'>✅ getRiskStatistics() funciona correctamente</p>";
            echo "<pre>" . print_r($stats, true) . "</pre>";
        } else {
            echo "<p style='color: orange;'>⚠️ getRiskStatistics() retorna null o vacío</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Clase StudentRiskModel NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en StudentRiskModel: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>💡 Recomendaciones</h2>";
echo "<ul>";
echo "<li>Si la tabla 'users' no tiene columna 'user_id', necesitamos usar 'id'</li>";
echo "<li>Si la tabla 'student_scores' no tiene columna 'student_user_id', necesitamos usar 'student_id'</li>";
echo "<li>Si las tablas no existen, necesitamos crearlas</li>";
echo "</ul>";
?> 