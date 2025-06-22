<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="<?php echo ROOT; ?>#"><i data-lucide="home"></i>Inicio</a></li>
            <li><a href="<?php ROOT; ?>#"><i data-lucide="school"></i>Colegios</a></li>
            <li><a href="<?php ROOT; ?>#"><i data-lucide="users"></i>Rectores</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('headMaster/createHmaster')">Registrar Rector</a></li>
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Editar el perfil</a></li>
                </ul>
            </li>
            <li><a href="<?php ROOT; ?>#"><i data-lucide="user"></i>Usuarios</a></li>
            <li><a href="<?php ROOT; ?>#"><i data-lucide="key"></i>Permisos</a></li>
            <li><a href="<?php ROOT; ?>#"><i data-lucide="bar-chart-2"></i>Reportes</a></li>
            <li><a href="<?php ROOT; ?>#"><i data-lucide="settings"></i>Configuración</a></li>
        </ul>
    </div>