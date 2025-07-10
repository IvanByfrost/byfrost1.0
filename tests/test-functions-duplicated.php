<?php
require_once '../config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Funciones Duplicadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Test - Verificar Funciones Duplicadas</h1>
        
        <div id="results"></div>
    </div>

    <script src="../app/resources/js/loadView.js"></script>
    <script src="../app/resources/js/dashboard.js"></script>
    <script src="../app/resources/js/directorDashboard.js"></script>
    <script src="../app/resources/js/payrollManagement.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const results = document.getElementById('results');
            let testResults = [];
            
            // Test 1: Verificar que loadView está definido
            if (typeof window.loadView === 'function') {
                testResults.push('<div style="color: green;">✅ window.loadView está definido</div>');
            } else {
                testResults.push('<div style="color: red;">❌ window.loadView NO está definido</div>');
            }
            
            // Test 2: Verificar que safeLoadView está definido
            if (typeof window.safeLoadView === 'function') {
                testResults.push('<div style="color: green;">✅ window.safeLoadView está definido</div>');
            } else {
                testResults.push('<div style="color: red;">❌ window.safeLoadView NO está definido</div>');
            }
            
            // Test 3: Verificar que no hay múltiples definiciones
            const loadViewFunctions = [];
            const safeLoadViewFunctions = [];
            
            for (let key in window) {
                if (typeof window[key] === 'function') {
                    const funcStr = window[key].toString();
                    if (funcStr.includes('loadView') && key === 'loadView') {
                        loadViewFunctions.push(key);
                    }
                    if (funcStr.includes('safeLoadView') && key === 'safeLoadView') {
                        safeLoadViewFunctions.push(key);
                    }
                }
            }
            
            if (loadViewFunctions.length === 1) {
                testResults.push('<div style="color: green;">✅ Solo hay 1 definición de loadView</div>');
            } else {
                testResults.push(`<div style="color: red;">❌ Hay ${loadViewFunctions.length} definiciones de loadView</div>`);
            }
            
            if (safeLoadViewFunctions.length === 1) {
                testResults.push('<div style="color: green;">✅ Solo hay 1 definición de safeLoadView</div>');
            } else {
                testResults.push(`<div style="color: red;">❌ Hay ${safeLoadViewFunctions.length} definiciones de safeLoadView</div>`);
            }
            
            results.innerHTML = testResults.join('');
        });
    </script>
</body>
</html> 