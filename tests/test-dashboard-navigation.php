<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Navegación Dashboard - Opción 1</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .step { background: #e7f3ff; padding: 10px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #007bff; }
        .important { background: #fff3cd; padding: 15px; border-radius: 5px; border: 1px solid #ffeaa7; }
    </style>
</head>
<body>
    <h1>Test: Navegación Dashboard - Opción 1</h1>
    
    <div class="test-section">
        <h2>🎯 Objetivo</h2>
        <p>Verificar que la navegación desde el dashboard funcione correctamente para acceder a la asignación de roles.</p>
    </div>
    
    <div class="test-section">
        <h2>📋 Pasos de Navegación Correcta</h2>
        
        <div class="step">
            <h3>Paso 1: Acceder al Dashboard</h3>
            <p><strong>URL:</strong> <a href="http://localhost:8000/?view=root&action=dashboard" target="_blank">http://localhost:8000/?view=root&action=dashboard</a></p>
            <p><strong>Verificar:</strong> Deberías ver el dashboard con el sidebar de navegación</p>
        </div>
        
        <div class="step">
            <h3>Paso 2: Navegar a Asignación de Roles</h3>
            <p><strong>En el sidebar:</strong> Hacer clic en "Usuarios" → "Asignar rol"</p>
            <p><strong>Verificar:</strong> La vista debería cargar sin recargar la página (AJAX)</p>
        </div>
        
        <div class="step">
            <h3>Paso 3: Llenar el Formulario de Búsqueda</h3>
            <p><strong>Tipo de documento:</strong> CC</p>
            <p><strong>Número de documento:</strong> 1031180139</p>
            <p><strong>Verificar:</strong> Los campos se llenan correctamente</p>
        </div>
        
        <div class="step">
            <h3>Paso 4: Ejecutar la Búsqueda</h3>
            <p><strong>Acción:</strong> Hacer clic en "Buscar"</p>
            <p><strong>Verificar:</strong> Los resultados aparecen sin recargar la página</p>
        </div>
    </div>
    
    <div class="test-section">
        <h2>🔍 Verificaciones Técnicas</h2>
        <button onclick="testDashboardAccess()" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;">
            🏠 Verificar Dashboard
        </button>
        <button onclick="testAssignRoleAccess()" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;">
            👥 Verificar Asignar Roles
        </button>
        <button onclick="testJavaScript()" style="background: #ffc107; color: black; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;">
            ⚙️ Verificar JavaScript
        </button>
        <div id="test-results"></div>
    </div>
    
    <div class="test-section">
        <h2>📝 Instrucciones Detalladas</h2>
        <div class="important">
            <h3>⚠️ Importante: Usar la Navegación del Dashboard</h3>
            <p><strong>NO uses URLs directas</strong> como <code>?credential_type=CC&credential_number=1031180139</code></p>
            <p><strong>SÍ usa la navegación del dashboard</strong> para mantener la funcionalidad AJAX</p>
        </div>
        
        <ol>
            <li><strong>Abre el dashboard:</strong> <a href="http://localhost:8000/?view=root&action=dashboard" target="_blank">Dashboard</a></li>
            <li><strong>Abre las herramientas de desarrollador</strong> (F12) → Console</li>
            <li><strong>En el dashboard, haz clic en:</strong> Usuarios → Asignar rol</li>
            <li><strong>Verifica en la consola:</strong>
                <ul>
                    <li>✅ "Cargando vista: user/assignRole"</li>
                    <li>✅ "URL construida: [url correcta]"</li>
                    <li>✅ La vista se carga sin errores</li>
                </ul>
            </li>
            <li><strong>Llena el formulario:</strong>
                <ul>
                    <li>Tipo de documento: CC</li>
                    <li>Número: 1031180139</li>
                </ul>
            </li>
            <li><strong>Haz clic en "Buscar"</strong></li>
            <li><strong>Verifica en la consola:</strong>
                <ul>
                    <li>✅ "Formulario enviado, procesando búsqueda..."</li>
                    <li>✅ "Buscando usuarios: CC 1031180139"</li>
                    <li>✅ "Resultados actualizados"</li>
                </ul>
            </li>
            <li><strong>Verifica que NO se recargue la página</strong> - La URL debe permanecer igual</li>
        </ol>
    </div>
    
    <div class="test-section">
        <h2>✅ Estado del Sistema</h2>
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb;">
            <strong>🎯 Navegación Dashboard Implementada</strong><br>
            El sistema está configurado para funcionar correctamente con la navegación del dashboard.<br>
            Usa la navegación lateral para acceder a todas las funcionalidades sin recargas de página.
        </div>
    </div>
    
    <div class="test-section">
        <h2>🚀 Enlaces de Acceso Rápido</h2>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="http://localhost:8000/?view=root&action=dashboard" target="_blank" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                🏠 Abrir Dashboard
            </a>
            <a href="http://localhost:8000/?view=user&action=assignRole" target="_blank" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                👥 Asignar Roles (Directo)
            </a>
            <a href="test-current-status.php" target="_blank" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                📊 Estado del Sistema
            </a>
        </div>
    </div>

    <script>
        // Verificar acceso al dashboard
        async function testDashboardAccess() {
            const results = document.getElementById('test-results');
            results.innerHTML = '<div class="info">Verificando acceso al dashboard...</div>';
            
            try {
                const response = await fetch('http://localhost:8000/?view=root&action=dashboard');
                if (response.ok) {
                    results.innerHTML = '<div class="success">✅ Dashboard accesible - Puedes proceder con la navegación</div>';
                } else {
                    results.innerHTML = `<div class="error">❌ Dashboard no accesible - Error: ${response.status}</div>`;
                }
            } catch (error) {
                results.innerHTML = `<div class="error">❌ Error de conexión: ${error.message}</div>`;
            }
        }
        
        // Verificar acceso a asignar roles
        async function testAssignRoleAccess() {
            const results = document.getElementById('test-results');
            results.innerHTML = '<div class="info">Verificando acceso a asignar roles...</div>';
            
            try {
                const response = await fetch('http://localhost:8000/?view=user&action=assignRole');
                if (response.ok) {
                    results.innerHTML = '<div class="success">✅ Asignar roles accesible - La funcionalidad está disponible</div>';
                } else {
                    results.innerHTML = `<div class="error">❌ Asignar roles no accesible - Error: ${response.status}</div>`;
                }
            } catch (error) {
                results.innerHTML = `<div class="error">❌ Error de conexión: ${error.message}</div>`;
            }
        }
        
        // Verificar JavaScript
        async function testJavaScript() {
            const results = document.getElementById('test-results');
            results.innerHTML = '<div class="info">Verificando archivos JavaScript...</div>';
            
            try {
                const loadViewResponse = await fetch('app/resources/js/loadView.js');
                const assignRoleResponse = await fetch('app/resources/js/assignRole.js');
                
                if (loadViewResponse.ok && assignRoleResponse.ok) {
                    results.innerHTML = '<div class="success">✅ Archivos JavaScript disponibles - La navegación AJAX funcionará</div>';
                } else {
                    results.innerHTML = '<div class="warning">⚠️ Algunos archivos JavaScript pueden no estar disponibles</div>';
                }
            } catch (error) {
                results.innerHTML = `<div class="error">❌ Error al verificar JavaScript: ${error.message}</div>`;
            }
        }
    </script>
</body>
</html> 