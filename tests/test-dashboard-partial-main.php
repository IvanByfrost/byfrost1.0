<?php
/**
 * Test para verificar que dashboardPartial se carga como vista principal
 */

// Configuración básica
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y sea director
if (!$sessionManager->isLoggedIn() || !$sessionManager->hasRole('director')) {
    echo "❌ Error: Usuario no logueado o no es director";
    exit;
}

echo "✅ Usuario autenticado como director\n";
echo "🔍 Verificando vista principal dashboardPartial...\n\n";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Dashboard Partial Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Test: Dashboard Partial como Vista Principal</h1>
        <div id="testResults" class="alert alert-info">
            <i class="fas fa-spinner fa-spin"></i> Ejecutando tests...
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div id="dashboardContainer">
                    <!-- Aquí se cargará el dashboard -->
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Información del Test</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Vista:</strong> director/dashboardPartial</p>
                        <p><strong>Estado:</strong> <span id="status">Cargando...</span></p>
                        <p><strong>Inicialización:</strong> <span id="initStatus">Pendiente...</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts externos -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Scripts locales -->
    <script>
        const url = '<?php echo url; ?>';
        const BASE_URL = '<?php echo url; ?>';
        window.BYFROST_BASE_URL = '<?php echo url . app ?>';
        
        console.log('=== INICIO TEST DASHBOARD PARTIAL ===');
        
        // Test 1: Cargar dashboardPartial
        function testDashboardPartial() {
            console.log('Test 1: Cargando dashboardPartial...');
            document.getElementById('status').textContent = 'Cargando...';
            
            const container = document.getElementById('dashboardContainer');
            container.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando dashboard parcial...</div>';
            
            // Cargar el dashboard parcial
            fetch(`${url}?view=director&action=loadPartial&partialView=dashboardPartial`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                console.log('✅ Dashboard parcial cargado correctamente');
                document.getElementById('status').textContent = 'Cargado correctamente';
                
                // Verificar que las funciones de inicialización estén disponibles
                setTimeout(() => {
                    if (typeof window.initDirectorDashboardPartial === 'function') {
                        console.log('✅ initDirectorDashboardPartial está disponible');
                        window.initDirectorDashboardPartial();
                        console.log('✅ Dashboard parcial inicializado correctamente');
                        document.getElementById('initStatus').textContent = 'Inicializado correctamente';
                        document.getElementById('testResults').innerHTML = 
                            '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Dashboard parcial cargado e inicializado correctamente</div>';
                    } else {
                        console.log('❌ initDirectorDashboardPartial NO está disponible');
                        document.getElementById('initStatus').textContent = 'No disponible';
                        document.getElementById('testResults').innerHTML = 
                            '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Función de inicialización no disponible</div>';
                    }
                }, 1000);
            })
            .catch(error => {
                console.error('❌ Error cargando dashboard parcial:', error);
                container.innerHTML = '<div class="alert alert-danger">Error cargando dashboard parcial: ' + error.message + '</div>';
                document.getElementById('status').textContent = 'Error';
                document.getElementById('testResults').innerHTML = 
                    '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error cargando dashboard parcial</div>';
            });
        }
        
        // Ejecutar test
        setTimeout(() => {
            testDashboardPartial();
        }, 500);
        
        console.log('=== FIN TEST DASHBOARD PARTIAL ===');
    </script>
</body>
</html> 