<?php
// Script de prueba para verificar viewEmployee
require_once '../config.php';
require_once '../app/controllers/PayrollController.php';

echo "<h1>Prueba de viewEmployee</h1>";

try {
    // Simular parámetros GET
    $_GET['id'] = '1';
    
    // Crear instancia del controlador
    $controller = new PayrollController();
    
    // Verificar si el método existe
    if (method_exists($controller, 'viewEmployee')) {
        echo "<p style='color: green;'>✓ El método viewEmployee existe</p>";
        
        // Listar todos los métodos públicos
        $methods = get_class_methods($controller);
        $publicMethods = array_filter($methods, function($method) {
            return $method !== '__construct' && !str_starts_with($method, '_');
        });
        
        echo "<h3>Métodos disponibles en PayrollController:</h3>";
        echo "<ul>";
        foreach ($publicMethods as $method) {
            echo "<li>$method</li>";
        }
        echo "</ul>";
        
    } else {
        echo "<p style='color: red;'>✗ El método viewEmployee NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?> 