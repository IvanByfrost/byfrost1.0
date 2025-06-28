<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test AJAX Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Test: Debug AJAX</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test 1: Verificar JavaScript</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary" onclick="testJavaScript()">
                            <i class="fas fa-play"></i> Probar JavaScript
                        </button>
                        <div id="js-result" class="mt-3"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test 2: Simular Búsqueda</h5>
                    </div>
                    <div class="card-body">
                        <form id="testSearchForm">
                            <div class="mb-3">
                                <label for="test_credential_type">Tipo de Documento</label>
                                <select class="form-control" id="test_credential_type" required>
                                    <option value="">Seleccionar</option>
                                    <option value="CC">Cédula de Ciudadanía</option>
                                    <option value="TI">Tarjeta de Identidad</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="test_credential_number">Número</label>
                                <input type="text" class="form-control" id="test_credential_number" 
                                       value="1031180139" placeholder="Número de documento" required>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-search"></i> Probar Búsqueda
                            </button>
                        </form>
                        <div id="search-result" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Test 3: Verificar Archivos</h5>
                    </div>
                    <div class="card-body">
                        <div id="files-result"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Instrucciones de Debug</h5>
                    </div>
                    <div class="card-body">
                        <ol>
                            <li><strong>Ejecuta Test 1:</strong> Verifica que JavaScript funcione</li>
                            <li><strong>Ejecuta Test 2:</strong> Simula la búsqueda AJAX</li>
                            <li><strong>Ejecuta Test 3:</strong> Verifica archivos</li>
                            <li><strong>Abre las herramientas de desarrollador</strong> (F12) y ve a la pestaña Console</li>
                            <li><strong>Ve a:</strong> <a href="http://localhost:8000/?view=root&action=dashboard" target="_blank">Dashboard</a></li>
                            <li><strong>Navega a:</strong> Usuarios → Asignar rol</li>
                            <li><strong>Busca en la consola:</strong> mensajes de error o logs</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Test 1: Verificar JavaScript
        function testJavaScript() {
            const result = document.getElementById('js-result');
            result.innerHTML = '<div class="alert alert-info">✅ JavaScript funcionando correctamente</div>';
            
            // Verificar si fetch está disponible
            if (typeof fetch !== 'undefined') {
                result.innerHTML += '<div class="alert alert-success">✅ Fetch API disponible</div>';
            } else {
                result.innerHTML += '<div class="alert alert-danger">❌ Fetch API no disponible</div>';
            }
            
            // Verificar DOMParser
            if (typeof DOMParser !== 'undefined') {
                result.innerHTML += '<div class="alert alert-success">✅ DOMParser disponible</div>';
            } else {
                result.innerHTML += '<div class="alert alert-danger">❌ DOMParser no disponible</div>';
            }
        }
        
        // Test 2: Simular búsqueda
        document.getElementById('testSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const credentialType = document.getElementById('test_credential_type').value;
            const credentialNumber = document.getElementById('test_credential_number').value;
            const result = document.getElementById('search-result');
            
            if (!credentialType || !credentialNumber) {
                result.innerHTML = '<div class="alert alert-warning">⚠️ Completa todos los campos</div>';
                return;
            }
            
            result.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Probando búsqueda...</div>';
            
            // Simular la URL que se generaría
            const url = `http://localhost:8000/?view=user&action=assignRole&credential_type=${encodeURIComponent(credentialType)}&credential_number=${encodeURIComponent(credentialNumber)}`;
            
            console.log('URL de prueba:', url);
            
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
                console.log('HTML recibido:', html.substring(0, 200) + '...');
                result.innerHTML = '<div class="alert alert-success">✅ Petición AJAX exitosa</div>';
                result.innerHTML += '<div class="alert alert-info">📄 Respuesta recibida (' + html.length + ' caracteres)</div>';
            })
            .catch(error => {
                console.error('Error:', error);
                result.innerHTML = '<div class="alert alert-danger">❌ Error en petición AJAX: ' + error.message + '</div>';
            });
        });
        
        // Test 3: Verificar archivos
        function checkFiles() {
            const result = document.getElementById('files-result');
            result.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Verificando archivos...</div>';
            
            const files = [
                'app/resources/js/assignRole.js',
                'app/views/user/assignRole.php',
                'app/controllers/UserController.php',
                'app/models/UserModel.php'
            ];
            
            let html = '';
            files.forEach(file => {
                html += `<div>📄 <strong>${file}</strong>: <span id="file-${file.replace(/[^a-zA-Z0-9]/g, '-')}">Verificando...</span></div>`;
            });
            
            result.innerHTML = html;
            
            // Verificar archivos via AJAX
            files.forEach(file => {
                fetch(file)
                    .then(response => {
                        const elementId = `file-${file.replace(/[^a-zA-Z0-9]/g, '-')}`;
                        const element = document.getElementById(elementId);
                        if (response.ok) {
                            element.innerHTML = '<span style="color: green;">✅ EXISTE</span>';
                        } else {
                            element.innerHTML = '<span style="color: red;">❌ NO EXISTE</span>';
                        }
                    })
                    .catch(error => {
                        const elementId = `file-${file.replace(/[^a-zA-Z0-9]/g, '-')}`;
                        const element = document.getElementById(elementId);
                        element.innerHTML = '<span style="color: red;">❌ ERROR</span>';
                    });
            });
        }
        
        // Ejecutar verificación de archivos al cargar
        document.addEventListener('DOMContentLoaded', function() {
            checkFiles();
        });
    </script>
</body>
</html> 