<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test RootController</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Test: RootController</h1>
    
    <div class="test-section">
        <h2>Estado del Sistema</h2>
        <div id="status">Verificando...</div>
    </div>
    
    <div class="test-section">
        <h2>Pruebas</h2>
        <button onclick="testDashboard()">Probar Dashboard</button>
        <button onclick="testLogin()">Probar Login</button>
        <div id="test-results"></div>
    </div>
    
    <div class="test-section">
        <h2>Enlaces de Prueba</h2>
        <ul>
            <li><a href="http://localhost:8000/?view=index&action=login" target="_blank">Login</a></li>
            <li><a href="http://localhost:8000/?view=root&action=dashboard" target="_blank">Dashboard Root</a></li>
            <li><a href="http://localhost:8000/?view=user&action=assignRole" target="_blank">Asignar Roles</a></li>
        </ul>
    </div>

    <script>
        // Verificar archivos críticos
        async function checkFiles() {
            const files = [
                'app/controllers/RootController.php',
                'app/views/root/dashboard.php',
                'app/views/root/rootSidebar.php'
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
        
        // Probar login
        async function testLogin() {
            const results = document.getElementById('test-results');
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
        
        // Ejecutar verificación al cargar
        document.addEventListener('DOMContentLoaded', function() {
            checkFiles();
        });
    </script>
</body>
</html> 