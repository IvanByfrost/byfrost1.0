<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="#" onclick="loadView('root/menuRoot')"><i data-lucide="home"></i>Inicio</a></li>
            <li class="has-submenu"><a href="#"><i data-lucide="school"></i>Colegios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('school/createSchool')">Registrar Colegio</a></li>
                    <li><a href="#" onclick="loadView('school/consultSchool')">Consultar Colegio</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="users"></i>Rectores</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('director/createDirector')">Registrar Rector</a></li>
                    <li><a href="#" onclick="loadView('director/editDirector')">Editar el perfil</a></li>
                    <li><a href="#" onclick="loadView('director/hmasterLists')">Consultar Rector</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="user"></i>Usuarios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('director/createDirector')">Registrar Usuario</a></li>
                    <li><a href="#" onclick="loadView('director/editDirector')">Asignar rol Administrador</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="key"></i>Permisos</a>
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
            <li class="has-submenu"><a href="#"><i data-lucide="settings"></i>Configuración</a></li>
        </ul>
    </div>