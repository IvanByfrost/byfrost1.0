<?php
/**
 * Test simple para diagnosticar el problema
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Simple Debug</title>
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
    <h1>🧪 Test Simple Debug</h1>
    
    <div class='test-section info'>
        <h3>Estado del Sistema</h3>
        <div id='systemStatus'>Verificando...</div>
    </div>

    <div class='test-section'>
        <h3>Pruebas Básicas</h3>
        <button onclick='testBasicAjax()'>Test AJAX Básico</button>
        <button onclick='testLoadView()'>Test loadView</button>
        <button onclick='checkJS()'>Verificar JavaScript</button>
    </div>

    <div id='result'>
        <p>Resultados aparecerán aquí...</p>
    </div>

    <script>
        function showResult(message, type = 'info') {
            const result = document.getElementById('result');
            const timestamp = new Date().toLocaleTimeString();
            result.innerHTML += '<div class="test-section ' + type + '">[' + timestamp + '] ' + message + '</div>';
        }

        function testBasicAjax() {
            showResult('🌐 Probando AJAX básico...', 'info');
            
            fetch('?view=user&action=loadPartial&partialView=consultUser', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                showResult('Response status: ' + response.status, 'info');
                return response.text();
            })
            .then(html => {
                if (html.length > 0) {
                    showResult('✅ AJAX funciona. Tamaño: ' + html.length + ' caracteres', 'success');
                } else {
                    showResult('⚠️ AJAX responde vacío', 'warning');
                }
            })
            .catch(error => {
                showResult('❌ Error en AJAX: ' + error.message, 'error');
            });
        }

        function testLoadView() {
            showResult('🎯 Probando loadView...', 'info');
            
            if (typeof window.loadView === 'function') {
                try {
                    window.loadView('consultUser');
                    showResult('✅ loadView ejecutado', 'success');
                } catch (error) {
                    showResult('❌ Error en loadView: ' + error.message, 'error');
                }
            } else {
                showResult('❌ loadView no está disponible', 'error');
            }
        }

        function checkJS() {
            showResult('📊 Verificando JavaScript...', 'info');
            
            showResult('loadView: ' + typeof window.loadView, 'info');
            showResult('dashboardManager: ' + typeof window.dashboardManager, 'info');
            showResult('safeLoadView: ' + typeof window.safeLoadView, 'info');
            
            if (typeof window.loadView === 'function') {
                showResult('✅ loadView está disponible', 'success');
            } else {
                showResult('❌ loadView NO está disponible', 'error');
            }
        }

        // Verificar al cargar
        window.onload = function() {
            showResult('🚀 Página cargada', 'info');
            setTimeout(checkJS, 1000);
        };
    </script>

    <!-- Cargar dashboard.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../app/resources/js/dashboard.js"></script>
</body>
</html> 