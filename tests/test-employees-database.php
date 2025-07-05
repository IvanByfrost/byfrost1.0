<?php
// Script para verificar empleados en la base de datos
require_once '../config.php';
require_once '../app/models/payrollModel.php';

echo "<h1>Verificación de Empleados en Base de Datos</h1>";

try {
    $payrollModel = new PayrollModel();
    
    // Verificar si hay empleados
    $employees = $payrollModel->getAllEmployees();
    
    echo "<h2>Empleados encontrados: " . count($employees) . "</h2>";
    
    if (!empty($employees)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Código</th><th>Nombre</th><th>Email</th><th>Cargo</th><th>Departamento</th></tr>";
        
        foreach ($employees as $employee) {
            echo "<tr>";
            echo "<td>" . $employee['employee_id'] . "</td>";
            echo "<td>" . htmlspecialchars($employee['employee_code']) . "</td>";
            echo "<td>" . htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) . "</td>";
            echo "<td>" . htmlspecialchars($employee['email']) . "</td>";
            echo "<td>" . htmlspecialchars($employee['position']) . "</td>";
            echo "<td>" . htmlspecialchars($employee['department']) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Probar obtener empleado específico
        $firstEmployee = $employees[0];
        echo "<h2>Probando getEmployeeById con ID: " . $firstEmployee['employee_id'] . "</h2>";
        
        $employee = $payrollModel->getEmployeeById($firstEmployee['employee_id']);
        if ($employee) {
            echo "<p style='color: green;'>✅ Empleado encontrado: " . $employee['first_name'] . ' ' . $employee['last_name'] . "</p>";
        } else {
            echo "<p style='color: red;'>❌ No se pudo obtener el empleado</p>";
        }
        
    } else {
        echo "<p style='color: orange;'>⚠️ No hay empleados en la base de datos</p>";
        echo "<p>Esto explica por qué viewEmployee no funciona - no hay empleados para mostrar.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?> 