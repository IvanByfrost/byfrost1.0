<?php
require_once '../config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Submenu Simple</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../app/resources/css/sidebar.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="root-sidebar">
                <ul>
                    <li><a href="#"><i class="fas fa-home"></i>Inicio</a></li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-school"></i>Colegios</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="alert('Registrar Colegio')">Registrar Colegio</a></li>
                            <li><a href="#" onclick="alert('Consultar Colegio')">Consultar Colegio</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-users"></i>Usuarios</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="alert('Consultar Usuario')">Consultar Usuario</a></li>
                            <li><a href="#" onclick="alert('Asignar rol')">Asignar rol</a></li>
                            <li><a href="#" onclick="alert('Historial de roles')">Historial de roles</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-dollar-sign"></i>Nómina</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="alert('Dashboard')">Dashboard</a></li>
                            <li><a href="#" onclick="alert('Empleados')">Empleados</a></li>
                            <li><a href="#" onclick="alert('Períodos')">Períodos</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
        
        <div id="mainContent" class="mainContent">
            <div class="container-fluid">
                <h1>🧪 Test Submenu Simple</h1>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Instrucciones</h5>
                            </div>
                            <div class="card-body">
                                <ol>
                                    <li><strong>Haz clic en "Colegios"</strong> - Debería expandirse el submenú</li>
                                    <li><strong>Haz clic en "Usuarios"</strong> - Debería expandirse y cerrar "Colegios"</li>
                                    <li><strong>Haz clic en "Nómina"</strong> - Debería expandirse y cerrar "Usuarios"</li>
                                    <li><strong>Haz clic en un elemento del submenú</strong> - Debería mostrar un alert</li>
                                </ol>
                                
                                <div class="alert alert-info">
                                    <strong>Si los submenús no funcionan:</strong> Revisa la consola del navegador (F12) para ver errores.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Estado</h5>
                            </div>
                            <div class="card-body">
                                <div id="status">
                                    <p><strong>sidebarToggle.js cargado:</strong> <span id="scriptStatus">Verificando...</span></p>
                                    <p><strong>Submenús encontrados:</strong> <span id="submenuCount">Verificando...</span></p>
                                    <p><strong>Eventos registrados:</strong> <span id="eventStatus">Verificando...</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../app/resources/js/sidebarToggle.js"></script>
    
    <script>
    // Verificar estado al cargar
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Página cargada, verificando estado...');
        
        // Verificar si sidebarToggle.js está cargado
        const scriptStatus = document.getElementById('scriptStatus');
        if (typeof window.reinitializeSidebarSubmenus === 'function') {
            scriptStatus.textContent = '✅ Cargado';
            scriptStatus.style.color = 'green';
        } else {
            scriptStatus.textContent = '❌ No cargado';
            scriptStatus.style.color = 'red';
        }
        
        // Contar submenús
        const submenus = document.querySelectorAll('.has-submenu');
        const submenuCount = document.getElementById('submenuCount');
        submenuCount.textContent = submenus.length;
        
        // Verificar eventos
        const eventStatus = document.getElementById('eventStatus');
        let eventCount = 0;
        submenus.forEach(submenu => {
            const link = submenu.querySelector('a');
            if (link && link._submenuHandler) {
                eventCount++;
            }
        });
        
        if (eventCount === submenus.length) {
            eventStatus.textContent = '✅ Todos registrados';
            eventStatus.style.color = 'green';
        } else {
            eventStatus.textContent = `❌ ${eventCount}/${submenus.length} registrados`;
            eventStatus.style.color = 'red';
        }
        
        console.log('Estado verificado:', {
            scriptLoaded: typeof window.reinitializeSidebarSubmenus === 'function',
            submenuCount: submenus.length,
            eventCount: eventCount
        });
    });
    
    // Interceptar console.log para debugging
    const originalLog = console.log;
    console.log = function(...args) {
        originalLog.apply(console, args);
        
        // Mostrar en la página si hay errores
        if (args.join(' ').includes('error') || args.join(' ').includes('Error')) {
            const status = document.getElementById('status');
            status.innerHTML += '<div class="alert alert-danger mt-2">Error: ' + args.join(' ') + '</div>';
        }
    };
    </script>
</body>
</html> 