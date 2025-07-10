<?php
/**
 * Test final para verificar que initUserManagementAfterLoad funciona en el dashboard real
 */
require_once '../config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Final: Dashboard Real</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= url . app . rq ?>css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Test Dashboard</h3>
            </div>
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li><a href="#" onclick="testLoadView('consultUser')">Consultar Usuario</a></li>
                    <li><a href="#" onclick="testLoadView('assignRole')">Asignar Rol</a></li>
                    <li><a href="#" onclick="testLoadView('roleHistory')">Historial de Roles</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="mainContent" id="mainContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Test Final: Dashboard Real</h5>
                            </div>
                            <div class="card-body">
                                <p>Este test simula el dashboard real. Haz clic en los enlaces del sidebar para probar la carga de vistas.</p>
                                <div id="testResults"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts externos -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Scripts locales -->
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/onlyNumber.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/toggles.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/loadView.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/sessionHandler.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/userSearch.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/createSchool.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/userManagement.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/roleManagement.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/Uploadpicture.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/User.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/Principal.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/app.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/profileSettings.js"></script> 
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/payrollManagement.js"></script>
    
    <script>
        // Función para probar loadView
        function testLoadView(viewName) {
            const resultsDiv = document.getElementById('testResults');
            resultsDiv.innerHTML = '<div class="alert alert-info">Probando carga de vista: ' + viewName + '</div>';
            
            console.log('=== TEST FINAL ===');
            console.log('Probando loadView con:', viewName);
            
            // Verificar función antes de llamar loadView
            console.log('Estado de initUserManagementAfterLoad antes:', typeof initUserManagementAfterLoad);
            
            if (typeof loadView === 'function') {
                console.log('loadView disponible, ejecutando...');
                loadView(viewName);
            } else {
                console.error('loadView no está disponible');
                resultsDiv.innerHTML = '<div class="alert alert-danger">Error: loadView no está disponible</div>';
            }
        }
        
        // Verificación automática al cargar
        window.addEventListener('load', function() {
            console.log('=== VERIFICACIÓN FINAL ===');
            console.log('Página cargada, verificando funciones...');
            console.log('loadView disponible:', typeof loadView);
            console.log('initUserManagementAfterLoad disponible:', typeof initUserManagementAfterLoad);
            
            if (typeof initUserManagementAfterLoad === 'function') {
                console.log('✅ initUserManagementAfterLoad está disponible');
            } else {
                console.warn('⚠️ initUserManagementAfterLoad NO está disponible');
            }
            
            console.log('=== FIN VERIFICACIÓN ===');
        });
    </script>
</body>
</html> 