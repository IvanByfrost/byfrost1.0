<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico Completo del Sistema</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .critical { background: #f8d7da; border: 1px solid #f5c6cb; }
        .working { background: #d4edda; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico Completo del Sistema</h1>
    
    <div class="test-section critical">
        <h2>🚨 Estado Crítico</h2>
        <p><strong>El usuario reporta que "Nada funciona"</strong></p>
        <p>Vamos a diagnosticar cada componente del sistema paso a paso.</p>
    </div>
    
    <div class="test-section">
        <h2>📋 Checklist de Diagnóstico</h2>
        <div id="diagnostic-results">Ejecutando diagnóstico...</div>
    </div>
    
    <div class="test-section">
        <h2>🔧 Pruebas Manuales</h2>
        <button onclick="testBasicAccess()" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;">
            🌐 Prueba Acceso Básico
        </button>
        <button onclick="testDashboard()" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;">
            🏠 Prueba Dashboard
        </button>
        <button onclick="testLogin()" style="background: #ffc107; color: black; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;">
            🔐 Prueba Login
        </button>
        <button onclick="testAssignRole()" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;">
            👥 Prueba Asignar Roles
        </button>
        <div id="manual-test-results"></div>
    </div>
    
    <div class="test-section">
        <h2>📁 Verificación de Archivos</h2>
        <div id="files-check"></div>
    </div>
    
    <div class="test-section">
        <h2>🌐 URLs de Prueba</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 10px;">
            <div style="border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                <strong>Página Principal:</strong><br>
                <a href="http://localhost:8000/" target="_blank">http://localhost:8000/</a>
            </div>
            <div style="border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                <strong>Login:</strong><br>
                <a href="http://localhost:8000/?view=index&action=login" target="_blank">http://localhost:8000/?view=index&action=login</a>
            </div>
            <div style="border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                <strong>Dashboard:</strong><br>
                <a href="http://localhost:8000/?view=root&action=dashboard" target="_blank">http://localhost:8000/?view=root&action=dashboard</a>
            </div>
            <div style="border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                <strong>Asignar Roles:</strong><br>
                <a href="http://localhost:8000/?view=user&action=assignRole" target="_blank">http://localhost:8000/?view=user&action=assignRole</a>
            </div>
        </div>
    </div>
    
    <div class="test-section">
        <h2>📝 Información del Sistema</h2>
        <div id="system-info"></div>
    </div>
    
    <div class="test-section">
        <h2>🚀 Acciones Recomendadas</h2>
        <ol>
            <li><strong>Verifica que el servidor esté corriendo:</strong> Deberías poder acceder a http://localhost:8000/</li>
            <li><strong>Revisa la consola del navegador:</strong> F12 → Console para ver errores</li>
            <li><strong>Verifica la base de datos:</strong> Asegúrate de que esté conectada</li>
            <li><strong>Revisa los logs del servidor:</strong> Para errores de PHP</li>
            <li><strong>Prueba cada URL individualmente:</strong> Una por una</li>
        </ol>
    </div>

    <script>
        // Diagnóstico automático
        async function runDiagnostic() {
            const results = document.getElementById('diagnostic-results');
            let html = '<h3>Ejecutando diagnóstico...</h3>';
            
            // Verificar archivos críticos
            const criticalFiles = [
                'index.php',
                'config.php',
                'app/controllers/RootController.php',
                'app/controllers/UserController.php',
                'app/views/root/dashboard.php',
                'app/views/user/assignRole.php',
                'app/resources/js/loadView.js',
                'app/resources/js/assignRole.js'
            ];
            
            html += '<h4>📁 Archivos Críticos:</h4>';
            for (const file of criticalFiles) {
                try {
                    const response = await fetch(file);
                    if (response.ok) {
                        html += `<div class="success">✅ ${file}</div>`;
                    } else {
                        html += `<div class="error">❌ ${file} - Error ${response.status}</div>`;
                    }
                } catch (error) {
                    html += `<div class="error">❌ ${file} - ${error.message}</div>`;
                }
            }
            
            // Verificar acceso básico
            html += '<h4>🌐 Acceso Básico:</h4>';
            try {
                const response = await fetch('http://localhost:8000/');
                if (response.ok) {
                    html += '<div class="success">✅ Servidor accesible</div>';
                } else {
                    html += `<div class="error">❌ Servidor error: ${response.status}</div>`;
                }
            } catch (error) {
                html += `<div class="error">❌ Servidor no accesible: ${error.message}</div>`;
            }
            
            results.innerHTML = html;
        }
        
        // Verificar archivos
        async function checkFiles() {
            const results = document.getElementById('files-check');
            let html = '<h4>Verificando archivos...</h4>';
            
            const files = [
                { name: 'index.php', path: 'index.php' },
                { name: 'config.php', path: 'config.php' },
                { name: 'RootController', path: 'app/controllers/RootController.php' },
                { name: 'UserController', path: 'app/controllers/UserController.php' },
                { name: 'Dashboard View', path: 'app/views/root/dashboard.php' },
                { name: 'AssignRole View', path: 'app/views/user/assignRole.php' },
                { name: 'loadView.js', path: 'app/resources/js/loadView.js' },
                { name: 'assignRole.js', path: 'app/resources/js/assignRole.js' }
            ];
            
            for (const file of files) {
                try {
                    const response = await fetch(file.path);
                    if (response.ok) {
                        const content = await response.text();
                        const size = content.length;
                        html += `<div class="success">✅ ${file.name} (${size} bytes)</div>`;
                    } else {
                        html += `<div class="error">❌ ${file.name} - Error ${response.status}</div>`;
                    }
                } catch (error) {
                    html += `<div class="error">❌ ${file.name} - ${error.message}</div>`;
                }
            }
            
            results.innerHTML = html;
        }
        
        // Información del sistema
        function getSystemInfo() {
            const results = document.getElementById('system-info');
            const info = {
                'User Agent': navigator.userAgent,
                'URL Actual': window.location.href,
                'Protocolo': window.location.protocol,
                'Host': window.location.host,
                'Puerto': window.location.port,
                'Pathname': window.location.pathname,
                'Search': window.location.search
            };
            
            let html = '<h4>Información del Navegador:</h4>';
            for (const [key, value] of Object.entries(info)) {
                html += `<div><strong>${key}:</strong> ${value}</div>`;
            }
            
            results.innerHTML = html;
        }
        
        // Pruebas manuales
        async function testBasicAccess() {
            const results = document.getElementById('manual-test-results');
            results.innerHTML = '<div class="info">Probando acceso básico...</div>';
            
            try {
                const response = await fetch('http://localhost:8000/');
                if (response.ok) {
                    results.innerHTML = '<div class="success">✅ Acceso básico funciona</div>';
                } else {
                    results.innerHTML = `<div class="error">❌ Acceso básico falló: ${response.status}</div>`;
                }
            } catch (error) {
                results.innerHTML = `<div class="error">❌ Error de conexión: ${error.message}</div>`;
            }
        }
        
        async function testDashboard() {
            const results = document.getElementById('manual-test-results');
            results.innerHTML = '<div class="info">Probando dashboard...</div>';
            
            try {
                const response = await fetch('http://localhost:8000/?view=root&action=dashboard');
                if (response.ok) {
                    results.innerHTML = '<div class="success">✅ Dashboard accesible</div>';
                } else {
                    results.innerHTML = `<div class="error">❌ Dashboard error: ${response.status}</div>`;
                }
            } catch (error) {
                results.innerHTML = `<div class="error">❌ Error de conexión: ${error.message}</div>`;
            }
        }
        
        async function testLogin() {
            const results = document.getElementById('manual-test-results');
            results.innerHTML = '<div class="info">Probando login...</div>';
            
            try {
                const response = await fetch('http://localhost:8000/?view=index&action=login');
                if (response.ok) {
                    results.innerHTML = '<div class="success">✅ Login accesible</div>';
                } else {
                    results.innerHTML = `<div class="error">❌ Login error: ${response.status}</div>`;
                }
            } catch (error) {
                results.innerHTML = `<div class="error">❌ Error de conexión: ${error.message}</div>`;
            }
        }
        
        async function testAssignRole() {
            const results = document.getElementById('manual-test-results');
            results.innerHTML = '<div class="info">Probando asignar roles...</div>';
            
            try {
                const response = await fetch('http://localhost:8000/?view=user&action=assignRole');
                if (response.ok) {
                    results.innerHTML = '<div class="success">✅ Asignar roles accesible</div>';
                } else {
                    results.innerHTML = `<div class="error">❌ Asignar roles error: ${response.status}</div>`;
                }
            } catch (error) {
                results.innerHTML = `<div class="error">❌ Error de conexión: ${error.message}</div>`;
            }
        }
        
        // Ejecutar diagnóstico al cargar
        document.addEventListener('DOMContentLoaded', function() {
            runDiagnostic();
            checkFiles();
            getSystemInfo();
        });
    </script>
</body>
</html> 