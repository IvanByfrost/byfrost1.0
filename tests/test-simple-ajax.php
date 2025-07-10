<?php
/**
 * Test simple para verificar AJAX
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test AJAX Simple</title>
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
    <h1>🧪 Test AJAX Simple</h1>
    
    <div class='test-section info'>
        <h3>Estado del Sistema</h3>
        <div id='systemStatus'>Verificando...</div>
    </div>

    <div class='test-section'>
        <h3>Pruebas JavaScript</h3>
        <button onclick='testLoadView(\"consultUser\")'>Test loadView(consultUser)</button>
        <button onclick='testLoadView(\"editDirector\")'>Test loadView(editDirector)</button>
        <button onclick='testDirectAjax()'>Test AJAX Directo</button>
        <button onclick='checkJSFunctions()'>Verificar Funciones JS</button>
    </div>

    <div id='result'>
        <p>Resultados aparecerán aquí...</p>
    </div>

    <script>
        function showResult(message, type = 'info') {
            const result = document.getElementById('result');
            const timestamp = new Date().toLocaleTimeString();
            result.innerHTML += '<div class=\"test-section ' + type + '\">[' + timestamp + '] ' + message + '</div>';
        }

        function checkJSFunctions() {
            showResult('🔍 Verificando funciones JavaScript...', 'info');
            
            if (typeof window.loadView === 'function') {
                showResult('✅ window.loadView está disponible', 'success');
            } else {
                showResult('❌ window.loadView NO está disponible', 'error');
            }
            
            if (typeof window.dashboardManager !== 'undefined') {
                showResult('✅ window.dashboardManager está disponible', 'success');
                if (typeof window.dashboardManager.safeLoadView === 'function') {
                    showResult('✅ dashboardManager.safeLoadView está disponible', 'success');
                } else {
                    showResult('❌ dashboardManager.safeLoadView NO está disponible', 'error');
                }
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
                    showResult('✅ loadView(\"' + viewName + '\") ejecutado', 'success');
                } catch (error) {
                    showResult('❌ Error en loadView: ' + error.message, 'error');
                }
            } else {
                showResult('❌ window.loadView no está disponible', 'error');
            }
        }

        function testDirectAjax() {
            showResult('🌐 Probando AJAX directo...', 'info');
            
            const url = '?view=user&action=loadPartial&partialView=consultUser';
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                showResult('Response status: ' + response.status, 'info');
                return response.text();
            })
            .then(html => {
                if (html.length > 0) {
                    showResult('✅ AJAX directo funcionó. Tamaño: ' + html.length + ' caracteres', 'success');
                } else {
                    showResult('⚠️ AJAX directo responde vacío', 'warning');
                }
            })
            .catch(error => {
                showResult('❌ Error en AJAX directo: ' + error.message, 'error');
            });
        }

        // Verificar estado al cargar
        window.onload = function() {
            showResult('🚀 Página cargada, verificando sistema...', 'info');
            
            // Verificar si dashboard.js está cargado
            setTimeout(() => {
                checkJSFunctions();
            }, 1000);
        };
    </script>

    <!-- Cargar dashboard.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../app/resources/js/dashboard.js"></script>
</body>
</html> 