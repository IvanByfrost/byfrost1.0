<?php
/**
 * Test para verificar la carga correcta del dashboard del director
 * Sin errores de JavaScript ni conflictos de scripts
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
echo "🔍 Iniciando test de carga del dashboard...\n\n";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Dashboard Loading</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Test de Carga del Dashboard</h1>
        <div id="testResults" class="alert alert-info">
            <i class="fas fa-spinner fa-spin"></i> Ejecutando tests...
        </div>
        
        <div id="dashboardContainer">
            <!-- Aquí se cargará el dashboard -->
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
        
        console.log('=== INICIO TEST DASHBOARD ===');
        
        // Test 1: Verificar que loadView está disponible
        function testLoadView() {
            console.log('Test 1: Verificando loadView...');
            if (typeof window.loadView === 'function') {
                console.log('✅ loadView está disponible');
                return true;
            } else {
                console.log('❌ loadView NO está disponible');
                return false;
            }
        }
        
        // Test 2: Cargar dashboard y verificar inicialización
        function testDashboardLoading() {
            console.log('Test 2: Cargando dashboard...');
            
            const container = document.getElementById('dashboardContainer');
            container.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando dashboard...</div>';
            
            // Cargar el dashboard
            fetch(`${url}?view=director&action=loadPartial&partialView=dashboard`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                console.log('✅ Dashboard cargado correctamente');
                
                // Verificar que las funciones de inicialización estén disponibles
                setTimeout(() => {
                    if (typeof window.initDirectorDashboard === 'function') {
                        console.log('✅ initDirectorDashboard está disponible');
                        window.initDirectorDashboard();
                        console.log('✅ Dashboard inicializado correctamente');
                    } else {
                        console.log('❌ initDirectorDashboard NO está disponible');
                    }
                    
                    // Verificar que no hay errores de JavaScript
                    const errors = [];
                    window.addEventListener('error', (e) => {
                        errors.push(e.error);
                        console.error('❌ Error de JavaScript:', e.error);
                    });
                    
                    setTimeout(() => {
                        if (errors.length === 0) {
                            console.log('✅ No se detectaron errores de JavaScript');
                            document.getElementById('testResults').innerHTML = 
                                '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Todos los tests pasaron correctamente</div>';
                        } else {
                            console.log('❌ Se detectaron errores de JavaScript:', errors);
                            document.getElementById('testResults').innerHTML = 
                                '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Se detectaron errores de JavaScript</div>';
                        }
                    }, 2000);
                }, 1000);
            })
            .catch(error => {
                console.error('❌ Error cargando dashboard:', error);
                container.innerHTML = '<div class="alert alert-danger">Error cargando dashboard: ' + error.message + '</div>';
            });
        }
        
        // Ejecutar tests
        setTimeout(() => {
            const loadViewTest = testLoadView();
            if (loadViewTest) {
                testDashboardLoading();
            } else {
                document.getElementById('testResults').innerHTML = 
                    '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> loadView no está disponible</div>';
            }
        }, 500);
        
        console.log('=== FIN TEST DASHBOARD ===');
    </script>
</body>
</html> 