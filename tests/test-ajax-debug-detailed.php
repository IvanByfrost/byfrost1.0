<?php
/**
 * Test detallado para diagnosticar AJAX Universal
 */

require_once '../config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Diagnóstico Detallado AJAX</title>
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
        .debug { background: #e9ecef; padding: 5px; margin: 2px 0; border-radius: 3px; font-size: 12px; }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico Detallado AJAX Universal</h1>
    
    <div class='test-section info'>
        <h3>📋 Información del Sistema</h3>
        <p><strong>URL Base:</strong> " . url . "</p>
        <p><strong>Directorio:</strong> " . ROOT . "</p>
        <p><strong>PHP Version:</strong> " . phpversion() . "</p>
    </div>

    <div class='test-section'>
        <h3>🔧 Verificación de Archivos Críticos</h3>
        <div id='fileCheck'></div>
    </div>

    <div class='test-section'>
        <h3>🧪 Pruebas de Controladores</h3>
        <button onclick='testControllerLoadPartial(\"user\")'>Test UserController loadPartial</button>
        <button onclick='testControllerLoadPartial(\"director\")'>Test DirectorController loadPartial</button>
        <button onclick='testControllerLoadPartial(\"school\")'>Test SchoolController loadPartial</button>
        <button onclick='testControllerLoadPartial(\"payroll\")'>Test PayrollController loadPartial</button>
    </div>

    <div class='test-section'>
        <h3>🎯 Pruebas AJAX Específicas</h3>
        <button onclick='testAjaxWithDebug(\"user\", \"consultUser\")'>Test user/consultUser con Debug</button>
        <button onclick='testAjaxWithDebug(\"director\", \"editDirector\")'>Test director/editDirector con Debug</button>
        <button onclick='testAjaxWithDebug(\"school\", \"createSchool\")'>Test school/createSchool con Debug</button>
        <button onclick='testAjaxWithDebug(\"payroll\", \"dashboard\")'>Test payroll/dashboard con Debug</button>
    </div>

    <div class='test-section'>
        <h3>📊 Test JavaScript Dashboard</h3>
        <button onclick='testDashboardJSDetailed()'>Test Dashboard.js Detallado</button>
        <button onclick='testLoadViewWithDebug(\"consultUser\")'>Test loadView(consultUser) con Debug</button>
        <button onclick='testLoadViewWithDebug(\"editDirector\")'>Test loadView(editDirector) con Debug</button>
    </div>

    <div class='test-section'>
        <h3>🌐 Test de Red</h3>
        <button onclick='testNetworkRequest()'>Test de Petición de Red</button>
        <button onclick='testHeaders()'>Test de Headers</button>
    </div>

    <div id='result'>
        <p>Resultados del diagnóstico detallado aparecerán aquí...</p>
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

        function addDebug(message) {
            const result = document.getElementById('result');
            const timestamp = new Date().toLocaleTimeString();
            result.innerHTML += '<div class=\"debug\">[' + timestamp + '] DEBUG: ' + message + '</div>';
        }

        // Verificar archivos al cargar
        window.onload = function() {
            showResult('🚀 Iniciando diagnóstico detallado...', 'info');
            checkCriticalFiles();
        };

        function checkCriticalFiles() {
            addLog('Verificando archivos críticos...');
            
            const files = [
                '../app/resources/js/dashboard.js',
                '../app/controllers/UserController.php',
                '../app/controllers/DirectorController.php',
                '../app/controllers/SchoolController.php',
                '../app/controllers/payrollController.php',
                '../app/views/user/consultUser.php',
                '../app/views/director/editDirector.php',
                '../app/views/school/createSchool.php',
                '../app/views/payroll/dashboard.php'
            ];

            files.forEach(file => {
                fetch(file)
                    .then(response => {
                        if (response.ok) {
                            addLog('✅ ' + file + ' - OK (' + response.status + ')');
                        } else {
                            addLog('❌ ' + file + ' - Error ' + response.status);
                        }
                    })
                    .catch(error => {
                        addLog('❌ ' + file + ' - Error: ' + error.message);
                    });
            });
        }

        function testControllerLoadPartial(controller) {
            showResult('🧪 Probando controlador: ' + controller, 'info');
            
            const url = '?view=' + controller + '&action=loadPartial&partialView=test';
            
            addDebug('URL: ' + url);
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                addDebug('Response status: ' + response.status);
                addDebug('Response headers: ' + JSON.stringify([...response.headers.entries()]));
                return response.text();
            })
            .then(html => {
                addDebug('Response length: ' + html.length);
                addDebug('Response preview: ' + html.substring(0, 200));
                
                if (html.includes('Vista no encontrada')) {
                    showResult('✅ Controlador ' + controller + ' responde correctamente (vista de prueba no existe)', 'success');
                } else if (html.includes('Vista no especificada')) {
                    showResult('✅ Controlador ' + controller + ' responde correctamente', 'success');
                } else if (html.includes('Error')) {
                    showResult('⚠️ Controlador ' + controller + ' responde con error', 'warning');
                } else {
                    showResult('❓ Controlador ' + controller + ' responde con contenido inesperado', 'warning');
                }
            })
            .catch(error => {
                showResult('❌ Error en controlador ' + controller + ': ' + error.message, 'error');
                addDebug('Error details: ' + error.stack);
            });
        }

        function testAjaxWithDebug(module, view) {
            showResult('🎯 Probando AJAX: ' + module + '/' + view, 'info');
            
            const url = '?view=' + module + '&action=loadPartial&partialView=' + view;
            
            addDebug('URL: ' + url);
            addDebug('Headers: X-Requested-With: XMLHttpRequest');
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                addDebug('Response status: ' + response.status);
                addDebug('Response headers: ' + JSON.stringify([...response.headers.entries()]));
                return response.text();
            })
            .then(html => {
                addDebug('Response length: ' + html.length);
                addDebug('Response preview: ' + html.substring(0, 300));
                
                if (html.includes('Vista no encontrada')) {
                    showResult('❌ Vista ' + module + '/' + view + ' no existe', 'error');
                } else if (html.length > 0) {
                    showResult('✅ Vista ' + module + '/' + view + ' cargada correctamente', 'success');
                } else {
                    showResult('⚠️ Vista ' + module + '/' + view + ' responde vacío', 'warning');
                }
            })
            .catch(error => {
                showResult('❌ Error cargando ' + module + '/' + view + ': ' + error.message, 'error');
                addDebug('Error details: ' + error.stack);
            });
        }

        function testDashboardJSDetailed() {
            showResult('📊 Probando dashboard.js detallado...', 'info');
            
            addDebug('window.loadView: ' + typeof window.loadView);
            addDebug('window.dashboardManager: ' + typeof window.dashboardManager);
            addDebug('window.safeLoadView: ' + typeof window.safeLoadView);
            
            if (typeof window.loadView === 'function') {
                showResult('✅ window.loadView está disponible', 'success');
                addDebug('loadView.toString(): ' + window.loadView.toString().substring(0, 100));
            } else {
                showResult('❌ window.loadView NO está disponible', 'error');
            }
            
            if (typeof window.dashboardManager !== 'undefined') {
                showResult('✅ window.dashboardManager está disponible', 'success');
                if (typeof window.dashboardManager.safeLoadView === 'function') {
                    showResult('✅ dashboardManager.safeLoadView está disponible', 'success');
                    addDebug('safeLoadView.toString(): ' + window.dashboardManager.safeLoadView.toString().substring(0, 100));
                } else {
                    showResult('❌ dashboardManager.safeLoadView NO está disponible', 'error');
                }
            } else {
                showResult('❌ window.dashboardManager NO está disponible', 'error');
            }
        }

        function testLoadViewWithDebug(viewName) {
            showResult('🎯 Probando loadView(\"' + viewName + '\") con debug...', 'info');
            
            addDebug('viewName: ' + viewName);
            addDebug('typeof loadView: ' + typeof window.loadView);
            
            if (typeof window.loadView === 'function') {
                try {
                    addDebug('Ejecutando loadView...');
                    window.loadView(viewName);
                    showResult('✅ loadView(\"' + viewName + '\") ejecutado sin errores', 'success');
                } catch (error) {
                    showResult('❌ Error en loadView(\"' + viewName + '\"): ' + error.message, 'error');
                    addDebug('Error stack: ' + error.stack);
                }
            } else {
                showResult('❌ window.loadView no está disponible', 'error');
            }
        }

        function testNetworkRequest() {
            showResult('🌐 Probando petición de red...', 'info');
            
            const testUrl = '?view=user&action=loadPartial&partialView=consultUser';
            
            addDebug('Test URL: ' + testUrl);
            
            fetch(testUrl, {
                method: 'GET',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                }
            })
            .then(response => {
                addDebug('Network response status: ' + response.status);
                addDebug('Network response type: ' + response.type);
                addDebug('Network response url: ' + response.url);
                return response.text();
            })
            .then(html => {
                addDebug('Network response length: ' + html.length);
                showResult('✅ Petición de red exitosa', 'success');
            })
            .catch(error => {
                showResult('❌ Error en petición de red: ' + error.message, 'error');
            });
        }

        function testHeaders() {
            showResult('📋 Probando headers...', 'info');
            
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Cache-Control': 'no-cache'
            };
            
            addDebug('Headers enviados: ' + JSON.stringify(headers));
            
            fetch('?view=user&action=loadPartial&partialView=consultUser', {
                headers: headers
            })
            .then(response => {
                addDebug('Response headers recibidos: ' + JSON.stringify([...response.headers.entries()]));
                return response.text();
            })
            .then(html => {
                showResult('✅ Headers procesados correctamente', 'success');
            })
            .catch(error => {
                showResult('❌ Error con headers: ' + error.message, 'error');
            });
        }
    </script>

    <!-- Cargar dashboard.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../app/resources/js/dashboard.js"></script>
</body>
</html>";
?> 