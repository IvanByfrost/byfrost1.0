<?php
/**
 * Test para verificar la nueva estructura de submenú del dashboard
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
echo "🔍 Verificando nueva estructura de submenú del dashboard...\n\n";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Dashboard Submenú</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Test: Nueva Estructura de Submenú Dashboard</h1>
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
                        <h5>Tests de Navegación</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <button class="btn btn-primary btn-sm w-100" onclick="testView('director/dashboardHome')">
                                Test Dashboard Home
                            </button>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-success btn-sm w-100" onclick="testView('director/dashboardPartial')">
                                Test Vista General
                            </button>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-info btn-sm w-100" onclick="testView('director/dashboard')">
                                Test Dashboard Completo
                            </button>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-warning btn-sm w-100" onclick="testView('studentStats/dashboard')">
                                Test Estadísticas
                            </button>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-secondary btn-sm w-100" onclick="testView('academicAverages')">
                                Test Promedios
                            </button>
                        </div>
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
        
        console.log('=== INICIO TEST DASHBOARD SUBMENÚ ===');
        
        // Función para probar diferentes vistas
        function testView(viewName) {
            console.log('Test: Cargando vista:', viewName);
            document.getElementById('testResults').innerHTML = 
                '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Cargando: ' + viewName + '</div>';
            
            const container = document.getElementById('dashboardContainer');
            container.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';
            
            // Cargar la vista
            fetch(`${url}?view=${viewName.split('/')[0]}&action=loadPartial&partialView=${viewName.split('/')[1]}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                console.log('✅ Vista cargada correctamente:', viewName);
                document.getElementById('testResults').innerHTML = 
                    '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Vista cargada: ' + viewName + '</div>';
            })
            .catch(error => {
                console.error('❌ Error cargando vista:', error);
                container.innerHTML = '<div class="alert alert-danger">Error cargando vista: ' + error.message + '</div>';
                document.getElementById('testResults').innerHTML = 
                    '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: ' + viewName + '</div>';
            });
        }
        
        // Cargar dashboardHome por defecto
        setTimeout(() => {
            testView('director/dashboardHome');
        }, 500);
        
        console.log('=== FIN TEST DASHBOARD SUBMENÚ ===');
    </script>
</body>
</html> 