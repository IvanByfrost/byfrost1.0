<?php
/**
 * Script para limpiar archivos SQL antiguos después de la unificación
 * Mueve los archivos antiguos a una carpeta de respaldo
 */

echo "🧹 Iniciando limpieza de archivos SQL antiguos...\n\n";

// Archivos SQL antiguos que ya no son necesarios
$oldFiles = [
    'app/scripts/Baldur.sql',
    'app/scripts/payroll_tables.sql',
    'app/scripts/academic_tables.sql',
    'app/scripts/grades_tables.sql',
    'app/scripts/payments_table.sql',
    'app/scripts/attendance_table.sql',
    'app/scripts/events_table.sql',
    'app/scripts/password_resets_table.sql'
];

// Crear directorio de respaldo si no existe
$backupDir = 'app/scripts/backup_old_sql';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
    echo "✅ Directorio de respaldo creado: $backupDir\n";
}

$movedFiles = 0;
$deletedFiles = 0;

foreach ($oldFiles as $file) {
    if (file_exists($file)) {
        $fileName = basename($file);
        $backupPath = $backupDir . '/' . $fileName;
        
        // Mover archivo a respaldo
        if (copy($file, $backupPath)) {
            echo "✅ Respaldo creado: $fileName\n";
            
            // Eliminar archivo original
            if (unlink($file)) {
                echo "🗑️  Eliminado: $fileName\n";
                $deletedFiles++;
            } else {
                echo "❌ Error eliminando: $fileName\n";
            }
            
            $movedFiles++;
        } else {
            echo "❌ Error creando respaldo: $fileName\n";
        }
    } else {
        echo "⚠️  Archivo no encontrado: $file\n";
    }
}

// Crear archivo de documentación del respaldo
$backupInfo = [
    'backup_date' => date('Y-m-d H:i:s'),
    'reason' => 'Unificación de base de datos ByFrost',
    'files_moved' => $movedFiles,
    'files_deleted' => $deletedFiles,
    'description' => 'Estos archivos fueron reemplazados por ByFrost_Unified_Database.sql',
    'new_file' => 'app/scripts/ByFrost_Unified_Database.sql',
    'migration_script' => 'app/scripts/migrate_to_unified_database.php',
    'test_script' => 'app/scripts/test_unified_database.php'
];

file_put_contents($backupDir . '/backup_info.json', json_encode($backupInfo, JSON_PRETTY_PRINT));

echo "\n📊 Resumen de limpieza:\n";
echo "   - Archivos movidos a respaldo: $movedFiles\n";
echo "   - Archivos eliminados: $deletedFiles\n";
echo "   - Directorio de respaldo: $backupDir\n";
echo "   - Información de respaldo: $backupDir/backup_info.json\n";

echo "\n✅ Limpieza completada exitosamente.\n";
echo "📁 Los archivos antiguos están disponibles en: $backupDir\n";
echo "🔄 Si necesita restaurar, copie los archivos desde el directorio de respaldo.\n";
?> 