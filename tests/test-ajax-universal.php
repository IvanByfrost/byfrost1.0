<?php
/**
 * Test para verificar el AJAX universal
 * Prueba la carga dinámica de vistas de diferentes módulos
 */

require_once '../config.php';
require_once '../app/controllers/MainController.php';
require_once '../app/controllers/UserController.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test AJAX Universal</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        button { padding: 10px 15px; margin: 5px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background: #0056b3; }
        #result { margin-top: 20px; padding: 15px; border: 1px solid #ddd; min-height: 200px; }
    </style>
</head>
<body>
    <h1>Test AJAX Universal</h1>
    
    <div class='test-section info'>
        <h3>Información del Sistema</h3>
        <p><strong>URL Base:</strong> " . url . "</p>
        <p><strong>Directorio:</strong> " . ROOT . "</p>
        <p><strong>Vistas User:</strong> " . implode(', ', glob(ROOT . '/app/views/user/*.php')) . "</p>
    </div>

    <div class='test-section'>
        <h3>Pruebas AJAX Universal</h3>
        <button onclick='testUserView(\"consultUser\")'>Cargar consultUser</button>
        <button onclick='testUserView(\"assignRole\")'>Cargar assignRole</button>
        <button onclick='testUserView(\"roleHistory\")'>Cargar roleHistory</button>
        <button onclick='testUserView(\"settingsRoles\")'>Cargar settingsRoles</button>
        <button onclick='testDirectView(\"dashboard\")'>Cargar dashboard (directo)</button>
        <button onclick='testModuleView(\"school/createSchool\")'>Cargar school/createSchool</button>
    </div>

    <div id='result'>
        <p>Resultados de las pruebas aparecerán aquí...</p>
    </div>

    <script>
        function showResult(message, type = 'info') {
            const result = document.getElementById('result');
            result.innerHTML = '<div class=\"test-section ' + type + '\">' + message + '</div>';
        }

        function testUserView(viewName) {
            showResult('Probando carga de vista user: ' + viewName + '...', 'info');
            
            const url = '?view=user&action=loadPartial&partialView=' + encodeURIComponent(viewName);
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                return response.text();
            })
            .then(html => {
                showResult('<h4>✅ Éxito cargando ' + viewName + '</h4><p>URL: ' + url + '</p><div style=\"border: 1px solid #ccc; padding: 10px; margin: 10px 0; background: #f9f9f9;\">' + html.substring(0, 500) + '...</div>', 'success');
            })
            .catch(error => {
                showResult('<h4>❌ Error cargando ' + viewName + '</h4><p>Error: ' + error.message + '</p><p>URL: ' + url + '</p>', 'error');
            });
        }

        function testDirectView(viewName) {
            showResult('Probando carga de vista directa: ' + viewName + '...', 'info');
            
            const url = '?view=' + encodeURIComponent(viewName) + '&action=loadPartial';
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                return response.text();
            })
            .then(html => {
                showResult('<h4>✅ Éxito cargando ' + viewName + '</h4><p>URL: ' + url + '</p><div style=\"border: 1px solid #ccc; padding: 10px; margin: 10px 0; background: #f9f9f9;\">' + html.substring(0, 500) + '...</div>', 'success');
            })
            .catch(error => {
                showResult('<h4>❌ Error cargando ' + viewName + '</h4><p>Error: ' + error.message + '</p><p>URL: ' + url + '</p>', 'error');
            });
        }

        function testModuleView(viewPath) {
            showResult('Probando carga de vista de módulo: ' + viewPath + '...', 'info');
            
            const [module, view] = viewPath.split('/');
            const url = '?view=' + encodeURIComponent(module) + '&action=loadPartial&partialView=' + encodeURIComponent(view);
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                return response.text();
            })
            .then(html => {
                showResult('<h4>✅ Éxito cargando ' + viewPath + '</h4><p>URL: ' + url + '</p><div style=\"border: 1px solid #ccc; padding: 10px; margin: 10px 0; background: #f9f9f9;\">' + html.substring(0, 500) + '...</div>', 'success');
            })
            .catch(error => {
                showResult('<h4>❌ Error cargando ' + viewPath + '</h4><p>Error: ' + error.message + '</p><p>URL: ' + url + '</p>', 'error');
            });
        }

        // Test automático al cargar
        window.onload = function() {
            showResult('Sistema AJAX Universal listo para pruebas. Haz clic en los botones para probar diferentes tipos de carga.', 'info');
        };
    </script>
</body>
</html>";
?> 