<?php
/**
 * Script de migración para unificar la base de datos ByFrost
 * Actualiza todas las referencias de tablas y campos en el código
 */

// Configuración de base de datos
$host = 'localhost';
$dbname = 'byfrost_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a la base de datos establecida\n";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    exit(1);
}

// Mapeo de cambios de tablas
$table_mappings = [
    // Tablas que se mantienen igual
    'users' => 'users',
    'user_roles' => 'user_roles',
    'role_permissions' => 'role_permissions',
    'password_resets' => 'password_resets',
    'schools' => 'schools',
    'grades' => 'grades',
    'subjects' => 'subjects',
    'professor_subjects' => 'professor_subjects',
    'class_groups' => 'class_groups',
    'student_enrollment' => 'student_enrollment',
    'student_parents' => 'student_parents',
    'academic_terms' => 'academic_terms',
    'schedules' => 'schedules',
    'activity_types' => 'activity_types',
    'activities' => 'activities',
    'student_scores' => 'student_scores',
    'attendance' => 'attendance',
    'employees' => 'employees',
    'payroll_concepts' => 'payroll_concepts',
    'payroll_periods' => 'payroll_periods',
    'payroll_records' => 'payroll_records',
    'payroll_concept_details' => 'payroll_concept_details',
    'employee_absences' => 'employee_absences',
    'employee_overtime' => 'employee_overtime',
    'employee_bonuses' => 'employee_bonuses',
    'school_events' => 'school_events',
    'notifications' => 'notifications',
    'academic_reports' => 'academic_reports',
    'conduct_reports' => 'conduct_reports',
    'parent_meetings' => 'parent_meetings',
    
    // Tablas que cambian de nombre
    'student_account' => 'student_payments',
    'event_school' => 'school_events',
    'academic_term' => 'academic_terms',
    'subject_score' => 'student_scores',
    'student' => 'users', // Se unifica con users
    'teacher' => 'users', // Se unifica con users
];

// Mapeo de cambios de campos
$field_mappings = [
    // Campos que se mantienen igual
    'user_id' => 'user_id',
    'credential_number' => 'credential_number',
    'first_name' => 'first_name',
    'last_name' => 'last_name',
    'email' => 'email',
    'phone' => 'phone',
    'is_active' => 'is_active',
    'created_at' => 'created_at',
    'updated_at' => 'updated_at',
    
    // Campos que cambian
    'student_id' => 'student_user_id',
    'teacher_id' => 'professor_user_id',
    'academic_term_id' => 'term_id',
    'payment_id' => 'payment_id',
    'tuition_amount' => 'tuition_amount',
    'tuition_status' => 'tuition_status',
    'payment_due_date' => 'payment_due_date',
    'payment_date' => 'payment_date',
    'payment_method' => 'payment_method',
    'payment_notes' => 'payment_notes',
    'type_event' => 'event_type',
    'title_event' => 'event_title',
    'description_event' => 'event_description',
    'start_date_event' => 'start_date',
    'end_date_event' => 'end_date',
    'location_event' => 'event_location',
    'score' => 'score',
    'score_date' => 'score_date',
    'comments' => 'comments',
    'student_name' => 'CONCAT(first_name, " ", last_name)',
    'teacher_name' => 'CONCAT(first_name, " ", last_name)',
];

// Función para actualizar archivos PHP
function updatePhpFiles($tableMappings, $fieldMappings) {
    $phpFiles = glob('app/**/*.php');
    $updatedFiles = 0;
    
    foreach ($phpFiles as $file) {
        $content = file_get_contents($file);
        $originalContent = $content;
        
        // Actualizar nombres de tablas
        foreach ($tableMappings as $oldTable => $newTable) {
            if ($oldTable !== $newTable) {
                $content = preg_replace(
                    '/\b' . preg_quote($oldTable, '/') . '\b/',
                    $newTable,
                    $content
                );
            }
        }
        
        // Actualizar nombres de campos
        foreach ($fieldMappings as $oldField => $newField) {
            if ($oldField !== $newField) {
                $content = preg_replace(
                    '/\b' . preg_quote($oldField, '/') . '\b/',
                    $newField,
                    $content
                );
            }
        }
        
        // Actualizar consultas SQL específicas
        $content = updateSqlQueries($content);
        
        if ($content !== $originalContent) {
            file_put_contents($file, $content);
            $updatedFiles++;
            echo "✅ Actualizado: $file\n";
        }
    }
    
    return $updatedFiles;
}

