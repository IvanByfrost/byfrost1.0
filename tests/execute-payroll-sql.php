<?php
/**
 * Script para ejecutar el SQL de las tablas de nómina
 */

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Ejecutando SQL de Tablas de Nómina</h1>\n";
echo "<hr>\n";

try {
    // Conectar a la base de datos
    require_once 'app/scripts/connection.php';
    $conn = getConnection();
    echo "✅ Conexión a base de datos exitosa<br>\n";
    
    // Leer el archivo SQL
    $sqlFile = 'app/scripts/payroll_tables.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("Archivo SQL no encontrado: $sqlFile");
    }
    
    $sqlContent = file_get_contents($sqlFile);
    echo "✅ Archivo SQL leído correctamente<br>\n";
    
    // Dividir el SQL en comandos individuales
    $commands = array_filter(array_map('trim', explode(';', $sqlContent)));
    
    echo "<h3>Ejecutando comandos SQL:</h3>\n";
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($commands as $command) {
        if (empty($command) || strpos($command, '--') === 0) {
            continue; // Saltar comentarios y líneas vacías
        }
        
        try {
            $stmt = $conn->prepare($command);
            $stmt->execute();
            echo "✅ Comando ejecutado: " . substr($command, 0, 50) . "...<br>\n";
            $successCount++;
        } catch (Exception $e) {
            echo "❌ Error en comando: " . $e->getMessage() . "<br>\n";
            echo "Comando: " . substr($command, 0, 100) . "...<br>\n";
            $errorCount++;
        }
    }
    
    echo "<hr>\n";
    echo "<h3>Resumen:</h3>\n";
    echo "✅ Comandos exitosos: $successCount<br>\n";
    echo "❌ Errores: $errorCount<br>\n";
    
    // Verificar que las tablas se crearon
    echo "<h3>Verificando tablas creadas:</h3>\n";
    $requiredTables = [
        'employees',
        'payroll_concepts',
        'payroll_periods',
        'payroll_records',
        'payroll_concept_details',
        'employee_absences',
        'employee_overtime',
        'employee_bonuses'
    ];
    
    foreach ($requiredTables as $table) {
        try {
            $stmt = $conn->prepare("SHOW TABLES LIKE ?");
            $stmt->execute([$table]);
            if ($stmt->rowCount() > 0) {
                echo "✅ Tabla '$table' existe<br>\n";
            } else {
                echo "❌ Tabla '$table' NO existe<br>\n";
            }
        } catch (Exception $e) {
            echo "❌ Error verificando tabla '$table': " . $e->getMessage() . "<br>\n";
        }
    }
    
    // Verificar conceptos de nómina
    echo "<h3>Verificando conceptos de nómina:</h3>\n";
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM payroll_concepts");
        $stmt->execute();
        $result = $stmt->fetch();
        echo "✅ Conceptos de nómina: " . $result['count'] . " registros<br>\n";
    } catch (Exception $e) {
        echo "❌ Error verificando conceptos: " . $e->getMessage() . "<br>\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "<br>\n";
}

echo "<hr>\n";
echo "<p><em>Script completado el " . date('Y-m-d H:i:s') . "</em></p>\n";
?> 