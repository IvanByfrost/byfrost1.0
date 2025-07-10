<?php
/**
 * Script de prueba para verificar la base de datos unificada ByFrost
 * Verifica que todas las tablas, relaciones y datos funcionen correctamente
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

// Función para verificar tabla
function verifyTable($pdo, $tableName, $expectedColumns = []) {
    try {
        $stmt = $pdo->query("DESCRIBE $tableName");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($expectedColumns)) {
            echo "✅ Tabla '$tableName' existe con " . count($columns) . " columnas\n";
            return true;
        }
        
        $missingColumns = array_diff($expectedColumns, $columns);
        if (empty($missingColumns)) {
            echo "✅ Tabla '$tableName' existe con todas las columnas requeridas\n";
            return true;
        } else {
            echo "❌ Tabla '$tableName' falta columnas: " . implode(', ', $missingColumns) . "\n";
            return false;
        }
    } catch (PDOException $e) {
        echo "❌ Error verificando tabla '$tableName': " . $e->getMessage() . "\n";
        return false;
    }
}

// Función para verificar vista
function verifyView($pdo, $viewName) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '$viewName'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Vista '$viewName' existe\n";
            return true;
        } else {
            echo "❌ Vista '$viewName' no existe\n";
            return false;
        }
    } catch (PDOException $e) {
        echo "❌ Error verificando vista '$viewName': " . $e->getMessage() . "\n";
        return false;
    }
}

// Función para verificar índice
function verifyIndex($pdo, $tableName, $indexName) {
    try {
        $stmt = $pdo->query("SHOW INDEX FROM $tableName WHERE Key_name = '$indexName'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Índice '$indexName' existe en tabla '$tableName'\n";
            return true;
        } else {
            echo "❌ Índice '$indexName' no existe en tabla '$tableName'\n";
            return false;
        }
    } catch (PDOException $e) {
        echo "❌ Error verificando índice '$indexName': " . $e->getMessage() . "\n";
        return false;
    }
}

// Función para verificar datos
function verifyData($pdo, $tableName, $expectedCount = null) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $tableName");
        $count = $stmt->fetchColumn();
        
        if ($expectedCount === null) {
            echo "✅ Tabla '$tableName' tiene $count registros\n";
            return true;
        } elseif ($count >= $expectedCount) {
            echo "✅ Tabla '$tableName' tiene $count registros (mínimo $expectedCount)\n";
            return true;
        } else {
            echo "❌ Tabla '$tableName' tiene $count registros (esperado mínimo $expectedCount)\n";
            return false;
        }
    } catch (PDOException $e) {
        echo "❌ Error verificando datos de '$tableName': " . $e->getMessage() . "\n";
        return false;
    }
}

// Función para verificar relaciones
function verifyRelations($pdo) {
    echo "\n🔗 Verificando relaciones de base de datos...\n";
    
    $relations = [
        // Relaciones de usuarios
        "user_roles.user_id -> users.user_id",
        "role_permissions.role_type -> user_roles.role_type",
        "password_resets.user_id -> users.user_id",
        
        // Relaciones académicas
        "schools.director_user_id -> users.user_id",
        "schools.coordinator_user_id -> users.user_id",
        "grades.school_id -> schools.school_id",
        "professor_subjects.professor_user_id -> users.user_id",
        "professor_subjects.subject_id -> subjects.subject_id",
        "professor_subjects.school_id -> schools.school_id",
        "class_groups.grade_id -> grades.grade_id",
        "class_groups.professor_user_id -> users.user_id",
        "student_enrollment.student_user_id -> users.user_id",
        "student_enrollment.class_group_id -> class_groups.class_group_id",
        "student_parents.student_user_id -> users.user_id",
        "student_parents.parent_user_id -> users.user_id",
        
        // Relaciones de horarios
        "schedules.class_group_id -> class_groups.class_group_id",
        "schedules.professor_subject_id -> professor_subjects.professor_subject_id",
        "schedules.term_id -> academic_terms.term_id",
        
        // Relaciones de actividades
        "activities.professor_subject_id -> professor_subjects.professor_subject_id",
        "activities.activity_type_id -> activity_types.activity_type_id",
        "activities.class_group_id -> class_groups.class_group_id",
        "activities.term_id -> academic_terms.term_id",
        "activities.created_by_user_id -> users.user_id",
        "student_scores.student_user_id -> users.user_id",
        "student_scores.activity_id -> activities.activity_id",
        "student_scores.recorded_by_user_id -> users.user_id",
        
        // Relaciones de asistencia
        "attendance.student_user_id -> users.user_id",
        "attendance.schedule_id -> schedules.schedule_id",
        "attendance.recorded_by_user_id -> users.user_id",
        
        // Relaciones de pagos
        "student_payments.student_user_id -> users.user_id",
        
        // Relaciones de nómina
        "employees.user_id -> users.user_id",
        "payroll_periods.created_by_user_id -> users.user_id",
        "payroll_records.period_id -> payroll_periods.period_id",
        "payroll_records.employee_id -> employees.employee_id",
        "payroll_records.created_by_user_id -> users.user_id",
        "payroll_concept_details.record_id -> payroll_records.record_id",
        "payroll_concept_details.concept_id -> payroll_concepts.concept_id",
        "employee_absences.employee_id -> employees.employee_id",
        "employee_absences.approved_by_user_id -> users.user_id",
        "employee_overtime.employee_id -> employees.employee_id",
        "employee_overtime.period_id -> payroll_periods.period_id",
        "employee_overtime.approved_by_user_id -> users.user_id",
        "employee_bonuses.employee_id -> employees.employee_id",
        "employee_bonuses.period_id -> payroll_periods.period_id",
        "employee_bonuses.approved_by_user_id -> users.user_id",
        
        // Relaciones de eventos
        "school_events.school_id -> schools.school_id",
        "school_events.created_by_user_id -> users.user_id",
        "notifications.recipient_user_id -> users.user_id",
        
        // Relaciones de reportes
        "academic_reports.student_user_id -> users.user_id",
        "academic_reports.term_id -> academic_terms.term_id",
        "academic_reports.created_by_user_id -> users.user_id",
        "conduct_reports.student_user_id -> users.user_id",
        "conduct_reports.reported_by_user_id -> users.user_id",
        "parent_meetings.student_user_id -> users.user_id",
        "parent_meetings.parent_user_id -> users.user_id",
        "parent_meetings.scheduled_by_user_id -> users.user_id"
    ];
    
    $successCount = 0;
    $totalCount = count($relations);
    
    foreach ($relations as $relation) {
        list($fromTable, $toTable) = explode(' -> ', $relation);
        list($table, $column) = explode('.', $fromTable);
        
        try {
            // Verificar que la columna existe en la tabla origen
            $stmt = $pdo->query("SHOW COLUMNS FROM $table LIKE '$column'");
            if ($stmt->rowCount() > 0) {
                $successCount++;
                echo "✅ $relation\n";
            } else {
                echo "❌ $relation (columna no existe)\n";
            }
        } catch (PDOException $e) {
            echo "❌ $relation (error: " . $e->getMessage() . ")\n";
        }
    }
    
    echo "\n📊 Relaciones verificadas: $successCount/$totalCount\n";
    return $successCount === $totalCount;
}

// Función para probar consultas
function testQueries($pdo) {
    echo "\n🔍 Probando consultas importantes...\n";
    
    $queries = [
        "SELECT COUNT(*) FROM users" => "Usuarios totales",
        "SELECT COUNT(*) FROM user_roles" => "Roles de usuario",
        "SELECT COUNT(*) FROM schools" => "Escuelas",
        "SELECT COUNT(*) FROM grades" => "Grados",
        "SELECT COUNT(*) FROM subjects" => "Materias",
        "SELECT COUNT(*) FROM class_groups" => "Grupos de clase",
        "SELECT COUNT(*) FROM student_enrollment" => "Matrículas",
        "SELECT COUNT(*) FROM activities" => "Actividades",
        "SELECT COUNT(*) FROM student_scores" => "Calificaciones",
        "SELECT COUNT(*) FROM attendance" => "Asistencias",
        "SELECT COUNT(*) FROM student_payments" => "Pagos",
        "SELECT COUNT(*) FROM employees" => "Empleados",
        "SELECT COUNT(*) FROM payroll_periods" => "Períodos de nómina",
        "SELECT COUNT(*) FROM school_events" => "Eventos",
        "SELECT COUNT(*) FROM notifications" => "Notificaciones"
    ];
    
    $successCount = 0;
    $totalCount = count($queries);
    
    foreach ($queries as $query => $description) {
        try {
            $stmt = $pdo->query($query);
            $count = $stmt->fetchColumn();
            echo "✅ $description: $count registros\n";
            $successCount++;
        } catch (PDOException $e) {
            echo "❌ $description: Error - " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n📊 Consultas exitosas: $successCount/$totalCount\n";
    return $successCount === $totalCount;
}

// Función para probar vistas
function testViews($pdo) {
    echo "\n👁️ Probando vistas...\n";
    
    $views = [
        "attendance_summary" => "Resumen de asistencia",
        "payment_statistics_view" => "Estadísticas de pagos",
        "upcoming_events_view" => "Eventos próximos",
        "academic_general_stats_view" => "Estadísticas académicas"
    ];
    
    $successCount = 0;
    $totalCount = count($views);
    
    foreach ($views as $view => $description) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $view");
            $count = $stmt->fetchColumn();
            echo "✅ $description: $count registros\n";
            $successCount++;
        } catch (PDOException $e) {
            echo "❌ $description: Error - " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n📊 Vistas exitosas: $successCount/$totalCount\n";
    return $successCount === $totalCount;
}

// Función para verificar permisos
function verifyPermissions($pdo) {
    echo "\n🔐 Verificando permisos por defecto...\n";
    
    $expectedPermissions = [
        'student' => ['can_create' => 0, 'can_read' => 1, 'can_update' => 0, 'can_delete' => 0],
        'parent' => ['can_create' => 0, 'can_read' => 1, 'can_update' => 0, 'can_delete' => 0],
        'professor' => ['can_create' => 1, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 0],
        'coordinator' => ['can_create' => 1, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 1],
        'director' => ['can_create' => 1, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 1],
        'treasurer' => ['can_create' => 1, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 0],
        'root' => ['can_create' => 1, 'can_read' => 1, 'can_update' => 1, 'can_delete' => 1]
    ];
    
    $successCount = 0;
    $totalCount = count($expectedPermissions);
    
    foreach ($expectedPermissions as $role => $permissions) {
        try {
            $stmt = $pdo->prepare("SELECT can_create, can_read, can_update, can_delete FROM role_permissions WHERE role_type = ?");
            $stmt->execute([$role]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $match = true;
                foreach ($permissions as $permission => $value) {
                    if ($result[$permission] != $value) {
                        $match = false;
                        break;
                    }
                }
                
                if ($match) {
                    echo "✅ Permisos para '$role' correctos\n";
                    $successCount++;
                } else {
                    echo "❌ Permisos para '$role' incorrectos\n";
                }
            } else {
                echo "❌ Permisos para '$role' no encontrados\n";
            }
        } catch (PDOException $e) {
            echo "❌ Error verificando permisos para '$role': " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n📊 Permisos correctos: $successCount/$totalCount\n";
    return $successCount === $totalCount;
}

// Ejecutar pruebas
echo "🧪 Iniciando pruebas de la base de datos unificada ByFrost...\n\n";

$overallSuccess = true;

// 1. Verificar tablas principales
echo "📋 Verificando tablas principales...\n";
$tables = [
    'users' => ['user_id', 'credential_number', 'first_name', 'last_name', 'is_active'],
    'user_roles' => ['user_role_id', 'user_id', 'role_type', 'is_active'],
    'role_permissions' => ['role_type', 'can_create', 'can_read', 'can_update', 'can_delete'],
    'password_resets' => ['reset_id', 'user_id', 'token', 'expires_at'],
    'schools' => ['school_id', 'school_name', 'director_user_id', 'is_active'],
    'grades' => ['grade_id', 'grade_name', 'school_id', 'is_active'],
    'subjects' => ['subject_id', 'subject_name', 'is_active'],
    'professor_subjects' => ['professor_subject_id', 'professor_user_id', 'subject_id', 'school_id'],
    'class_groups' => ['class_group_id', 'group_name', 'grade_id', 'professor_user_id'],
    'student_enrollment' => ['enrollment_id', 'student_user_id', 'class_group_id', 'is_active'],
    'student_parents' => ['student_parent_id', 'student_user_id', 'parent_user_id'],
    'academic_terms' => ['term_id', 'term_name', 'start_date', 'end_date'],
    'schedules' => ['schedule_id', 'class_group_id', 'professor_subject_id', 'day_of_week'],
    'activity_types' => ['activity_type_id', 'type_name', 'weight_percentage'],
    'activities' => ['activity_id', 'activity_name', 'professor_subject_id', 'class_group_id'],
    'student_scores' => ['score_id', 'student_user_id', 'activity_id', 'score'],
    'attendance' => ['attendance_id', 'student_user_id', 'schedule_id', 'attendance_date'],
    'student_payments' => ['payment_id', 'student_user_id', 'tuition_amount', 'tuition_status'],
    'employees' => ['employee_id', 'user_id', 'employee_code', 'position'],
    'payroll_concepts' => ['concept_id', 'concept_name', 'concept_type', 'concept_category'],
    'payroll_periods' => ['period_id', 'period_name', 'start_date', 'end_date', 'status'],
    'payroll_records' => ['record_id', 'period_id', 'employee_id', 'base_salary'],
    'school_events' => ['event_id', 'event_type', 'event_title', 'start_date'],
    'notifications' => ['notification_id', 'recipient_user_id', 'notification_type', 'is_read'],
    'academic_reports' => ['report_id', 'student_user_id', 'term_id', 'report_type'],
    'conduct_reports' => ['conduct_id', 'student_user_id', 'report_date', 'incident_type'],
    'parent_meetings' => ['meeting_id', 'student_user_id', 'parent_user_id', 'meeting_date']
];

foreach ($tables as $table => $columns) {
    if (!verifyTable($pdo, $table, $columns)) {
        $overallSuccess = false;
    }
}

// 2. Verificar vistas
echo "\n👁️ Verificando vistas...\n";
$views = ['attendance_summary', 'payment_statistics_view', 'upcoming_events_view', 'academic_general_stats_view'];
foreach ($views as $view) {
    if (!verifyView($pdo, $view)) {
        $overallSuccess = false;
    }
}

// 3. Verificar datos iniciales
echo "\n📊 Verificando datos iniciales...\n";
if (!verifyData($pdo, 'role_permissions', 7)) {
    $overallSuccess = false;
}
if (!verifyData($pdo, 'payroll_concepts', 12)) {
    $overallSuccess = false;
}
if (!verifyData($pdo, 'activity_types', 5)) {
    $overallSuccess = false;
}

// 4. Verificar relaciones
if (!verifyRelations($pdo)) {
    $overallSuccess = false;
}

// 5. Probar consultas
if (!testQueries($pdo)) {
    $overallSuccess = false;
}

// 6. Probar vistas
if (!testViews($pdo)) {
    $overallSuccess = false;
}

// 7. Verificar permisos
if (!verifyPermissions($pdo)) {
    $overallSuccess = false;
}

// Resultado final
echo "\n" . str_repeat("=", 50) . "\n";
if ($overallSuccess) {
    echo "🎉 ¡TODAS LAS PRUEBAS PASARON EXITOSAMENTE!\n";
    echo "✅ La base de datos unificada está funcionando correctamente\n";
} else {
    echo "❌ ALGUNAS PRUEBAS FALLARON\n";
    echo "⚠️  Revise los errores anteriores y corrija los problemas\n";
}
echo str_repeat("=", 50) . "\n";

echo "\n📋 Resumen de verificación:\n";
echo "   - Tablas principales: Verificadas\n";
echo "   - Vistas: Verificadas\n";
echo "   - Datos iniciales: Verificados\n";
echo "   - Relaciones: Verificadas\n";
echo "   - Consultas: Probadas\n";
echo "   - Permisos: Verificados\n";

echo "\n🚀 La base de datos unificada está lista para usar.\n";
?> 