// Función para actualizar consultas SQL específicas
function updateSqlQueries($content) {
    // Actualizar consultas de student_account a student_payments
    $content = preg_replace(
        '/FROM\s+student_account\b/',
        'FROM student_payments',
        $content
    );
    
    $content = preg_replace(
        '/JOIN\s+student_account\b/',
        'JOIN student_payments',
        $content
    );
    
    // Actualizar consultas de event_school a school_events
    $content = preg_replace(
        '/FROM\s+event_school\b/',
        'FROM school_events',
        $content
    );
    
    $content = preg_replace(
        '/JOIN\s+event_school\b/',
        'JOIN school_events',
        $content
    );
    
    // Actualizar referencias de student_id a student_user_id
    $content = preg_replace(
        '/\bstudent_id\s*=\s*(\d+)/',
        'student_user_id = $1',
        $content
    );
    
    // Actualizar referencias de teacher_id a professor_user_id
    $content = preg_replace(
        '/\bteacher_id\s*=\s*(\d+)/',
        'professor_user_id = $1',
        $content
    );
    
    return $content;
}

// Función para actualizar archivos JavaScript
function updateJsFiles($tableMappings, $fieldMappings) {
    $jsFiles = glob('app/resources/js/*.js');
    $updatedFiles = 0;
    
    foreach ($jsFiles as $file) {
        $content = file_get_contents($file);
        $originalContent = $content;
        
        // Actualizar nombres de tablas en comentarios o strings
        foreach ($tableMappings as $oldTable => $newTable) {
            if ($oldTable !== $newTable) {
                $content = str_replace($oldTable, $newTable, $content);
            }
        }
        
        // Actualizar nombres de campos en comentarios o strings
        foreach ($fieldMappings as $oldField => $newField) {
            if ($oldField !== $newField) {
                $content = str_replace($oldField, $newField, $content);
            }
        }
        
        if ($content !== $originalContent) {
            file_put_contents($file, $content);
            $updatedFiles++;
            echo "✅ Actualizado: $file\n";
        }
    }
    
    return $updatedFiles;
}

// Función para verificar la integridad de la base de datos
function verifyDatabaseIntegrity($pdo) {
    echo "\n🔍 Verificando integridad de la base de datos...\n";
    
    $requiredTables = [
        'users', 'user_roles', 'role_permissions', 'password_resets',
        'schools', 'grades', 'subjects', 'professor_subjects', 'class_groups',
        'student_enrollment', 'student_parents', 'academic_terms', 'schedules',
        'activity_types', 'activities', 'student_scores', 'attendance',
        'student_payments', 'employees', 'payroll_concepts', 'payroll_periods',
        'payroll_records', 'payroll_concept_details', 'employee_absences',
        'employee_overtime', 'employee_bonuses', 'school_events', 'notifications',
        'academic_reports', 'conduct_reports', 'parent_meetings'
    ];
    
    $missingTables = [];
    
    foreach ($requiredTables as $table) {
        try {
            $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() === 0) {
                $missingTables[] = $table;
            }
        } catch (PDOException $e) {
            $missingTables[] = $table;
        }
    }
    
    if (empty($missingTables)) {
        echo "✅ Todas las tablas requeridas están presentes\n";
        return true;
    } else {
        echo "❌ Tablas faltantes:\n";
        foreach ($missingTables as $table) {
            echo "   - $table\n";
        }
        return false;
    }
}

