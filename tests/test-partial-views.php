<?php
/**
 * Test para verificar que las vistas parciales se cargan sin header y footer
 */

// Definir constantes necesarias
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/library/SessionManager.php';
require_once ROOT . '/app/controllers/MainController.php';

// Inicializar conexión y SessionManager
$dbConn = getConnection();
$sessionManager = new SessionManager();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Vistas Parciales</title>
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
    </style>
</head>
<body>
    <h1>🧪 Test - Vistas Parciales</h1>
    
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
        <h2>🔍 Verificar Métodos de Vistas Parciales</h2>
        <?php
        // Crear una instancia de MainController para testing
        class TestController extends MainController {
            public function testRenderPartial() {
                return $this->renderPartial('index', 'about');
            }
            
            public function testRenderContent() {
                return $this->renderContent('index', 'about');
            }
            
            public function testLoadPartialView() {
                return $this->loadPartialView('index/about');
            }
        }
        
        $testController = new TestController($dbConn);
        
        echo '<div class="test-result info">';
        echo '<h3>Probando renderPartial():</h3>';
        echo '<div class="content-preview">';
        ob_start();
        $testController->testRenderPartial();
        $partialContent = ob_get_clean();
        echo htmlspecialchars($partialContent);
        echo '</div>';
        
        // Verificar que no contiene header ni footer
        $hasHeader = strpos($partialContent, '<header>') !== false;
        $hasFooter = strpos($partialContent, '<footer>') !== false;
        $hasHtml = strpos($partialContent, '<!DOCTYPE html>') !== false;
        
        echo '<p><strong>Contiene header:</strong> ' . ($hasHeader ? '❌ ERROR' : '✅ OK') . '</p>';
        echo '<p><strong>Contiene footer:</strong> ' . ($hasFooter ? '❌ ERROR' : '✅ OK') . '</p>';
        echo '<p><strong>Contiene DOCTYPE:</strong> ' . ($hasHtml ? '❌ ERROR' : '✅ OK') . '</p>';
        echo '</div>';
        ?>
    </div>

    <div class="test-section">
        <h2>🧪 Probar Carga de Vistas Parciales</h2>
        <p>Selecciona una vista para cargar como parcial:</p>
        
        <select id="viewSelect" style="width: 100%; padding: 8px; margin: 10px 0;">
            <option value="">Selecciona una vista...</option>
            <option value="index/about">Index - About</option>
            <option value="index/contact">Index - Contact</option>
            <option value="index/faq">Index - FAQ</option>
            <option value="school/consultSchool">School - Consult School</option>
            <option value="user/assignRole">User - Assign Role</option>
        </select>
        
        <button onclick="testPartialView()" class="btn btn-primary">🔍 Probar Vista Parcial</button>
        <button onclick="testFullView()" class="btn btn-warning">🔍 Probar Vista Completa</button>
        
        <div id="testResult" style="margin-top: 15px;"></div>
    </div>

    <div class="test-section">
        <h2>🔗 URLs de Prueba</h2>
        <p><strong>Endpoint de vistas parciales:</strong> <a href="<?php echo url; ?>?view=index&action=loadPartial" target="_blank"><?php echo url; ?>?view=index&action=loadPartial</a></p>
        <p><strong>Dashboard con loadView.js:</strong> <a href="<?php echo url; ?>?view=root&action=dashboard" target="_blank"><?php echo url; ?>?view=root&action=dashboard</a></p>
    </div>

    <script>
        const ROOT = "<?php echo url; ?>";
        
        function testPartialView() {
            const viewName = document.getElementById('viewSelect').value;
            if (!viewName) {
                alert('Selecciona una vista primero');
                return;
            }
            
            const resultDiv = document.getElementById('testResult');
            resultDiv.innerHTML = '<div class="test-result info">🔄 Probando vista parcial...</div>';
            
            const formData = new FormData();
            formData.append('view', viewName.split('/')[0]);
            formData.append('action', viewName.split('/')[1]);
            
            fetch(ROOT + '?view=index&action=loadPartial', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(html => {
                console.log('Respuesta de vista parcial:', html);
                
                // Verificar que no contiene elementos de layout
                const hasHeader = html.includes('<header>');
                const hasFooter = html.includes('<footer>');
                const hasHtml = html.includes('<!DOCTYPE html>');
                const hasBody = html.includes('<body>');
                
                let status = 'success';
                let issues = [];
                
                if (hasHeader) issues.push('Contiene header');
                if (hasFooter) issues.push('Contiene footer');
                if (hasHtml) issues.push('Contiene DOCTYPE');
                if (hasBody) issues.push('Contiene body');
                
                if (issues.length > 0) {
                    status = 'error';
                }
                
                resultDiv.innerHTML = `
                    <div class="test-result ${status}">
                        <h3>${status === 'success' ? '✅' : '❌'} Vista Parcial ${status === 'success' ? 'Correcta' : 'Incorrecta'}</h3>
                        ${issues.length > 0 ? '<p><strong>Problemas encontrados:</strong> ' + issues.join(', ') + '</p>' : '<p>✅ No contiene elementos de layout</p>'}
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
        
        function testFullView() {
            const viewName = document.getElementById('viewSelect').value;
            if (!viewName) {
                alert('Selecciona una vista primero');
                return;
            }
            
            const resultDiv = document.getElementById('testResult');
            resultDiv.innerHTML = '<div class="test-result info">🔄 Probando vista completa...</div>';
            
            const url = ROOT + '?view=' + viewName.split('/')[0] + '&action=' + viewName.split('/')[1];
            
            fetch(url)
            .then(res => res.text())
            .then(html => {
                console.log('Respuesta de vista completa:', html);
                
                // Verificar que contiene elementos de layout
                const hasHeader = html.includes('<header>');
                const hasFooter = html.includes('<footer>');
                const hasHtml = html.includes('<!DOCTYPE html>');
                const hasBody = html.includes('<body>');
                
                let status = 'success';
                let issues = [];
                
                if (!hasHeader) issues.push('No contiene header');
                if (!hasFooter) issues.push('No contiene footer');
                if (!hasHtml) issues.push('No contiene DOCTYPE');
                if (!hasBody) issues.push('No contiene body');
                
                if (issues.length > 0) {
                    status = 'error';
                }
                
                resultDiv.innerHTML = `
                    <div class="test-result ${status}">
                        <h3>${status === 'success' ? '✅' : '❌'} Vista Completa ${status === 'success' ? 'Correcta' : 'Incorrecta'}</h3>
                        ${issues.length > 0 ? '<p><strong>Problemas encontrados:</strong> ' + issues.join(', ') + '</p>' : '<p>✅ Contiene elementos de layout</p>'}
                        <div class="content-preview">${html.substring(0, 1000)}...</div>
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
    </script>
</body>
</html> 