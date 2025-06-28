<?php
/**
 * Test para verificar que el endpoint loadPartial funciona correctamente
 */

// Definir constantes necesarias
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar conexión y SessionManager
$dbConn = getConnection();
$sessionManager = new SessionManager();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - AJAX loadPartial</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        .test-result { margin: 10px 0; padding: 10px; border-radius: 3px; }
        .btn { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .content-preview { 
            border: 1px solid #ccc; 
            padding: 10px; 
            margin: 10px 0; 
            background: #f9f9f9; 
            max-height: 300px; 
            overflow-y: auto; 
        }
        .debug-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>🧪 Test - AJAX loadPartial</h1>
    
    <div class="test-section info">
        <h2>Estado Actual del Sistema</h2>
        <p><strong>URL Base:</strong> <?php echo url; ?></p>
        <p><strong>Usuario Logueado:</strong> <?php echo $sessionManager->isLoggedIn() ? 'Sí' : 'No'; ?></p>
        <?php if ($sessionManager->isLoggedIn()): ?>
            <p><strong>Rol:</strong> <?php echo $sessionManager->getUserRole(); ?></p>
            <p><strong>Usuario:</strong> <?php echo $sessionManager->getCurrentUser()['full_name'] ?? 'N/A'; ?></p>
        <?php endif; ?>
    </div>

    <div class="test-section">
        <h2>🔍 Verificar Endpoint loadPartial</h2>
        <p>Prueba directa del endpoint:</p>
        
        <a href="<?php echo url; ?>?view=index&action=loadPartial&view=index&action=about&force=1" target="_blank" class="btn btn-primary">
            🔗 Probar loadPartial Directo
        </a>
        
        <div id="directTest" style="margin-top: 15px;"></div>
    </div>

    <div class="test-section">
        <h2>🧪 Probar Carga AJAX</h2>
        <p>Selecciona una vista para cargar vía AJAX:</p>
        
        <select id="viewSelect" style="width: 100%; padding: 8px; margin: 10px 0;">
            <option value="">Selecciona una vista...</option>
            <option value="index/about">Index - About</option>
            <option value="index/contact">Index - Contact</option>
            <option value="index/faq">Index - FAQ</option>
            <option value="school/consultSchool">School - Consult School</option>
            <option value="user/assignRole">User - Assign Role</option>
        </select>
        
        <button onclick="testAjaxLoad()" class="btn btn-primary">🔍 Probar Carga AJAX</button>
        <button onclick="testAjaxLoadWithHeaders()" class="btn btn-success">🔍 Probar con Headers</button>
        <button onclick="testAjaxLoadWithFetch()" class="btn btn-warning">🔍 Probar con Fetch</button>
        
        <div id="ajaxResult" style="margin-top: 15px;"></div>
    </div>

    <div class="test-section">
        <h2>🔧 Debug de Headers</h2>
        <div class="debug-info">
            <h4>Headers de la petición actual:</h4>
            <pre><?php
                echo "HTTP_X_REQUESTED_WITH: " . ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? 'NO DEFINIDO') . "\n";
                echo "HTTP_ACCEPT: " . ($_SERVER['HTTP_ACCEPT'] ?? 'NO DEFINIDO') . "\n";
                echo "CONTENT_TYPE: " . ($_SERVER['CONTENT_TYPE'] ?? 'NO DEFINIDO') . "\n";
                echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NO DEFINIDO') . "\n";
                echo "POST data: " . print_r($_POST, true) . "\n";
                echo "GET data: " . print_r($_GET, true) . "\n";
            ?></pre>
        </div>
    </div>

    <div class="test-section">
        <h2>🔗 URLs de Prueba</h2>
        <p><strong>Endpoint loadPartial:</strong> <a href="<?php echo url; ?>?view=index&action=loadPartial" target="_blank"><?php echo url; ?>?view=index&action=loadPartial</a></p>
        <p><strong>Con vista específica:</strong> <a href="<?php echo url; ?>?view=index&action=loadPartial&view=index&action=about&force=1" target="_blank"><?php echo url; ?>?view=index&action=loadPartial&view=index&action=about&force=1</a></p>
    </div>

    <script>
        const ROOT = "<?php echo url; ?>";
        
        function testAjaxLoad() {
            const viewName = document.getElementById('viewSelect').value;
            if (!viewName) {
                alert('Selecciona una vista primero');
                return;
            }
            
            const resultDiv = document.getElementById('ajaxResult');
            resultDiv.innerHTML = '<div class="test-result info">🔄 Probando carga AJAX...</div>';
            
            const formData = new FormData();
            formData.append('view', viewName.split('/')[0]);
            formData.append('action', viewName.split('/')[1]);
            
            fetch(ROOT + '?view=index&action=loadPartial', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(html => {
                console.log('Respuesta AJAX:', html);
                
                // Verificar si es un error JSON
                try {
                    const jsonResponse = JSON.parse(html);
                    if (jsonResponse.success === false) {
                        resultDiv.innerHTML = `
                            <div class="test-result error">
                                <h3>❌ Error AJAX</h3>
                                <p><strong>Mensaje:</strong> ${jsonResponse.message}</p>
                                <p><strong>Datos:</strong> ${JSON.stringify(jsonResponse.data)}</p>
                            </div>
                        `;
                        return;
                    }
                } catch (e) {
                    // No es JSON, es HTML normal
                }
                
                resultDiv.innerHTML = `
                    <div class="test-result success">
                        <h3>✅ Carga AJAX Exitosa</h3>
                        <div class="content-preview">${html}</div>
                    </div>
                `;
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = `
                    <div class="test-result error">
                        <h3>❌ Error de Conexión</h3>
                        <p>No se pudo conectar con el servidor.</p>
                        <p>Error: ${error.message}</p>
                    </div>
                `;
            });
        }
        
        function testAjaxLoadWithHeaders() {
            const viewName = document.getElementById('viewSelect').value;
            if (!viewName) {
                alert('Selecciona una vista primero');
                return;
            }
            
            const resultDiv = document.getElementById('ajaxResult');
            resultDiv.innerHTML = '<div class="test-result info">🔄 Probando carga AJAX con headers...</div>';
            
            const formData = new FormData();
            formData.append('view', viewName.split('/')[0]);
            formData.append('action', viewName.split('/')[1]);
            
            fetch(ROOT + '?view=index&action=loadPartial', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                },
                body: formData
            })
            .then(res => res.text())
            .then(html => {
                console.log('Respuesta AJAX con headers:', html);
                
                // Verificar si es un error JSON
                try {
                    const jsonResponse = JSON.parse(html);
                    if (jsonResponse.success === false) {
                        resultDiv.innerHTML = `
                            <div class="test-result error">
                                <h3>❌ Error AJAX con Headers</h3>
                                <p><strong>Mensaje:</strong> ${jsonResponse.message}</p>
                                <p><strong>Datos:</strong> ${JSON.stringify(jsonResponse.data)}</p>
                            </div>
                        `;
                        return;
                    }
                } catch (e) {
                    // No es JSON, es HTML normal
                }
                
                resultDiv.innerHTML = `
                    <div class="test-result success">
                        <h3>✅ Carga AJAX con Headers Exitosa</h3>
                        <div class="content-preview">${html}</div>
                    </div>
                `;
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = `
                    <div class="test-result error">
                        <h3>❌ Error de Conexión</h3>
                        <p>No se pudo conectar con el servidor.</p>
                        <p>Error: ${error.message}</p>
                    </div>
                `;
            });
        }
        
        function testAjaxLoadWithFetch() {
            const viewName = document.getElementById('viewSelect').value;
            if (!viewName) {
                alert('Selecciona una vista primero');
                return;
            }
            
            const resultDiv = document.getElementById('ajaxResult');
            resultDiv.innerHTML = '<div class="test-result info">🔄 Probando carga con fetch moderno...</div>';
            
            const formData = new FormData();
            formData.append('view', viewName.split('/')[0]);
            formData.append('action', viewName.split('/')[1]);
            formData.append('ajax', '1'); // Agregar parámetro ajax
            
            fetch(ROOT + '?view=index&action=loadPartial', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.text())
            .then(html => {
                console.log('Respuesta fetch moderno:', html);
                
                // Verificar si es un error JSON
                try {
                    const jsonResponse = JSON.parse(html);
                    if (jsonResponse.success === false) {
                        resultDiv.innerHTML = `
                            <div class="test-result error">
                                <h3>❌ Error Fetch Moderno</h3>
                                <p><strong>Mensaje:</strong> ${jsonResponse.message}</p>
                                <p><strong>Datos:</strong> ${JSON.stringify(jsonResponse.data)}</p>
                            </div>
                        `;
                        return;
                    }
                } catch (e) {
                    // No es JSON, es HTML normal
                }
                
                resultDiv.innerHTML = `
                    <div class="test-result success">
                        <h3>✅ Carga Fetch Moderno Exitosa</h3>
                        <div class="content-preview">${html}</div>
                    </div>
                `;
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = `
                    <div class="test-result error">
                        <h3>❌ Error de Conexión</h3>
                        <p>No se pudo conectar con el servidor.</p>
                        <p>Error: ${error.message}</p>
                    </div>
                `;
            });
        }
        
        // Cargar vista directa al cargar la página
        window.onload = function() {
            fetch(ROOT + '?view=index&action=loadPartial&view=index&action=about&force=1')
            .then(res => res.text())
            .then(html => {
                document.getElementById('directTest').innerHTML = `
                    <div class="test-result success">
                        <h3>✅ Test Directo Exitoso</h3>
                        <div class="content-preview">${html.substring(0, 500)}...</div>
                    </div>
                `;
            })
            .catch(error => {
                document.getElementById('directTest').innerHTML = `
                    <div class="test-result error">
                        <h3>❌ Error en Test Directo</h3>
                        <p>Error: ${error.message}</p>
                    </div>
                `;
            });
        };
    </script>
</body>
</html> 