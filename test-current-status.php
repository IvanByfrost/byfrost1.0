<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Estado Actual del Sistema</title>
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
    <h1>Test: Estado Actual del Sistema</h1>
    
    <div class="test-section">
        <h2>Problema Identificado</h2>
        <p>La URL <code>http://localhost:8000/?credential_type=CC&credential_number=1031180139</code> no funciona porque:</p>
        <ul>
            <li>❌ No tiene el parámetro <code>view</code></li>
            <li>❌ El sistema no sabe qué vista mostrar</li>
            <li>❌ Muestra la página index por defecto</li>
            <li>❌ No ejecuta la búsqueda automáticamente</li>
        </ul>
    </div>
    
    <div class="test-section">
        <h2>Soluciones Disponibles</h2>
        
        <h3>Opción 1: Navegación Correcta desde Dashboard</h3>
        <div class="url-test">
            <strong>Paso 1:</strong> <a href="http://localhost:8000/?view=root&action=dashboard" target="_blank">Acceder al Dashboard</a><br>
            <strong>Paso 2:</strong> Hacer clic en "Usuarios" → "Asignar rol"<br>
            <strong>Paso 3:</strong> Llenar el formulario con CC y 1031180139<br>
            <strong>Paso 4:</strong> Hacer clic en "Buscar"
        </div>
        
        <h3>Opción 2: URL Correcta Directa</h3>
        <div class="url-test">
            <strong>URL que SÍ funciona:</strong><br>
            <a href="http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139" target="_blank">http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139</a>
        </div>
        
        <h3>Opción 3: Implementar Redirección Automática</h3>
        <p>Si quieres que la URL problemática funcione, necesitamos agregar lógica de redirección en <code>index.php</code>.</p>
    </div>
    
    <div class="test-section">
        <h2>Pruebas de Funcionamiento</h2>
        <button onclick="testDashboard()" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;">
            🏠 Probar Dashboard
        </button>
        <button onclick="testCorrectURL()" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;">
            ✅ Probar URL Correcta
        </button>
        <button onclick="testProblematicURL()" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;">
            ❌ Probar URL Problemática
        </button>
        <div id="test-results"></div>
    </div>
    
    <div class="test-section">
        <h2>Instrucciones de Uso</h2>
        <ol>
            <li><strong>Para usar el sistema correctamente:</strong>
                <ul>
                    <li>Accede al dashboard: <a href="http://localhost:8000/?view=root&action=dashboard" target="_blank">Dashboard</a></li>
                    <li>Navega a: Usuarios → Asignar rol</li>
                    <li>Usa el formulario de búsqueda</li>
                    <li>El sistema funcionará con AJAX sin recargas</li>
                </ul>
            </li>
            <li><strong>Si necesitas acceso directo:</strong>
                <ul>
                    <li>Usa la URL completa con <code>view=user&action=assignRole</code></li>
                    <li>No uses URLs sin el parámetro <code>view</code></li>
                </ul>
            </li>
        </ol>
    </div>
    
    <div class="test-section">
        <h2>Estado del Sistema</h2>
        <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; border: 1px solid #ffeaa7;">
            <strong>⚠️ Sistema Funcionando Correctamente</strong><br>
            El problema no es del sistema, sino de la URL que estás usando.<br>
            Usa la navegación del dashboard o la URL correcta para acceder a la funcionalidad.
        </div>
    </div>

    <script>
        // Probar dashboard
        async function testDashboard() {
            const results = document.getElementById('test-results');
            results.innerHTML = '<div class="info">Probando dashboard...</div>';
            
            try {
                const response = await fetch('http://localhost:8000/?view=root&action=dashboard');
                if (response.ok) {
                    results.innerHTML = '<div class="success">✅ Dashboard accesible - Usa la navegación desde aquí</div>';
                } else {
                    results.innerHTML = `<div class="error">❌ Dashboard error: ${response.status}</div>`;
                }
            } catch (error) {
                results.innerHTML = `<div class="error">❌ Error de conexión: ${error.message}</div>`;
            }
        }
        
        // Probar URL correcta
        async function testCorrectURL() {
            const results = document.getElementById('test-results');
            results.innerHTML = '<div class="info">Probando URL correcta...</div>';
            
            try {
                const response = await fetch('http://localhost:8000/?view=user&action=assignRole&credential_type=CC&credential_number=1031180139');
                if (response.ok) {
                    results.innerHTML = '<div class="success">✅ URL correcta funciona - Debería mostrar la página de asignación de roles</div>';
                } else {
                    results.innerHTML = `<div class="error">❌ URL correcta error: ${response.status}</div>`;
                }
            } catch (error) {
                results.innerHTML = `<div class="error">❌ Error de conexión: ${error.message}</div>`;
            }
        }
        
        // Probar URL problemática
        async function testProblematicURL() {
            const results = document.getElementById('test-results');
            results.innerHTML = '<div class="info">Probando URL problemática...</div>';
            
            try {
                const response = await fetch('http://localhost:8000/?credential_type=CC&credential_number=1031180139');
                if (response.ok) {
                    results.innerHTML = '<div class="warning">⚠️ URL problemática accesible pero mostrará la página index (no la funcionalidad esperada)</div>';
                } else {
                    results.innerHTML = `<div class="error">❌ URL problemática error: ${response.status}</div>`;
                }
            } catch (error) {
                results.innerHTML = `<div class="error">❌ Error de conexión: ${error.message}</div>`;
            }
        }
    </script>
</body>
</html> 