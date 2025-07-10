<?php
require_once '../config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Sidebar Responsive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../app/resources/css/sidebar.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="root-sidebar">
                <ul>
                    <li><a href="#" onclick="loadView('school/createSchool')"><i class="fas fa-school"></i> Crear Escuela</a></li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-users"></i> Usuarios</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="loadView('user/assignRole')">Asignar Rol</a></li>
                            <li><a href="#" onclick="loadView('user/consultUser')">Consultar Usuario</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-chart-bar"></i> Reportes</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="loadView('payroll/dashboard')">Dashboard</a></li>
                            <li><a href="#" onclick="loadView('payroll/employees')">Empleados</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
        
        <div id="mainContent" class="mainContent">
            <div class="container-fluid">
                <h1>🧪 Test Sidebar Responsive</h1>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Controles de Test</h5>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-primary mb-2" onclick="toggleSidebar()">
                                    <i class="fas fa-bars"></i> Toggle Sidebar
                                </button><br>
                                <button class="btn btn-info mb-2" onclick="closeSidebar()">
                                    <i class="fas fa-times"></i> Cerrar Sidebar
                                </button><br>
                                <button class="btn btn-success mb-2" onclick="testResponsive()">
                                    <i class="fas fa-mobile-alt"></i> Test Responsive
                                </button><br>
                                <button class="btn btn-warning mb-2" onclick="checkSidebarState()">
                                    <i class="fas fa-info-circle"></i> Estado del Sidebar
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Información del Test</h5>
                            </div>
                            <div class="card-body">
                                <div id="testInfo">
                                    <p><strong>Ancho de pantalla:</strong> <span id="screenWidth"></span></p>
                                    <p><strong>Estado del sidebar:</strong> <span id="sidebarState"></span></p>
                                    <p><strong>Clase del contenedor:</strong> <span id="containerClass"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Instrucciones</h5>
                            </div>
                            <div class="card-body">
                                <ol>
                                    <li><strong>Desktop (>768px):</strong> El sidebar debe estar siempre visible a la izquierda</li>
                                    <li><strong>Móvil (≤768px):</strong> El sidebar debe estar oculto por defecto y aparecer al hacer clic en el botón</li>
                                    <li><strong>Submenús:</strong> Deben expandirse/colapsarse al hacer clic</li>
                                    <li><strong>Click fuera:</strong> En móvil, el sidebar debe cerrarse al hacer clic fuera de él</li>
                                </ol>
                                
                                <div class="alert alert-info">
                                    <strong>Para probar responsive:</strong> Redimensiona la ventana del navegador o usa las herramientas de desarrollador (F12) para cambiar el tamaño de la pantalla.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../app/resources/js/loadView.js"></script>
    <script src="../app/resources/js/sidebarToggle.js"></script>
    
    <script>
    function updateTestInfo() {
        const screenWidth = window.innerWidth;
        const dashboardContainer = document.querySelector('.dashboard-container');
        const sidebar = document.querySelector('.root-sidebar');
        
        document.getElementById('screenWidth').textContent = screenWidth + 'px';
        document.getElementById('sidebarState').textContent = dashboardContainer.classList.contains('sidebar-open') ? 'Abierto' : 'Cerrado';
        document.getElementById('containerClass').textContent = dashboardContainer.className;
    }
    
    function checkSidebarState() {
        updateTestInfo();
        console.log('Estado del sidebar verificado');
    }
    
    function testResponsive() {
        const screenWidth = window.innerWidth;
        const isMobile = screenWidth <= 768;
        
        console.log('Test responsive ejecutado');
        console.log('Ancho de pantalla:', screenWidth);
        console.log('Es móvil:', isMobile);
        
        updateTestInfo();
        
        if (isMobile) {
            alert('Modo móvil detectado. El sidebar debería estar oculto por defecto.');
        } else {
            alert('Modo desktop detectado. El sidebar debería estar siempre visible.');
        }
    }
    
    // Actualizar información cuando cambie el tamaño de la ventana
    window.addEventListener('resize', function() {
        updateTestInfo();
        console.log('Ventana redimensionada');
    });
    
    // Actualizar información al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        updateTestInfo();
        console.log('Test de sidebar responsive iniciado');
    });
    
    // Interceptar console.log para mostrar en la página
    const originalLog = console.log;
    console.log = function(...args) {
        originalLog.apply(console, args);
        
        const testInfo = document.getElementById('testInfo');
        if (testInfo.innerHTML.includes('Ancho de pantalla')) {
            testInfo.innerHTML += '<br><small class="text-muted"><i class="fas fa-terminal"></i> ' + args.join(' ') + '</small>';
        }
    };
    </script>
</body>
</html> 