<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="#"><i data-lucide="home"></i>Inicio</a></li>
            <li><a href="<?php echo url; ?>#"><i data-lucide="school"></i>Colegios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('headMaster/createHmaster')">Registrar Colegio</a></li>
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Reportes</a></li>
                </ul>
            </li>
            <li><a href="<?php echo url; ?>#"><i data-lucide="users"></i>Rectores</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('headMaster/createHmaster')">Registrar Rector</a></li>
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Editar el perfil</a></li>
                </ul>
            </li>
            <li><a href="<?php echo url; ?>#"><i data-lucide="user"></i>Usuarios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('headMaster/createHmaster')">Registrar Usuario</a></li>
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Asignar rol Administrador</a></li>
                </ul>
            </li>
            <li><a href="<?php echo url; ?>#"><i data-lucide="key"></i>Permisos</a></li>
            <ul class="submenu">
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Asignar permisos</a></li>
                    <li><a href="#" onclick="loadView('headMaster/createHmaster')">Editar permisos</a></li>
                </ul>
            <li><a href="<?php echo url; ?>#"><i data-lucide="bar-chart-2"></i>Reportes</a></li>
            <li><a href="<?php echo url; ?>#"><i data-lucide="settings"></i>Configuración</a></li>
        </ul>
    </div>