// Función para crear índices faltantes
function createMissingIndexes($pdo) {
    echo "\n🔧 Creando índices faltantes...\n";
    
    $indexes = [
        "CREATE INDEX IF NOT EXISTS idx_credential ON users(credential_number)",
        "CREATE INDEX IF NOT EXISTS idx_email ON users(email)",
        "CREATE INDEX IF NOT EXISTS idx_active ON users(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_role_type ON user_roles(role_type)",
        "CREATE INDEX IF NOT EXISTS idx_active_role ON user_roles(is_active, role_type)",
        "CREATE INDEX IF NOT EXISTS idx_school_active ON schools(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_grade_school ON grades(school_id, is_active)",
        "CREATE INDEX IF NOT EXISTS idx_subject_active ON subjects(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_professor_active ON professor_subjects(professor_user_id, is_active)",
        "CREATE INDEX IF NOT EXISTS idx_group_grade ON class_groups(grade_id, is_active)",
        "CREATE INDEX IF NOT EXISTS idx_group_professor ON class_groups(professor_user_id, is_active)",
        "CREATE INDEX IF NOT EXISTS idx_enrollment_active ON student_enrollment(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_student_code ON student_enrollment(student_code)",
        "CREATE INDEX IF NOT EXISTS idx_primary_contact ON student_parents(is_primary_contact)",
        "CREATE INDEX IF NOT EXISTS idx_term_active ON academic_terms(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_term_dates ON academic_terms(start_date, end_date)",
        "CREATE INDEX IF NOT EXISTS idx_schedule_class_day ON schedules(class_group_id, day_of_week)",
        "CREATE INDEX IF NOT EXISTS idx_schedule_active ON schedules(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_type_active ON activity_types(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_activities_group_term ON activities(class_group_id, term_id)",
        "CREATE INDEX IF NOT EXISTS idx_activities_active ON activities(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_student_scores_student ON student_scores(student_user_id)",
        "CREATE INDEX IF NOT EXISTS idx_score_active ON student_scores(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_score_date ON student_scores(score_date)",
        "CREATE INDEX IF NOT EXISTS idx_student_user_id ON attendance(student_user_id)",
        "CREATE INDEX IF NOT EXISTS idx_schedule_id ON attendance(schedule_id)",
        "CREATE INDEX IF NOT EXISTS idx_attendance_date ON attendance(attendance_date)",
        "CREATE INDEX IF NOT EXISTS idx_status ON attendance(status)",
        "CREATE INDEX IF NOT EXISTS idx_student_date ON attendance(student_user_id, attendance_date)",
        "CREATE INDEX IF NOT EXISTS idx_student_date_status ON attendance(student_user_id, attendance_date, status)",
        "CREATE INDEX IF NOT EXISTS idx_student_payment ON student_payments(student_user_id)",
        "CREATE INDEX IF NOT EXISTS idx_tuition_status ON student_payments(tuition_status)",
        "CREATE INDEX IF NOT EXISTS idx_payment_due_date ON student_payments(payment_due_date)",
        "CREATE INDEX IF NOT EXISTS idx_payment_active ON student_payments(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_employee_code ON employees(employee_code)",
        "CREATE INDEX IF NOT EXISTS idx_position ON employees(position)",
        "CREATE INDEX IF NOT EXISTS idx_department ON employees(department)",
        "CREATE INDEX IF NOT EXISTS idx_employee_active ON employees(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_concept_type ON payroll_concepts(concept_type)",
        "CREATE INDEX IF NOT EXISTS idx_concept_category ON payroll_concepts(concept_category)",
        "CREATE INDEX IF NOT EXISTS idx_concept_active ON payroll_concepts(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_period_dates ON payroll_periods(start_date, end_date)",
        "CREATE INDEX IF NOT EXISTS idx_status ON payroll_periods(status)",
        "CREATE INDEX IF NOT EXISTS idx_period_employee ON payroll_records(period_id, employee_id)",
        "CREATE INDEX IF NOT EXISTS idx_record_concept ON payroll_concept_details(record_id, concept_id)",
        "CREATE INDEX IF NOT EXISTS idx_employee_dates ON employee_absences(employee_id, start_date, end_date)",
        "CREATE INDEX IF NOT EXISTS idx_employee_period ON employee_overtime(employee_id, period_id)",
        "CREATE INDEX IF NOT EXISTS idx_start_date ON school_events(start_date)",
        "CREATE INDEX IF NOT EXISTS idx_event_type ON school_events(event_type)",
        "CREATE INDEX IF NOT EXISTS idx_event_active ON school_events(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_school_events ON school_events(school_id, is_active)",
        "CREATE INDEX IF NOT EXISTS idx_recipient_read ON notifications(recipient_user_id, is_read)",
        "CREATE INDEX IF NOT EXISTS idx_notification_type ON notifications(notification_type)",
        "CREATE INDEX IF NOT EXISTS idx_created_at ON notifications(created_at)",
        "CREATE INDEX IF NOT EXISTS idx_student_term ON academic_reports(student_user_id, term_id)",
        "CREATE INDEX IF NOT EXISTS idx_report_active ON academic_reports(is_active)",
        "CREATE INDEX IF NOT EXISTS idx_conduct_student ON conduct_reports(student_user_id)",
        "CREATE INDEX IF NOT EXISTS idx_conduct_date ON conduct_reports(report_date)",
        "CREATE INDEX IF NOT EXISTS idx_conduct_severity ON conduct_reports(severity_level)",
        "CREATE INDEX IF NOT EXISTS idx_meeting_student ON parent_meetings(student_user_id)",
        "CREATE INDEX IF NOT EXISTS idx_meeting_date ON parent_meetings(meeting_date)",
        "CREATE INDEX IF NOT EXISTS idx_meeting_completed ON parent_meetings(is_completed)"
    ];
    
    $createdIndexes = 0;
    
    foreach ($indexes as $index) {
        try {
            $pdo->exec($index);
            $createdIndexes++;
        } catch (PDOException $e) {
            // El índice ya existe o hay un error, continuar
        }
    }
    
    echo "✅ Se crearon/verificaron $createdIndexes índices\n";
}

