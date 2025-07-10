<?php
require_once '../config.php';
require_once '../app/scripts/connection.php';
require_once '../app/library/SessionManager.php';

// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo "Error: Usuario no logueado";
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Path Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Test Path Debug</h1>
        <p>Debuggeando las rutas que se construyen</p>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Tests de Ruta</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-2 w-100" onclick="testPathDebug()">Test Path Debug</button>
                        <button class="btn btn-success mb-2 w-100" onclick="testDirectPath()">Test Ruta Directa</button>
                        <button class="btn btn-info mb-2 w-100" onclick="testFileExists()">Test File Exists</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Resultados</h5>
                    </div>
                    <div class="card-body">
                        <div id="results">
                            <p class="text-muted">Los resultados aparecerán aquí...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h4>Logs</h4>
            <div id="console-log" class="bg-dark text-light p-3" style="height: 200px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                <div>Test iniciado...</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Interceptar console.log
        const originalLog = console.log;
        const originalError = console.error;
        const consoleDiv = document.getElementById('console-log');
        
        function addToConsole(message, type = 'log') {
            const timestamp = new Date().toLocaleTimeString();
            const color = type === 'error' ? '#ff6b6b' : type === 'warn' ? '#ffd93d' : '#6bcf7f';
            consoleDiv.innerHTML += `<div style="color: ${color};">[${timestamp}] ${message}</div>`;
            consoleDiv.scrollTop = consoleDiv.scrollHeight;
        }
        
        console.log = function(...args) {
            originalLog.apply(console, args);
            addToConsole(args.join(' '));
        };
        
        console.error = function(...args) {
            originalError.apply(console, args);
            addToConsole(args.join(' '), 'error');
        };
        
        function testPathDebug() {
            console.log('=== Test Path Debug ===');
            
            // Test 1: Verificar qué ruta se construye
            fetch('?view=director&action=loadPartial&partialView=dashboardPartial', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Status:', response.status);
                return response.text();
            })
            .then(html => {
                console.log('Contenido completo:', html);
                document.getElementById('results').innerHTML = '<div class="alert alert-info">Respuesta completa:</div><pre>' + html + '</pre>';
            })
            .catch(err => {
                console.error('Error:', err);
                document.getElementById('results').innerHTML = '<div class="alert alert-danger">Error: ' + err.message + '</div>';
            });
        }
        
        function testDirectPath() {
            console.log('=== Test Ruta Directa ===');
            
            // Test 2: Verificar si el archivo existe directamente
            fetch('?view=director&action=loadPartial&partialView=dashboardPartial&force=1')
            .then(response => {
                console.log('Status:', response.status);
                return response.text();
            })
            .then(html => {
                console.log('Contenido:', html.substring(0, 300));
                document.getElementById('results').innerHTML = '<div class="alert alert-success">Ruta directa funcionando</div>' + html;
            })
            .catch(err => {
                console.error('Error:', err);
                document.getElementById('results').innerHTML = '<div class="alert alert-danger">Error: ' + err.message + '</div>';
            });
        }
        
        function testFileExists() {
            console.log('=== Test File Exists ===');
            
            // Test 3: Verificar si el archivo existe
            fetch('?view=director&action=loadPartial&partialView=dashboardPartial&debug=1')
            .then(response => {
                console.log('Status:', response.status);
                return response.text();
            })
            .then(html => {
                console.log('Contenido:', html.substring(0, 300));
                document.getElementById('results').innerHTML = '<div class="alert alert-info">Debug info</div>' + html;
            })
            .catch(err => {
                console.error('Error:', err);
                document.getElementById('results').innerHTML = '<div class="alert alert-danger">Error: ' + err.message + '</div>';
            });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== Test Path Debug ===');
            console.log('ROOT:', '<?= ROOT ?>');
            console.log('URL:', window.location.href);
        });
    </script>
</body>
</html> 