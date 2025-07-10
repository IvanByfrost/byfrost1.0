<?php
/**
 * Script para configurar una base de datos ByFrost limpia
 * Ejecuta las consultas básicas e inserciones de datos de ejemplo
 */

// Configuración de base de datos
$host = 'localhost';
$dbname = 'byfrost_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a la base de datos establecida\n\n";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    exit(1);
}

// Función para ejecutar archivo SQL
function executeSqlFile($pdo, $filename, $description) {
    if (!file_exists($filename)) {
        echo "❌ Archivo no encontrado: $filename\n";
        return false;
    }
    
    try {
        $sql = file_get_contents($filename);
        
        // Dividir el SQL en consultas individuales
        $queries = array_filter(array_map('trim', explode(';', $sql)));
        
        $successCount = 0;
        $totalCount = count($queries);
        
        echo "📝 Ejecutando $description...\n";
        
        foreach ($queries as $query) {
            if (!empty($query) && !preg_match('/^--/', $query)) {
                try {
                    $pdo->exec($query);
                    $successCount++;
                } catch (PDOException $e) {
                    // Ignorar errores de duplicados o consultas que ya existen
                    if (!strpos($e->getMessage(), 'Duplicate') && !strpos($e->getMessage(), 'already exists')) {
                        echo "⚠️  Error en consulta: " . substr($query, 0, 50) . "...\n";
                    }
                }
            }
        }
        
        echo "✅ $description completado: $successCount/$totalCount consultas exitosas\n\n";
        return true;
        
    } catch (Exception $e) {
        echo "❌ Error ejecutando $description: " . $e->getMessage() . "\n";
        return false;
    }
}

// Función para verificar estado de la base de datos
function checkDatabaseStatus($pdo) {
    echo "🔍 Verificando estado de la base de datos...\n";
    
    $checks = [
        'users' => 'SELECT COUNT(*) FROM users',
        'schools' => 'SELECT COUNT(*) FROM schools',
        'grades' => 'SELECT COUNT(*) FROM grades',
        'subjects' => 'SELECT COUNT(*) FROM subjects',
        'class_groups' => 'SELECT COUNT(*) FROM class_groups',
        'student_enrollment' => 'SELECT COUNT(*) FROM student_enrollment',
        'activities' => 'SELECT COUNT(*) FROM activities',
        'student_scores' => 'SELECT COUNT(*) FROM student_scores',
        'attendance' => 'SELECT COUNT(*) FROM attendance',
        'student_payments' => 'SELECT COUNT(*) FROM student_payments',
        'employees' => 'SELECT COUNT(*) FROM employees',
        'school_events' => 'SELECT COUNT(*) FROM school_events',
        'notifications' => 'SELECT COUNT(*) FROM notifications'
    ];
    
    $totalRecords = 0;
    
    foreach ($checks as $table => $query) {
        try {
            $stmt = $pdo->query($query);
            $count = $stmt->fetchColumn();
            echo "   📊 $table: $count registros\n";
            $totalRecords += $count;
        } catch (PDOException $e) {
            echo "   ❌ $table: Error - " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n📈 Total de registros en la base de datos: $totalRecords\n\n";
    return $totalRecords > 0;
}

// Función para limpiar datos existentes (opcional)
function cleanDatabase($pdo) {
    echo "🧹 Limpiando datos existentes...\n";
    
    $tables = [
        'notifications',
        'parent_meetings',
        'conduct_reports',
        'academic_reports',
        'student_scores',
        'attendance',
        'activities',
        'student_enrollment',
        'student_parents',
        'class_groups',
        'professor_subjects',
        'schedules',
        'academic_terms',
        'student_payments',
        'payroll_records',
        'payroll_periods',
        'employee_absences',
        'employee_overtime',
        'employee_bonuses',
        'employees',
        'school_events',
        'user_roles',
        'users'
    ];
    
    $deletedCount = 0;
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->prepare("DELETE FROM $table");
            $stmt->execute();
            $deletedCount += $stmt->rowCount();
            echo "   🗑️  $table: " . $stmt->rowCount() . " registros eliminados\n";
        } catch (PDOException $e) {
            echo "   ⚠️  $table: " . $e->getMessage() . "\n";
        }
    }
    
    echo "✅ Limpieza completada: $deletedCount registros eliminados\n\n";
}