// Ejecutar migración
echo "🚀 Iniciando migración de base de datos ByFrost...\n\n";

// 1. Verificar integridad de la base de datos
if (!verifyDatabaseIntegrity($pdo)) {
    echo "❌ Error: Faltan tablas requeridas. Ejecute primero ByFrost_Unified_Database.sql\n";
    exit(1);
}

// 2. Crear índices faltantes
createMissingIndexes($pdo);

// 3. Actualizar archivos PHP
echo "\n📝 Actualizando archivos PHP...\n";
$updatedPhpFiles = updatePhpFiles($table_mappings, $field_mappings);
echo "✅ Se actualizaron $updatedPhpFiles archivos PHP\n";

// 4. Actualizar archivos JavaScript
echo "\n📝 Actualizando archivos JavaScript...\n";
$updatedJsFiles = updateJsFiles($table_mappings, $field_mappings);
echo "✅ Se actualizaron $updatedJsFiles archivos JavaScript\n";

// 5. Crear archivo de respaldo de configuración
$backupConfig = [
    'migration_date' => date('Y-m-d H:i:s'),
    'table_mappings' => $table_mappings,
    'field_mappings' => $field_mappings,
    'updated_php_files' => $updatedPhpFiles,
    'updated_js_files' => $updatedJsFiles
];

file_put_contents('app/scripts/migration_backup.json', json_encode($backupConfig, JSON_PRETTY_PRINT));

echo "\n🎉 ¡Migración completada exitosamente!\n";
echo "📊 Resumen:\n";
echo "   - Archivos PHP actualizados: $updatedPhpFiles\n";
echo "   - Archivos JavaScript actualizados: $updatedJsFiles\n";
echo "   - Índices verificados/creados\n";
echo "   - Respaldo guardado en: app/scripts/migration_backup.json\n\n";

echo "⚠️  IMPORTANTE:\n";
echo "   1. Revise los archivos actualizados para verificar que los cambios sean correctos\n";
echo "   2. Pruebe la funcionalidad de la aplicación\n";
echo "   3. Si hay problemas, puede restaurar desde el respaldo\n";
echo "   4. Considere hacer un respaldo completo de la base de datos antes de usar en producción\n";
?> 