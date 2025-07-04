<?php
// Archivo de prueba simple para verificar getAllAbsences
require_once '../config.php';
require_once '../app/models/payrollModel.php';

echo "<h2>Prueba simple de getAllAbsences</h2>";

try {
    $payrollModel = new PayrollModel();
    echo "✅ Modelo creado exitosamente<br>";
    
    $absences = $payrollModel->getAllAbsences([]);
    echo "✅ getAllAbsences ejecutado sin errores<br>";
    echo "Resultado: " . count($absences) . " ausencias encontradas<br>";
    
    if (!empty($absences)) {
        echo "<h3>Primera ausencia:</h3>";
        echo "<pre>" . print_r($absences[0], true) . "</pre>";
    } else {
        echo "<p>No hay ausencias en la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Error:</h3>";
    echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
}
?> 