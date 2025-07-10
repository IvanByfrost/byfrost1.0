<?php
/**
 * Test de diagnóstico completo para AJAX Universal
 * Verifica todos los componentes del sistema
 */

require_once '../config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Diagnóstico AJAX Universal</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        .warning { background-color: #fff3cd; border-color: #ffeaa7; }
        button { padding: 10px 15px; margin: 5px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background: #0056b3; }
        #result { margin-top: 20px; padding: 15px; border: 1px solid #ddd; min-height: 200px; }
        .log { font-family: monospace; background: #f8f9fa; padding: 10px; margin: 5px 0; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico AJAX Universal</h1>
    
    <div class='test-section info'>
        <h3>📋 Información del Sistema</h3>
        <p><strong>URL Base:</strong> " . url . "</p>
        <p><strong>Directorio:</strong> " . ROOT . "</p>
        <p><strong>PHP Version:</strong> " . phpversion() . "</p>
    </div>

    <div class='test-section'>
        <h3>🔧 Verificación de Archivos</h3>
        <div id='fileCheck'></div>
    </div>

    <div class='test-section'>
        <h3>🧪 Pruebas de Controladores</h3>
        <button onclick='testController(\"user\")'>Test UserController</button>
        <button onclick='testController(\"director\")'>Test DirectorController</button>
        <button onclick='testController(\"school\")'>Test SchoolController</button>
        <button onclick='testController(\"student\")'>Test StudentController</button>
        <button onclick='testController(\"payroll\")'>Test PayrollController</button>
    </div>

    <div class='test-section'>
        <h3>🎯 Pruebas AJAX Específicas</h3>
        <button onclick='testAjaxLoad(\"user\", \"consultUser\")'>Test user/consultUser</button>
        <button onclick='testAjaxLoad(\"director\", \"editDirector\")'>Test director/editDirector</button>
        <button onclick='testAjaxLoad(\"school\", \"createSchool\")'>Test school/createSchool</button>
        <button onclick='testAjaxLoad(\"student\", \"academicHistory\")'>Test student/academicHistory</button>
        <button onclick='testAjaxLoad(\"payroll\", \"dashboard\")'>Test payroll/dashboard</button>
    </div>

    <div class='test-section'>
        <h3>📊 Test JavaScript Dashboard</h3>
        <button onclick='testDashboardJS()'>Test Dashboard.js</button>
        <button onclick='testLoadView(\"consultUser\")'>Test loadView(consultUser)</button>
        <button onclick='testLoadView(\"editDirector\")'>Test loadView(editDirector)</button>
        <button onclick='testLoadView(\"createSchool\")'>Test loadView(createSchool)</button>
    </div>

    <div id='result'>
        <p>Resultados del diagnóstico aparecerán aquí...</p>
    </div>

    <script>
        function showResult(message, type = 'info') {
            const result = document.getElementById('result');
            const timestamp = new Date().toLocaleTimeString();
            result.innerHTML += '<div class=\"test-section ' + type + '\">[' + timestamp + '] ' + message + '</div>';
        }

        function addLog(message) {
            const result = document.getElementById('result');
            const timestamp = new Date().toLocaleTimeString();
            result.innerHTML += '<div class=\"log\">[' + timestamp + '] ' + message + '</div>';
        }

        // Verificar archivos al cargar
        window.onload = function() {
            showResult('🚀 Iniciando diagnóstico completo...', 'info');
            checkFiles();
        };

        function checkFiles() {
            addLog('Verificando archivos críticos...');
            
            const files = [
                '../app/resources/js/dashboard.js',
                '../app/controllers/UserController.php',
                '../app/controllers/DirectorController.php',
                '../app/controllers/SchoolController.php',
                '../app/controllers/studentController.php',
                '../app/controllers/payrollController.php',
                '../app/views/user/consultUser.php',
                '../app/views/director/editDirector.php',
                '../app/views/school/createSchool.php'
            ];

            files.forEach(file => {
                fetch(file)
                    .then(response => {
                        if (response.ok) {
                            addLog('✅ ' + file + ' - OK');
                        } else {
                            addLog('❌ ' + file + ' - Error ' + response.status);
                        }
                    })
                    .catch(error => {
                        addLog('❌ ' + file + ' - Error: ' + error.message);
                    });
            });
        }

        function testController(controller) {
            showResult('🧪 Probando controlador: ' + controller, 'info');
            
            const url = '?view=' + controller + '&action=loadPartial&partialView=test';
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                addLog('Response status: ' + response.status);
                return response.text();
            })
            .then(html => {
                if (html.includes('Vista no encontrada')) {
                    showResult('✅ Controlador ' + controller + ' responde correctamente (vista de prueba no existe)', 'success');
                } else if (html.includes('Vista no especificada')) {
                    showResult('✅ Controlador ' + controller + ' responde correctamente', 'success');
                } else {
                    showResult('⚠️ Controlador ' + controller + ' responde pero con contenido inesperado', 'warning');
                    addLog('Respuesta: ' + html.substring(0, 200));
                }
            })
            .catch(error => {
                showResult('❌ Error en controlador ' + controller + ': ' + error.message, 'error');
            });
        }

        function testAjaxLoad(module, view) {
            showResult('🎯 Probando AJAX: ' + module + '/' + view, 'info');
            
            const url = '?view=' + module + '&action=loadPartial&partialView=' + view;
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                addLog('Response status: ' + response.status);
                return response.text();
            })
            .then(html => {
                if (html.includes('Vista no encontrada')) {
                    showResult('❌ Vista ' + module + '/' + view + ' no existe', 'error');
                } else if (html.length > 0) {
                    showResult('✅ Vista ' + module + '/' + view + ' cargada correctamente', 'success');
                    addLog('Tamaño respuesta: ' + html.length + ' caracteres');
                } else {
                    showResult('⚠️ Vista ' + module + '/' + view + ' responde vacío', 'warning');
                }
            })
            .catch(error => {
                showResult('❌ Error cargando ' + module + '/' + view + ': ' + error.message, 'error');
            });
        }

        function testDashboardJS() {
            showResult('📊 Probando dashboard.js...', 'info');
            
            // Verificar si dashboard.js está cargado
            if (typeof window.loadView === 'function') {
                showResult('✅ window.loadView está disponible', 'success');
            } else {
                showResult('❌ window.loadView NO está disponible', 'error');
            }
            
            if (typeof window.dashboardManager !== 'undefined') {
                showResult('✅ window.dashboardManager está disponible', 'success');
            } else {
                showResult('❌ window.dashboardManager NO está disponible', 'error');
            }
            
            if (typeof window.safeLoadView === 'function') {
                showResult('✅ window.safeLoadView está disponible', 'success');
            } else {
                showResult('❌ window.safeLoadView NO está disponible', 'error');
            }
        }

        function testLoadView(viewName) {
            showResult('🎯 Probando loadView(\"' + viewName + '\")...', 'info');
            
            if (typeof window.loadView === 'function') {
                try {
                    window.loadView(viewName);
                    showResult('✅ loadView(\"' + viewName + '\") ejecutado sin errores', 'success');
                } catch (error) {
                    showResult('❌ Error en loadView(\"' + viewName + '\"): ' + error.message, 'error');
                }
            } else {
                showResult('❌ window.loadView no está disponible', 'error');
            }
        }
    </script>
</body>
</html>";
?> 