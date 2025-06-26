<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="#"><i data-lucide="home"></i>Inicio</a></li>
            <li class="has-submenu"><a href="<?php echo url; ?>#"><i data-lucide="users"></i>Estudiantes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('student/createHmaster')">Registrar estudiantes</a></li>
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Editar estudiantes</a></li>
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Eliminar estudiantes</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="users"></i>Rectores</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('headMaster/createHmaster')">Registrar Rector</a></li>
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Editar el perfil</a></li>
                    <li><a href="#" onclick="loadView('headMaster/hmasterLists')">Consultar Rector</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="user"></i>Usuarios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('headMaster/createHmaster')">Registrar Usuario</a></li>
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Asignar rol Administrador</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="key"></i>Permisos</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Asignar permisos</a></li>
                    <li><a href="#" onclick="loadView('headMaster/createHmaster')">Editar permisos</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="bar-chart-2"></i>Reportes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Crear reporte</a></li>
                    <li><a href="#" onclick="loadView('headMaster/createHmaster')">Consultar reporte</a></li>
                    <li><a href="#" onclick="loadView('headMaster/createHmaster')">Consultar estadística</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="settings"></i>Configuración</a></li>
        </ul>
    </div>