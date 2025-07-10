<?php
// Script para debuggear la consulta específica que está fallando
echo "<h1>🔍 Debug de Consulta StudentRiskModel</h1>";

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

// Verificar datos en las tablas
echo "<h2>📊 Datos en las tablas</h2>";

// Verificar usuarios
try {
    $stmt = $dbConn->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Total usuarios:</strong> " . $result['total'] . "</p>";
    
    if ($result['total'] > 0) {
        $stmt = $dbConn->query("SELECT user_id, first_name, last_name, email FROM users LIMIT 5");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<p><strong>Primeros 5 usuarios:</strong></p>";
        echo "<ul>";
        foreach ($users as $user) {
            echo "<li>ID: " . $user['user_id'] . " - " . $user['first_name'] . " " . $user['last_name'] . " (" . $user['email'] . ")</li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error consultando usuarios: " . $e->getMessage() . "</p>";
}

// Verificar roles de usuario
try {
    $stmt = $dbConn->query("SELECT COUNT(*) as total FROM user_roles");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Total roles de usuario:</strong> " . $result['total'] . "</p>";
    
    if ($result['total'] > 0) {
        $stmt = $dbConn->query("SELECT user_id, role_type FROM user_roles LIMIT 5");
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<p><strong>Primeros 5 roles:</strong></p>";
        echo "<ul>";
        foreach ($roles as $role) {
            echo "<li>Usuario ID: " . $role['user_id'] . " - Rol: " . $role['role_type'] . "</li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error consultando roles: " . $e->getMessage() . "</p>";
}

// Probar consulta específica que está fallando
echo "<h2>🧪 Prueba de Consulta Específica</h2>";

try {
    // Consulta simple para verificar que las columnas existen
    $query = "SELECT u.user_id, u.first_name, u.last_name, ur.role_type 
              FROM users u 
              JOIN user_roles ur ON u.user_id = ur.user_id 
              WHERE ur.role_type = 'student' 
              LIMIT 1";
    
    echo "<p><strong>Probando consulta:</strong></p>";
    echo "<pre>" . htmlspecialchars($query) . "</pre>";
    
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo "<p style='color: green;'>✅ Consulta exitosa</p>";
        echo "<pre>" . print_r($result, true) . "</pre>";
    } else {
        echo "<p style='color: orange;'>⚠️ Consulta ejecutada pero no retornó datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en consulta: " . $e->getMessage() . "</p>";
}

// Probar el modelo con debug detallado
echo "<h2>🧪 Prueba del Modelo con Debug</h2>";

try {
    require_once ROOT . '/app/models/studentRiskModel.php';
    
    if (class_exists('StudentRiskModel')) {
        echo "<p style='color: green;'>✅ Clase StudentRiskModel existe</p>";
        
        $model = new StudentRiskModel($dbConn);
        
        // Obtener información de debug
        $debugInfo = $model->getDebugInfo();
        echo "<p><strong>Información de debug del modelo:</strong></p>";
        echo "<ul>";
        echo "<li>userIdColumn: " . $debugInfo['userIdColumn'] . "</li>";
        echo "<li>studentUserIdColumn: " . $debugInfo['studentUserIdColumn'] . "</li>";
        echo "</ul>";
        
        // Probar método getRiskStatistics con try-catch específico
        echo "<p><strong>Probando getRiskStatistics() con debug detallado...</strong></p>";
        
        try {
            $stats = $model->getRiskStatistics();
            echo "<p style='color: green;'>✅ getRiskStatistics() ejecutado correctamente</p>";
            echo "<pre>" . print_r($stats, true) . "</pre>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getRiskStatistics(): " . $e->getMessage() . "</p>";
            echo "<p><strong>Stack trace:</strong></p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Clase StudentRiskModel NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error general en StudentRiskModel: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>💡 Análisis</h2>";
echo "<p>Este script nos ayudará a identificar:</p>";
echo "<ul>";
echo "<li>Si las columnas existen con los nombres correctos</li>";
echo "<li>Si hay datos en las tablas</li>";
echo "<li>Si la consulta básica funciona</li>";
echo "<li>Dónde exactamente falla el modelo</li>";
echo "</ul>";
?> 