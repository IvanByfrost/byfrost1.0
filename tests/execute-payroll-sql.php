<?php
/**
 * Script para ejecutar el SQL de las tablas de nómina
 */

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Ejecutar Tablas SQL de Nómina</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once '../config.php';
require_once ROOT . '/app/scripts/connection.php';

echo "<h2>1. Verificación de Conexión:</h2>";
try {
    $conn = getConnection();
    echo "<p>✅ Conexión a base de datos exitosa</p>";
} catch (Exception $e) {
    echo "<p>❌ Error de conexión: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>2. Verificación de Tablas Existentes:</h2>";
$tables = [
    'employees',
    'payroll_concepts', 
    'payroll_periods',
    'payroll_records',
    'payroll_concept_details',
    'employee_absences',
    'employee_overtime',
    'employee_bonuses'
];

echo "<ul>";
foreach ($tables as $table) {
    try {
        $stmt = $conn->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        $exists = $stmt->rowCount() > 0;
        
        if ($exists) {
            // Verificar registros
            $stmt = $conn->prepare("SELECT COUNT(*) FROM $table");
            $stmt->execute();
            $count = $stmt->fetchColumn();
            echo "<li>✅ Tabla <strong>$table</strong>: EXISTE ($count registros)</li>";
        } else {
            echo "<li>❌ Tabla <strong>$table</strong>: NO EXISTE</li>";
        }
    } catch (Exception $e) {
        echo "<li>❌ Error verificando tabla <strong>$table</strong>: " . $e->getMessage() . "</li>";
    }
}
echo "</ul>";

echo "<h2>3. Ejecutar Script SQL:</h2>";
$sqlFile = ROOT . '/app/scripts/payroll_tables.sql';

if (file_exists($sqlFile)) {
    echo "<p>✅ Archivo SQL encontrado: " . $sqlFile . "</p>";
    
    $sql = file_get_contents($sqlFile);
    
    // Dividir el SQL en comandos individuales
    $commands = array_filter(array_map('trim', explode(';', $sql)));
    
    echo "<p>Ejecutando " . count($commands) . " comandos SQL...</p>";
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($commands as $command) {
        if (!empty($command)) {
            try {
                $stmt = $conn->prepare($command);
                $stmt->execute();
                $successCount++;
                echo "<p>✅ Comando ejecutado correctamente</p>";
            } catch (Exception $e) {
                $errorCount++;
                echo "<p>❌ Error ejecutando comando: " . $e->getMessage() . "</p>";
                echo "<p><strong>Comando:</strong> " . htmlspecialchars(substr($command, 0, 100)) . "...</p>";
            }
        }
    }
    
    echo "<h3>Resultado:</h3>";
    echo "<p>✅ Comandos exitosos: $successCount</p>";
    echo "<p>❌ Comandos con error: $errorCount</p>";
    
} else {
    echo "<p>❌ Archivo SQL no encontrado: " . $sqlFile . "</p>";
}

echo "<h2>4. Verificación Final:</h2>";
echo "<ul>";
foreach ($tables as $table) {
    try {
        $stmt = $conn->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        $exists = $stmt->rowCount() > 0;
        
        if ($exists) {
            // Verificar registros
            $stmt = $conn->prepare("SELECT COUNT(*) FROM $table");
            $stmt->execute();
            $count = $stmt->fetchColumn();
            echo "<li>✅ Tabla <strong>$table</strong>: EXISTE ($count registros)</li>";
        } else {
            echo "<li>❌ Tabla <strong>$table</strong>: NO EXISTE</li>";
        }
    } catch (Exception $e) {
        echo "<li>❌ Error verificando tabla <strong>$table</strong>: " . $e->getMessage() . "</li>";
    }
}
echo "</ul>";

echo "<h2>5. Test de Funcionalidad:</h2>";
echo "<p><a href='test-payroll-urgent.php' target='_blank'>Ejecutar Test Urgente</a></p>";
echo "<p><a href='../?view=payroll&action=dashboard' target='_blank'>Probar Dashboard de Nómina</a></p>";

echo "<h2>6. Instrucciones:</h2>";
echo "<ol>";
echo "<li>Si todas las tablas existen, el problema no está en la base de datos</li>";
echo "<li>Si faltan tablas, ejecuta este script para crearlas</li>";
echo "<li>Después de crear las tablas, prueba el dashboard de nómina</li>";
echo "<li>Si sigue sin funcionar, el problema está en el código</li>";
echo "</ol>";

echo "<hr>\n";
echo "<p><em>Script completado el " . date('Y-m-d H:i:s') . "</em></p>\n";
?> 