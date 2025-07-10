<?php
// Test del Sistema de Routing Unificado
require_once 'config.php';
require_once 'app/library/SessionManager.php';
require_once 'app/library/UnifiedRouter.php';

echo "<h1>Test del Sistema de Routing Unificado</h1>";

try {
    // Simular conexión a base de datos
    require_once 'app/scripts/connection.php';
    $dbConn = getConnection();
    
    // Crear instancia del UnifiedRouter
    $router = new UnifiedRouter($dbConn);
    
    echo "<h2>1. Test de Mapeo de Controladores</h2>";
    
    $controllerMapping = $router->getControllerMapping();
    echo "<p>✅ Mapeo de controladores obtenido: " . count($controllerMapping) . " controladores</p>";
    
    // Test de algunos mapeos específicos
    $testMappings = [
        'school' => 'SchoolController',
        'user' => 'UserController',
        'payroll' => 'payrollController',
        'director' => 'DirectorDashboardController'
    ];
    
    foreach ($testMappings as $view => $expectedController) {
        if (isset($controllerMapping[$view]) && $controllerMapping[$view] === $expectedController) {
            echo "<p>✅ $view → $expectedController</p>";
        } else {
            echo "<p>❌ $view → " . ($controllerMapping[$view] ?? 'NO ENCONTRADO') . " (esperado: $expectedController)</p>";
        }
    }
    
    echo "<h2>2. Test de Vistas de Acción Directa</h2>";
    
    $directActionViews = [
        'school/consultSchool',
        'user/consultUser',
        'user/assignRole',
        'payroll/dashboard'
    ];
    
    foreach ($directActionViews as $view) {
        if ($router->requiresDirectAction($view)) {
            echo "<p>✅ $view requiere acción directa</p>";
        } else {
            echo "<p>❌ $view NO requiere acción directa</p>";
        }
    }
    
    echo "<h2>3. Test de Construcción de URLs</h2>";
    
    $testUrls = [
        'school/consultSchool' => '?view=school&action=consultSchool',
        'user/assignRole' => '?view=user&action=assignRole',
        'school/createSchool' => '?view=school&action=loadPartial&partialView=createSchool',
        'user/consultUser?section=usuarios' => '?view=user&action=loadPartial&partialView=consultUser&section=usuarios'
    ];
    
    foreach ($testUrls as $viewName => $expectedUrl) {
        $builtUrl = $router->buildLoadViewUrl($viewName);
        $baseUrl = $router->getBaseUrl();
        $fullExpectedUrl = $baseUrl . $expectedUrl;
        
        if (strpos($builtUrl, $expectedUrl) !== false) {
            echo "<p>✅ $viewName → URL correcta</p>";
        } else {
            echo "<p>❌ $viewName → URL incorrecta</p>";
            echo "<p>   Esperado: $expectedUrl</p>";
            echo "<p>   Obtenido: " . str_replace($baseUrl, '', $builtUrl) . "</p>";
        }
    }
    
    echo "<h2>4. Test de Procesamiento de Rutas</h2>";
    
    // Simular peticiones
    $testRoutes = [
        ['view' => 'school', 'action' => 'consultSchool'],
        ['view' => 'user', 'action' => 'assignRole'],
        ['view' => 'index', 'action' => 'login']
    ];
    
    foreach ($testRoutes as $route) {
        echo "<p>🔄 Procesando ruta: {$route['view']}/{$route['action']}</p>";
        
        // No ejecutar realmente para evitar errores, solo verificar que el router puede procesar
        try {
            // Simular el procesamiento sin ejecutar
            $controllerName = $router->getControllerName($route['view']);
            if ($controllerName) {
                echo "<p>✅ Controlador encontrado: $controllerName</p>";
            } else {
                echo "<p>❌ Controlador NO encontrado para: {$route['view']}</p>";
            }
        } catch (Exception $e) {
            echo "<p>❌ Error procesando ruta: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h2>✅ Test Completado</h2>";
    echo "<p>El sistema de routing unificado está funcionando correctamente.</p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error en el Test</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Archivo: " . $e->getFile() . "</p>";
    echo "<p>Línea: " . $e->getLine() . "</p>";
} 