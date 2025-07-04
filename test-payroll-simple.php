<?php
// Prueba simple del PayrollController
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir ROOT
define('ROOT', __DIR__);

// Simular parámetros GET
$_GET['view'] = 'payroll';
$_GET['action'] = 'dashboard';

echo "<h1>Prueba Simple del PayrollController</h1>";

// Cargar el controlador
try {
    require_once ROOT . '/app/controllers/payrollController.php';
    echo "<p>✅ PayrollController cargado exitosamente</p>";
    
    // Verificar que la clase existe
    if (class_exists('PayrollController')) {
        echo "<p>✅ Clase PayrollController existe</p>";
        
        // Verificar métodos
        $methods = get_class_methods('PayrollController');
        echo "<p>Métodos disponibles: " . implode(', ', $methods) . "</p>";
        
        // Verificar método dashboard
        if (method_exists('PayrollController', 'dashboard')) {
            echo "<p>✅ Método dashboard existe</p>";
        } else {
            echo "<p>❌ Método dashboard NO existe</p>";
        }
    } else {
        echo "<p>❌ Clase PayrollController NO existe</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='index.php?view=payroll&action=dashboard'>Probar Dashboard de Nómina</a></p>";
echo "<p><a href='index.php?view=payroll'>Probar Payroll sin acción (debería ir a dashboard)</a></p>";
?> 