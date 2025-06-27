<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="#"><i data-lucide="home"></i>Inicio</a></li>
            <li class="has-submenu"><a href="#"><i data-lucide="school"></i>Colegios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('school/createSchool')">Registrar Colegio</a></li>
                    <li><a href="#" onclick="loadView('director/hmasterLists')">Ver Colegios</a></li>
                    <li><a href="#" onclick="loadView('director/editDirector')">Editar Colegios</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="users"></i>Coordinadores</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('director/createDirector')">Registrar Coordinador</a></li>
                    <li><a href="#" onclick="loadView('director/editDirector')">Gestionar Coordinadores</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="key"></i>Nómina</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('director/editDirector')">Asignar permisos</a></li>
                    <li><a href="#" onclick="loadView('director/createDirector')">Editar permisos</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="bar-chart-2"></i>Reportes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('director/editDirector')">Crear reporte</a></li>
                    <li><a href="#" onclick="loadView('director/createDirector')">Consultar reporte</a></li>
                    <li><a href="#" onclick="loadView('director/createDirector')">Consultar estadística</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#" onclick="loadView('director/editDirector')"><i data-lucide="settings"></i>Configuración</a></li>
        </ul>
    </div>