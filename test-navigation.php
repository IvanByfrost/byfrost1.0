<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Navegación Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .url-test { background: #f8f9fa; padding: 10px; margin: 5px 0; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>Test: Navegación Dashboard</h1>
    
    <div class="test-section">
        <h2>Estado del Sistema</h2>
        <div id="status">Verificando...</div>
    </div>
    
    <div class="test-section">
        <h2>Pruebas de Navegación</h2>
        <button onclick="testDashboard()">Probar Dashboard</button>
        <button onclick="testAssignRole()">Probar Asignar Roles</button>
        <button onclick="testLoadView()">Probar loadView.js</button>
        <div id="test-results"></div>
    </div>
    
    <div class="test-section">
        <h2>URLs de Prueba</h2>
        <div class="url-test">
            <strong>Dashboard Root:</strong> <a href="http://localhost:8000/?view=root&action=dashboard" target="_blank">http://localhost:8000/?view=root&action=dashboard</a>
        </div>
        <div class="url-test">
            <strong>Asignar Roles (Directo):</strong> <a href="http://localhost:8000/?view=user&action=assignRole" target="_blank">http://localhost:8000/?view=user&action=assignRole</a>
        </div>
        <div class="url-test">
            <strong>Login:</strong> <a href="http://localhost:8000/?view=index&action=login" target="_blank">http://localhost:8000/?view=index&action=login</a>
        </div>
    </div>
    
    <div class="test-section">
        <h2>Instrucciones de Prueba</h2>
        <ol>
            <li><strong>Acceder al dashboard:</strong> <a href="http://localhost:8000/?view=root&action=dashboard" target="_blank">Dashboard</a></li>
            <li><strong>Abrir herramientas de desarrollador</strong> (F12) → Console</li>
            <li><strong>En el dashboard, hacer clic en:</strong> Usuarios → Asignar rol</li>
            <li><strong>Verificar en la consola:</strong>
                <ul>
                    <li>✅ "Cargando vista: user/assignRole"</li>
                    <li>✅ "URL construida: [url correcta]"</li>
                    <li>✅ La vista se carga sin errores</li>
                </ul>
            </li>
            <li><strong>Probar búsqueda:</strong> Llenar formulario y buscar</li>
            <li><strong>Verificar AJAX:</strong> Los resultados aparecen sin recargar la página</li>
        </ol>
    </div>

    <script>
        // Verificar archivos críticos
        async function checkFiles() {
            const files = [
                'app/resources/js/loadView.js',
                'app/views/root/rootSidebar.php',
                'app/views/user/assignRole.php'
            ];
            
            let results = '';
            for (const file of files) {
                try {
                    const response = await fetch(file);
                    if (response.ok) {
                        results += `<div class="success">✅ ${file} - EXISTE</div>`;
                    } else {
                        results += `<div class="error">❌ ${file} - NO EXISTE</div>`;
                    }
                } catch (error) {
                    results += `<div class="error">❌ ${file} - ERROR: ${error.message}</div>`;
                }
            }
            
            document.getElementById('status').innerHTML = results;
        }
        
        // Probar dashboard
        async function testDashboard() {
            const results = document.getElementById('test-results');
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
        
        // Probar asignar roles
        async function testAssignRole() {
            const results = document.getElementById('test-results');
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
        
        // Probar loadView.js
        async function testLoadView() {
            const results = document.getElementById('test-results');
            results.innerHTML = '<div class="info">Probando loadView.js...</div>';
            
            try {
                const response = await fetch('app/resources/js/loadView.js');
                if (response.ok) {
                    const content = await response.text();
                    if (content.includes('loadView') && content.includes('BASE_URL')) {
                        results.innerHTML = '<div class="success">✅ loadView.js existe y tiene la función correcta</div>';
                    } else {
                        results.innerHTML = '<div class="warning">⚠️ loadView.js existe pero puede no tener la función correcta</div>';
                    }
                } else {
                    results.innerHTML = '<div class="error">❌ loadView.js no accesible</div>';
                }
            } catch (error) {
                results.innerHTML = `<div class="error">❌ Error al verificar loadView.js: ${error.message}</div>`;
            }
        }
        
        // Ejecutar verificación al cargar
        document.addEventListener('DOMContentLoaded', function() {
            checkFiles();
        });
    </script>
</body>
</html> 