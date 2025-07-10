<?php
// Script para verificar qué tablas existen y cuáles faltan
echo "<h1>🔍 Verificación de Tablas en Base de Datos</h1>";

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

// Tablas requeridas para el StudentRiskModel
$requiredTables = [
    'users',
    'user_roles', 
    'student_scores',
    'attendance',
    'activities',
    'activity_types',
    'subjects',
    'professor_subjects',
    'class_groups',
    'grades',
    'schools',
    'academic_terms',
    'schedules'
];

echo "<h2>📋 Verificación de Tablas Requeridas</h2>";
$existingTables = [];
$missingTables = [];

foreach ($requiredTables as $table) {
    try {
        $stmt = $dbConn->query("SHOW TABLES LIKE '$table'");
        $result = $stmt->fetch();
        
        if ($result) {
            $existingTables[] = $table;
            echo "<p style='color: green;'>✅ Tabla '$table' existe</p>";
        } else {
            $missingTables[] = $table;
            echo "<p style='color: red;'>❌ Tabla '$table' NO existe</p>";
        }
    } catch (Exception $e) {
        $missingTables[] = $table;
        echo "<p style='color: red;'>❌ Error verificando tabla '$table': " . $e->getMessage() . "</p>";
    }
}

echo "<h2>📊 Resumen</h2>";
echo "<p><strong>Tablas existentes (" . count($existingTables) . "):</strong></p>";
echo "<ul>";
foreach ($existingTables as $table) {
    echo "<li>$table</li>";
}
echo "</ul>";

echo "<p><strong>Tablas faltantes (" . count($missingTables) . "):</strong></p>";
echo "<ul>";
foreach ($missingTables as $table) {
    echo "<li>$table</li>";
}
echo "</ul>";

