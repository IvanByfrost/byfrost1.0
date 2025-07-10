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
    <title>Test Dashboard Partial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Test Dashboard Partial</h1>
        <p>Verificando que dashboardPartial se carga correctamente sin header ni footer</p>
        
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Enlaces de Prueba</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-2 w-100" onclick="testDashboardPartial()">Test Dashboard Partial</button>
                        <button class="btn btn-success mb-2 w-100" onclick="testDashboardFull()">Test Dashboard Completo</button>
                        <button class="btn btn-info mb-2 w-100" onclick="testDirectAccess()">Test Acceso Directo</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h5>Contenido Cargado</h5>
                    </div>
                    <div class="card-body">
                        <div id="mainContent">
                            <div class="text-center text-muted">
                                <i class="fas fa-arrow-left fa-2x mb-3"></i>
                                <p>Haz clic en un botón de prueba para cargar contenido</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h4>Logs de Consola</h4>
            <div id="console-log" class="bg-dark text-light p-3" style="height: 200px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                <div>Consola iniciada...</div>
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
        
        // Funciones de prueba
        function testDashboardPartial() {
            console.log('=== Probando Dashboard Partial ===');
            console.log('Cargando: dashboardPartial');
            loadView('dashboardPartial');
        }
        
        function testDashboardFull() {
            console.log('=== Probando Dashboard Completo ===');
            console.log('Cargando: director/dashboard');
            loadView('director/dashboard');
        }
        
        function testDirectAccess() {
            console.log('=== Probando Acceso Directo ===');
            console.log('Accediendo directamente a: ?view=director&action=loadPartial&partialView=dashboardPartial');
            window.location.href = '?view=director&action=loadPartial&partialView=dashboardPartial';
        }
        
        // Verificar que loadView está disponible
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== Test Dashboard Partial ===');
            console.log('loadView disponible:', typeof loadView === 'function');
            console.log('safeLoadView disponible:', typeof safeLoadView === 'function');
            
            if (typeof loadView === 'function') {
                console.log('✅ loadView.js cargado correctamente');
            } else {
                console.error('❌ loadView no está disponible');
            }
        });
    </script>
</body>
</html> 