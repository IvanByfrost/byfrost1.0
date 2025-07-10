<?php
require_once '../config.php';
require_once '../app/scripts/connection.php';
require_once '../app/library/SessionManager.php';
require_once '../app/library/PermissionManager.php';

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
    <title>Diagnóstico Completo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Diagnóstico Completo del Sistema</h1>
        <p>Verificando todos los componentes del sistema AJAX</p>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Tests de JavaScript</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-2 w-100" onclick="testLoadView()">Test loadView</button>
                        <button class="btn btn-success mb-2 w-100" onclick="testAjaxRequest()">Test AJAX Request</button>
                        <button class="btn btn-info mb-2 w-100" onclick="testDirectController()">Test Controlador Directo</button>
                        <button class="btn btn-warning mb-2 w-100" onclick="testServerStatus()">Test Estado Servidor</button>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Tests de Vistas</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-outline-primary mb-2 w-100" onclick="loadView('consultUser')">Consultar Usuario</button>
                        <button class="btn btn-outline-success mb-2 w-100" onclick="loadView('createSchool')">Crear Escuela</button>
                        <button class="btn btn-outline-info mb-2 w-100" onclick="loadView('dashboardPartial')">Dashboard Partial</button>
                        <button class="btn btn-outline-warning mb-2 w-100" onclick="loadView('assignRole')">Asignar Rol</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Resultados del Diagnóstico</h5>
                    </div>
                    <div class="card-body">
                        <div id="mainContent">
                            <div class="text-center text-muted">
                                <i class="fas fa-stethoscope fa-2x mb-3"></i>
                                <p>Haz clic en los botones de prueba para diagnosticar el sistema</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h4>Logs de Consola</h4>
            <div id="console-log" class="bg-dark text-light p-3" style="height: 300px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                <div>Diagnóstico iniciado...</div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Cargar loadView.js -->
    <script src="../app/resources/js/loadView.js"></script>
    
    <script>
        // Interceptar console.log para mostrar en la página
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
        
        console.warn = function(...args) {
            originalLog.apply(console, args);
            addToConsole(args.join(' '), 'warn');
        };
        
        // Funciones de diagnóstico
        function testLoadView() {
            console.log('=== Test loadView ===');
            console.log('loadView disponible:', typeof loadView === 'function');
            console.log('safeLoadView disponible:', typeof safeLoadView === 'function');
            
            if (typeof loadView === 'function') {
                console.log('✅ loadView está disponible');
                loadView('consultUser');
            } else {
                console.error('❌ loadView NO está disponible');
            }
        }
        
        function testAjaxRequest() {
            console.log('=== Test AJAX Request ===');
            const url = '?view=user&action=loadPartial&partialView=consultUser';
            console.log('URL de prueba:', url);
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Respuesta del servidor:', response.status, response.statusText);
                return response.text();
            })
            .then(html => {
                console.log('Contenido recibido:', html.substring(0, 200) + '...');
                document.getElementById('mainContent').innerHTML = html;
            })
            .catch(err => {
                console.error('Error en AJAX:', err);
            });
        }
        
        function testDirectController() {
            console.log('=== Test Controlador Directo ===');
            const url = '?view=user&action=consultUser';
            console.log('Accediendo directamente a:', url);
            window.location.href = url;
        }
        
        function testServerStatus() {
            console.log('=== Test Estado Servidor ===');
            fetch('?view=index&action=index')
            .then(response => {
                console.log('Servidor responde:', response.status);
                if (response.ok) {
                    console.log('✅ Servidor funcionando correctamente');
                } else {
                    console.error('❌ Servidor con problemas:', response.status);
                }
            })
            .catch(err => {
                console.error('❌ Error conectando al servidor:', err);
            });
        }
        
        // Verificar estado inicial
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== DIAGNÓSTICO INICIAL ===');
            console.log('loadView disponible:', typeof loadView === 'function');
            console.log('safeLoadView disponible:', typeof safeLoadView === 'function');
            console.log('fetch disponible:', typeof fetch === 'function');
            console.log('URL actual:', window.location.href);
            
            if (typeof loadView === 'function') {
                console.log('✅ loadView.js cargado correctamente');
            } else {
                console.error('❌ loadView.js NO está cargado');
            }
        });
    </script>
</body>
</html> 