<?php
// Test específico para el JavaScript del sidebar de nómina
echo "<h1>🔧 Test JavaScript - Sidebar Nómina</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once '../config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test JavaScript - Nómina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <h3>Sidebar de Nómina</h3>
                <div class="root-dashboard">
                    <div class="root-sidebar">
                        <ul>
                            <li class="has-submenu">
                                <a href="#"><i data-lucide="dollar-sign"></i>Nómina</a>
                                <ul class="submenu">
                                    <li><a href="#" onclick="testLoadView('payroll/dashboard')"><i data-lucide="bar-chart-3"></i>Dashboard</a></li>
                                    <li><a href="#" onclick="testLoadView('payroll/employees')"><i data-lucide="users"></i>Empleados</a></li>
                                    <li><a href="#" onclick="testLoadView('payroll/periods')"><i data-lucide="calendar"></i>Períodos</a></li>
                                    <li><a href="#" onclick="testLoadView('payroll/absences')"><i data-lucide="user-x"></i>Ausencias</a></li>
                                    <li><a href="#" onclick="testLoadView('payroll/overtime')"><i data-lucide="clock"></i>Horas Extras</a></li>
                                    <li><a href="#" onclick="testLoadView('payroll/bonuses')"><i data-lucide="gift"></i>Bonificaciones</a></li>
                                    <li><a href="#" onclick="testLoadView('payroll/reports')"><i data-lucide="file-text"></i>Reportes</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <h3>Contenido Principal</h3>
                <div id="mainContent" class="border p-3" style="min-height: 400px;">
                    <p class="text-muted">Haz clic en un enlace del sidebar para probar la carga de vistas.</p>
                </div>
                
                <div class="mt-3">
                    <h4>Logs de JavaScript:</h4>
                    <div id="jsLogs" class="bg-light p-3" style="height: 200px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                        <div>Esperando logs...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Configuración base
        const BASE_URL = '<?php echo url; ?>';
        
        // Función para agregar logs
        function addLog(message, type = 'info') {
            const logsDiv = document.getElementById('jsLogs');
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = document.createElement('div');
            logEntry.innerHTML = `<span style="color: #666;">[${timestamp}]</span> <span style="color: ${type === 'error' ? 'red' : type === 'success' ? 'green' : 'blue'};">${message}</span>`;
            logsDiv.appendChild(logEntry);
            logsDiv.scrollTop = logsDiv.scrollHeight;
        }
        
        // Función de test para cargar vistas
        function testLoadView(viewName) {
            addLog(`Intentando cargar: ${viewName}`, 'info');
            
            const target = document.getElementById("mainContent");
            if (!target) {
                addLog('Error: Elemento mainContent no encontrado', 'error');
                return;
            }
            
            // Mostrar indicador de carga
            target.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';
            addLog('Mostrando indicador de carga', 'info');
            
            // Construir URL
            let url;
            if (viewName.includes('/')) {
                const [controller, actionWithParams] = viewName.split('/');
                const [action, params] = actionWithParams.split('?');
                url = `${BASE_URL}?view=${controller}&action=${action}`;
                
                if (params) {
                    url += `&${params}`;
                }
            } else {
                url = `${BASE_URL}?view=${viewName}`;
            }
            
            addLog(`URL construida: ${url}`, 'info');
            
            // Hacer la petición
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                addLog(`Respuesta del servidor: ${response.status} ${response.statusText}`, 'info');
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.text();
            })
            .then(html => {
                addLog(`Contenido recibido (${html.length} caracteres)`, 'success');
                addLog(`Primeros 100 caracteres: ${html.substring(0, 100)}...`, 'info');
                
                // Verificar si es HTML válido
                const hasHtmlTags = /<[^>]*>/g.test(html);
                
                if (hasHtmlTags) {
                    addLog('Contenido detectado como HTML válido', 'success');
                    target.innerHTML = html;
                } else {
                    addLog('Contenido no es HTML válido, mostrando como texto', 'warning');
                    target.innerHTML = '<div class="alert alert-info">' + html + '</div>';
                }
            })
            .catch(err => {
                addLog(`Error al cargar la vista: ${err.message}`, 'error');
                target.innerHTML = '<div class="alert alert-danger">Error al cargar la vista: ' + err.message + '</div>';
            });
        }
        
        // Función para probar loadView original
        function testOriginalLoadView(viewName) {
            addLog(`Probando loadView original con: ${viewName}`, 'info');
            
            if (typeof loadView === 'function') {
                addLog('Función loadView disponible', 'success');
                loadView(viewName);
            } else {
                addLog('Función loadView NO disponible', 'error');
            }
        }
        
        // Función para probar safeLoadView
        function testSafeLoadView(viewName) {
            addLog(`Probando safeLoadView con: ${viewName}`, 'info');
            
            if (typeof safeLoadView === 'function') {
                addLog('Función safeLoadView disponible', 'success');
                safeLoadView(viewName);
            } else {
                addLog('Función safeLoadView NO disponible', 'error');
            }
        }
        
        // Cargar loadView.js
        function loadLoadViewScript() {
            addLog('Cargando loadView.js...', 'info');
            
            const script = document.createElement('script');
            script.src = BASE_URL + 'app/resources/js/loadView.js';
            script.onload = function() {
                addLog('loadView.js cargado exitosamente', 'success');
            };
            script.onerror = function() {
                addLog('Error cargando loadView.js', 'error');
            };
            document.head.appendChild(script);
        }
        
        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            addLog('Página cargada, inicializando...', 'info');
            loadLoadViewScript();
        });
    </script>
    
    <div class="mt-4">
        <h4>Tests Adicionales:</h4>
        <div class="btn-group" role="group">
            <button class="btn btn-primary" onclick="testLoadView('payroll/dashboard')">Test Dashboard</button>
            <button class="btn btn-success" onclick="testOriginalLoadView('payroll/dashboard')">Test loadView Original</button>
            <button class="btn btn-info" onclick="testSafeLoadView('payroll/dashboard')">Test safeLoadView</button>
        </div>
    </div>
</body>
</html> 