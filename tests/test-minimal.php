<?php
/**
 * Test minimalista para diagnosticar el problema
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Minimal</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .result { margin: 10px 0; padding: 10px; border: 1px solid #ddd; }
        .success { background-color: #d4edda; }
        .error { background-color: #f8d7da; }
        .info { background-color: #d1ecf1; }
    </style>
</head>
<body>
    <h1>🧪 Test Minimal</h1>
    
    <div id='results'></div>
    
    <button onclick='testAjax()'>Test AJAX</button>
    <button onclick='testLoadView()'>Test loadView</button>
    
    <script>
        function showResult(message, type = 'info') {
            const results = document.getElementById('results');
            results.innerHTML += '<div class="result ' + type + '">' + message + '</div>';
        }
        
        function testAjax() {
            showResult('Probando AJAX...', 'info');
            
            fetch('?view=user&action=loadPartial&partialView=consultUser', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                showResult('Status: ' + response.status, 'info');
                return response.text();
            })
            .then(html => {
                if (html.length > 0) {
                    showResult('✅ AJAX funciona. Tamaño: ' + html.length, 'success');
                } else {
                    showResult('⚠️ AJAX vacío', 'error');
                }
            })
            .catch(error => {
                showResult('❌ Error: ' + error.message, 'error');
            });
        }
        
        function testLoadView() {
            showResult('Probando loadView...', 'info');
            
            if (typeof window.loadView === 'function') {
                try {
                    window.loadView('consultUser');
                    showResult('✅ loadView ejecutado', 'success');
                } catch (error) {
                    showResult('❌ Error: ' + error.message, 'error');
                }
            } else {
                showResult('❌ loadView no disponible', 'error');
            }
        }
        
        // Verificar al cargar
        window.onload = function() {
            showResult('Página cargada', 'info');
            showResult('loadView: ' + typeof window.loadView, 'info');
            showResult('dashboardManager: ' + typeof window.dashboardManager, 'info');
        };
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../app/resources/js/dashboard.js"></script>
</body>
</html> 