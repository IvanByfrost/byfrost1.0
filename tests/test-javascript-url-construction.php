<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test: Construcción de URLs JavaScript</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>🧪 Test: Construcción de URLs JavaScript</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>🔧 Test de Construcción de URLs</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-2" onclick="testUrlConstruction()">
                            <i class="fas fa-play"></i> Probar Construcción de URLs
                        </button>
                        
                        <div id="urlResults" class="mt-3"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>🎯 Test de Carga de Vistas</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-success mb-2" onclick="testLoadView('payroll/createPeriod')">
                            <i class="fas fa-plus"></i> Probar payroll/createPeriod
                        </button>
                        
                        <button class="btn btn-info mb-2" onclick="testLoadView('payroll/periods')">
                            <i class="fas fa-list"></i> Probar payroll/periods
                        </button>
                        
                        <button class="btn btn-warning mb-2" onclick="testLoadView('payroll/dashboard')">
                            <i class="fas fa-tachometer-alt"></i> Probar payroll/dashboard
                        </button>
                        
                        <div id="loadResults" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>📋 Información del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div id="systemInfo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para probar la construcción de URLs
        function testUrlConstruction() {
            const results = document.getElementById('urlResults');
            results.innerHTML = '<div class="alert alert-info">Probando construcción de URLs...</div>';
            
            const baseUrl = window.location.origin + window.location.pathname;
            const testCases = [
                'payroll/createPeriod',
                'payroll/periods',
                'payroll/dashboard',
                'user/assignRole',
                'role/listRoles'
            ];
            
            let html = '<h6>Resultados de Construcción de URLs:</h6><ul>';
            
            testCases.forEach(testCase => {
                let url;
                if (testCase.includes('/')) {
                    const [controller, actionWithParams] = testCase.split('/');
                    const [action, params] = actionWithParams.split('?');
                    url = `${baseUrl}?view=${controller}&action=${action}`;
                    
                    if (params) {
                        url += `&${params}`;
                    }
                } else {
                    url = `${baseUrl}?view=${testCase}`;
                }
                
                html += `<li><strong>${testCase}</strong> → <code>${url}</code></li>`;
            });
            
            html += '</ul>';
            results.innerHTML = html;
        }
        
        // Función para probar la carga de vistas
        function testLoadView(viewName) {
            const results = document.getElementById('loadResults');
            results.innerHTML = `<div class="alert alert-info">Probando carga de: ${viewName}</div>`;
            
            const baseUrl = window.location.origin + window.location.pathname;
            let url;
            
            if (viewName.includes('/')) {
                const [controller, actionWithParams] = viewName.split('/');
                const [action, params] = actionWithParams.split('?');
                url = `${baseUrl}?view=${controller}&action=${action}`;
                
                if (params) {
                    url += `&${params}`;
                }
            } else {
                url = `${baseUrl}?view=${viewName}`;
            }
            
            console.log('URL construida:', url);
            
            // Hacer la petición
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Respuesta:', response.status, response.statusText);
                return response.text();
            })
            .then(html => {
                if (html.includes('<html') || html.includes('<!DOCTYPE')) {
                    results.innerHTML = `<div class="alert alert-success">
                        <i class="fas fa-check"></i> <strong>Éxito:</strong> Vista cargada correctamente
                        <br><small>URL: ${url}</small>
                    </div>`;
                } else {
                    results.innerHTML = `<div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Respuesta inesperada:</strong>
                        <br><small>${html.substring(0, 200)}...</small>
                    </div>`;
                }
            })
            .catch(err => {
                results.innerHTML = `<div class="alert alert-danger">
                    <i class="fas fa-times"></i> <strong>Error:</strong> ${err.message}
                    <br><small>URL: ${url}</small>
                </div>`;
            });
        }
        
        // Mostrar información del sistema
        function showSystemInfo() {
            const info = document.getElementById('systemInfo');
            info.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>🌐 Información del Navegador:</h6>
                        <ul>
                            <li><strong>URL Actual:</strong> ${window.location.href}</li>
                            <li><strong>Origen:</strong> ${window.location.origin}</li>
                            <li><strong>Pathname:</strong> ${window.location.pathname}</li>
                            <li><strong>Base URL:</strong> ${window.location.origin + window.location.pathname}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>🔧 Funciones Disponibles:</h6>
                        <ul>
                            <li><strong>loadView:</strong> ${typeof loadView === 'function' ? '✅ Disponible' : '❌ No disponible'}</li>
                            <li><strong>safeLoadView:</strong> ${typeof safeLoadView === 'function' ? '✅ Disponible' : '❌ No disponible'}</li>
                            <li><strong>fetch:</strong> ${typeof fetch === 'function' ? '✅ Disponible' : '❌ No disponible'}</li>
                        </ul>
                    </div>
                </div>
            `;
        }
        
        // Ejecutar al cargar la página
        window.onload = function() {
            showSystemInfo();
            testUrlConstruction();
        };
    </script>
</body>
</html> 