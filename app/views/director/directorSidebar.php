<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="#"><i data-lucide="home"></i>Inicio</a></li>
            <li class="has-submenu"><a href="#"><i data-lucide="school"></i>Colegios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('school/createSchool')">Registrar Colegio</a></li>
                    <li><a href="#" onclick="loadView('headMaster/hmasterLists')">Ver Colegios</a></li>
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Editar Colegios</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="users"></i>Coordinadores</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('headMaster/createHmaster')">Registrar Coordinador</a></li>
                    <li><a href="#" onclick="loadView('headMaster/editHmaster')">Gestionar Coordinadores</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="key"></i>Nómina</a>
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
            <li class="has-submenu"><a href="#" onclick="loadView('headMaster/editHmaster')"><i data-lucide="settings"></i>Configuración</a></li>
        </ul>
    </div>