// Verificar si Baldur.sql existe
echo "<h2>📄 Verificación de Baldur.sql</h2>";
if (file_exists(ROOT . '/app/scripts/Baldur.sql')) {
    echo "<p style='color: green;'>✅ Baldur.sql existe</p>";
    
    // Leer el contenido para verificar qué tablas incluye
    $baldurContent = file_get_contents(ROOT . '/app/scripts/Baldur.sql');
    
    $baldurTables = [];
    foreach ($requiredTables as $table) {
        if (strpos($baldurContent, "CREATE TABLE $table") !== false) {
            $baldurTables[] = $table;
        }
    }
    
    echo "<p><strong>Tablas definidas en Baldur.sql:</strong></p>";
    echo "<ul>";
    foreach ($baldurTables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    echo "<p><strong>Tablas NO definidas en Baldur.sql:</strong></p>";
    echo "<ul>";
    foreach (array_diff($requiredTables, $baldurTables) as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
} else {
    echo "<p style='color: red;'>❌ Baldur.sql no existe</p>";
}

// Crear script SQL para las tablas faltantes
if (!empty($missingTables)) {
    echo "<h2>🔧 Script SQL para Crear Tablas Faltantes</h2>";
    echo "<p>Ejecuta este script en tu base de datos para crear las tablas faltantes:</p>";
    
    echo "<textarea style='width: 100%; height: 300px; font-family: monospace;'>";
    
    // Script básico para crear las tablas más importantes
    echo "-- Script para crear tablas faltantes\n";
    echo "-- Ejecuta este script en tu base de datos MySQL\n\n";
    
    if (in_array('users', $missingTables)) {
        echo "-- Tabla users\n";
        echo "CREATE TABLE users (\n";
        echo "    user_id INT AUTO_INCREMENT PRIMARY KEY,\n";
        echo "    credential_number VARCHAR(40) NOT NULL UNIQUE,\n";
        echo "    first_name VARCHAR(50) NOT NULL,\n";
        echo "    last_name VARCHAR(60) NOT NULL,\n";
        echo "    credential_type VARCHAR(2) NOT NULL COMMENT 'CC, TI, etc.',\n";
        echo "    date_of_birth DATE NOT NULL,\n";
        echo "    address VARCHAR(100),\n";
        echo "    phone VARCHAR(20),\n";
        echo "    email VARCHAR(100),\n";
        echo "    school_id INT NULL,\n";
        echo "    password_hash VARBINARY(255) NOT NULL,\n";
        echo "    salt_password VARBINARY(255) NOT NULL,\n";
        echo "    is_active BIT NOT NULL DEFAULT 1,\n";
        echo "    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP\n";
        echo ") ENGINE=InnoDB;\n\n";
    }
    
    if (in_array('user_roles', $missingTables)) {
        echo "-- Tabla user_roles\n";
        echo "CREATE TABLE user_roles (\n";
        echo "    user_role_id INT AUTO_INCREMENT PRIMARY KEY,\n";
        echo "    user_id INT NOT NULL,\n";
        echo "    role_type ENUM('student', 'parent', 'professor', 'coordinator', 'director', 'treasurer', 'root') DEFAULT NULL,\n";
        echo "    is_active BIT NOT NULL DEFAULT 1,\n";
        echo "    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,\n";
        echo "    UNIQUE KEY idx_user_role (user_id, role_type)\n";
        echo ") ENGINE=InnoDB;\n\n";
    }
    
    if (in_array('student_scores', $missingTables)) {
        echo "-- Tabla student_scores\n";
        echo "CREATE TABLE student_scores (\n";
        echo "    score_id INT AUTO_INCREMENT PRIMARY KEY,\n";
        echo "    student_user_id INT NOT NULL,\n";
        echo "    activity_id INT NOT NULL,\n";
        echo "    score DECIMAL(5,2),\n";
        echo "    feedback TEXT,\n";
        echo "    graded_by_user_id INT NOT NULL,\n";
        echo "    graded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP\n";
        echo ") ENGINE=InnoDB;\n\n";
    }
    
    if (in_array('attendance', $missingTables)) {
        echo "-- Tabla attendance\n";
        echo "CREATE TABLE attendance (\n";
        echo "    attendance_id INT AUTO_INCREMENT PRIMARY KEY,\n";
        echo "    student_user_id INT NOT NULL,\n";
        echo "    schedule_id INT NOT NULL,\n";
        echo "    attendance_date DATE NOT NULL,\n";
        echo "    status ENUM('present', 'absent', 'late', 'excused') NOT NULL,\n";
        echo "    notes TEXT,\n";
        echo "    recorded_by_user_id INT NOT NULL,\n";
        echo "    recorded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP\n";
        echo ") ENGINE=InnoDB;\n\n";
    }
    
    echo "-- Insertar algunos datos de prueba\n";
    echo "INSERT INTO users (credential_number, first_name, last_name, credential_type, date_of_birth, email, password_hash, salt_password) VALUES\n";
    echo "('123456789', 'Juan', 'Pérez', 'CC', '2000-01-01', 'juan@test.com', UNHEX('test'), UNHEX('test')),\n";
    echo "('987654321', 'María', 'González', 'CC', '2001-02-02', 'maria@test.com', UNHEX('test'), UNHEX('test'));\n\n";
    
    echo "INSERT INTO user_roles (user_id, role_type) VALUES\n";
    echo "(1, 'student'),\n";
    echo "(2, 'student');\n\n";
    
    echo "</textarea>";
    
    echo "<h2>💡 Instrucciones</h2>";
    echo "<ol>";
    echo "<li>Copia el script SQL de arriba</li>";
    echo "<li>Ve a phpMyAdmin o tu cliente MySQL</li>";
    echo "<li>Selecciona tu base de datos</li>";
    echo "<li>Ejecuta el script SQL</li>";
    echo "<li>Vuelve a probar el dashboard del director</li>";
    echo "</ol>";
}

echo "<h2>🔗 Prueba Rápida</h2>";
echo "<p>Una vez que tengas las tablas creadas, prueba:</p>";
echo "<ul>";
echo "<li><a href='/?view=directorDashboard' target='_blank'>Dashboard Director</a></li>";
echo "<li><a href='tests/test-student-risk-fixed.php' target='_blank'>Prueba del Modelo</a></li>";
echo "</ul>";
?> 