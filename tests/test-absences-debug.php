<?php
// Archivo de prueba para depurar el método absences
require_once '../config.php';
require_once '../app/models/payrollModel.php';
require_once '../app/library/SessionManager.php';

echo "<h2>Depuración del método absences</h2>";

try {
    // 1. Probar la conexión
    echo "<h3>1. Probando conexión a la base de datos...</h3>";
    $payrollModel = new PayrollModel();
    echo "✅ Conexión exitosa<br>";
    
    // 2. Probar getAllAbsences sin filtros
    echo "<h3>2. Probando getAllAbsences() sin filtros...</h3>";
    $absences = $payrollModel->getAllAbsences([]);
    echo "Resultado: " . count($absences) . " ausencias encontradas<br>";
    
    if (!empty($absences)) {
        echo "<h4>Primera ausencia:</h4>";
        echo "<pre>" . print_r($absences[0], true) . "</pre>";
    } else {
        echo "⚠️ No hay ausencias en la base de datos<br>";
    }
    
    // 3. Verificar si hay empleados
    echo "<h3>3. Verificando empleados...</h3>";
    $employees = $payrollModel->getAllEmployees();
    echo "Empleados encontrados: " . count($employees) . "<br>";
    
    if (!empty($employees)) {
        echo "<h4>Primer empleado:</h4>";
        echo "<pre>" . print_r($employees[0], true) . "</pre>";
    }
    
    // 4. Verificar estructura de la tabla employee_absences
    echo "<h3>4. Verificando estructura de tabla employee_absences...</h3>";
    $conn = getConnection();
    $stmt = $conn->query("DESCRIBE employee_absences");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<h4>Columnas de employee_absences:</h4>";
    echo "<pre>" . print_r($columns, true) . "</pre>";
    
    // 5. Verificar si hay datos en employee_absences
    echo "<h3>5. Verificando datos en employee_absences...</h3>";
    $stmt = $conn->query("SELECT COUNT(*) as total FROM employee_absences");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total de registros en employee_absences: " . $count['total'] . "<br>";
    
    if ($count['total'] > 0) {
        $stmt = $conn->query("SELECT * FROM employee_absences LIMIT 1");
        $sample = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<h4>Muestra de employee_absences:</h4>";
        echo "<pre>" . print_r($sample, true) . "</pre>";
    }
    
    // 6. Probar la consulta completa manualmente
    echo "<h3>6. Probando consulta completa manualmente...</h3>";
    $sql = "SELECT ea.*, e.employee_code, u.first_name, u.last_name, e.department 
            FROM employee_absences ea 
            INNER JOIN employees e ON ea.employee_id = e.employee_id 
            INNER JOIN users u ON e.user_id = u.user_id 
            WHERE ea.is_active = 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $manualResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Consulta manual devuelve: " . count($manualResult) . " registros<br>";
    
    if (!empty($manualResult)) {
        echo "<h4>Resultado de consulta manual:</h4>";
        echo "<pre>" . print_r($manualResult[0], true) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Error encontrado:</h3>";
    echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
    echo "<p><strong>Trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?> 