<?php
// Script de debug para viewEmployee
require_once '../config.php';

echo "<h1>Debug de viewEmployee</h1>";

// Simular la sesión y permisos
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['role_type'] = 'root';

// Simular parámetros GET
$_GET['view'] = 'payroll';
$_GET['action'] = 'viewEmployee';
$_GET['id'] = '1';

echo "<p><strong>Parámetros GET:</strong></p>";
echo "<ul>";
echo "<li>view: " . $_GET['view'] . "</li>";
echo "<li>action: " . $_GET['action'] . "</li>";
echo "<li>id: " . $_GET['id'] . "</li>";
echo "</ul>";

try {
    // Incluir el controlador
    require_once '../app/controllers/PayrollController.php';
    
    // Crear instancia del controlador
    $controller = new PayrollController();
    
    echo "<p>✅ Controlador creado correctamente</p>";
    
    // Verificar si el método existe
    if (method_exists($controller, 'viewEmployee')) {
        echo "<p>✅ El método viewEmployee existe</p>";
        
        // Intentar llamar el método directamente
        echo "<h2>Llamando viewEmployee directamente:</h2>";
        ob_start();
        $controller->viewEmployee();
        $output = ob_get_clean();
        
        echo "<p>✅ Método ejecutado sin errores</p>";
        echo "<p><strong>Salida:</strong></p>";
        echo "<div style='border: 1px solid #ccc; padding: 10px; background: #f9f9f9;'>";
        echo htmlspecialchars($output);
        echo "</div>";
        
    } else {
        echo "<p>❌ El método viewEmployee NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?> 