// Función para mostrar menú de opciones
function showMenu() {
    echo "🎯 CONFIGURACIÓN DE BASE DE DATOS BYFROST\n";
    echo str_repeat("=", 50) . "\n";
    echo "1. Verificar estado actual\n";
    echo "2. Limpiar base de datos (eliminar todos los datos)\n";
    echo "3. Insertar datos básicos (recomendado)\n";
    echo "4. Ejecutar consultas de prueba\n";
    echo "5. Configuración completa (limpiar + insertar)\n";
    echo "6. Salir\n";
    echo str_repeat("=", 50) . "\n";
    echo "Seleccione una opción: ";
}

// Función para ejecutar consultas de prueba
function runTestQueries($pdo) {
    echo "🧪 Ejecutando consultas de prueba...\n\n";
    
    $testQueries = [
        'Usuarios totales' => 'SELECT COUNT(*) FROM users',
        'Escuelas activas' => 'SELECT COUNT(*) FROM schools WHERE is_active = 1',
        'Estudiantes matriculados' => 'SELECT COUNT(*) FROM student_enrollment WHERE is_active = 1',
        'Profesores activos' => 'SELECT COUNT(*) FROM user_roles WHERE role_type = "professor" AND is_active = 1',
        'Actividades creadas' => 'SELECT COUNT(*) FROM activities WHERE is_active = 1',
        'Calificaciones registradas' => 'SELECT COUNT(*) FROM student_scores WHERE is_active = 1',
        'Asistencia de hoy' => 'SELECT COUNT(*) FROM attendance WHERE attendance_date = CURDATE()',
        'Eventos próximos' => 'SELECT COUNT(*) FROM school_events WHERE start_date >= NOW() AND is_active = 1',
        'Pagos pendientes' => 'SELECT COUNT(*) FROM student_payments WHERE tuition_status IN ("pending", "overdue") AND is_active = 1',
        'Notificaciones no leídas' => 'SELECT COUNT(*) FROM notifications WHERE is_read = 0'
    ];
    
    foreach ($testQueries as $description => $query) {
        try {
            $stmt = $pdo->query($query);
            $count = $stmt->fetchColumn();
            echo "✅ $description: $count\n";
        } catch (PDOException $e) {
            echo "❌ $description: Error - " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n";
}

// Función principal
function main($pdo) {
    while (true) {
        showMenu();
        $option = trim(fgets(STDIN));
        
        switch ($option) {
            case '1':
                echo "\n";
                checkDatabaseStatus($pdo);
                break;
                
            case '2':
                echo "\n⚠️  ADVERTENCIA: Esto eliminará TODOS los datos de la base de datos.\n";
                echo "¿Está seguro? (s/N): ";
                $confirm = trim(fgets(STDIN));
                if (strtolower($confirm) === 's') {
                    cleanDatabase($pdo);
                } else {
                    echo "Operación cancelada.\n\n";
                }
                break;
                
            case '3':
                echo "\n";
                executeSqlFile($pdo, 'app/scripts/ByFrost_Basic_Inserts.sql', 'Inserción de datos básicos');
                checkDatabaseStatus($pdo);
                break;
                
            case '4':
                echo "\n";
                runTestQueries($pdo);
                break;
                
            case '5':
                echo "\n";
                echo "🔄 Configuración completa iniciada...\n\n";
                cleanDatabase($pdo);
                executeSqlFile($pdo, 'app/scripts/ByFrost_Basic_Inserts.sql', 'Inserción de datos básicos');
                checkDatabaseStatus($pdo);
                runTestQueries($pdo);
                echo "🎉 Configuración completa finalizada.\n\n";
                break;
                
            case '6':
                echo "👋 ¡Hasta luego!\n";
                exit(0);
                
            default:
                echo "❌ Opción inválida. Intente de nuevo.\n\n";
                break;
        }
    }
}

// Ejecutar script
echo "🚀 Iniciando configuración de base de datos ByFrost...\n\n";

// Verificar que los archivos necesarios existen
$requiredFiles = [
    'app/scripts/ByFrost_Basic_Inserts.sql',
    'app/scripts/ByFrost_Basic_Queries.sql'
];

foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        echo "❌ Error: Archivo requerido no encontrado: $file\n";
        exit(1);
    }
}

echo "✅ Archivos requeridos encontrados.\n\n";

// Ejecutar menú principal
main($pdo);
?> 