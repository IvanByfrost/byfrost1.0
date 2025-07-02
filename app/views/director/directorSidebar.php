<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="#" onclick="loadView('school/createSchool')"><i data-lucide="home"></i>Inicio</a></li>
            <li class="has-submenu"><a href="#"><i data-lucide="school"></i>Colegios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('school/createSchool')"><i data-lucide="sparkles"></i>Registrar Colegio</a></li>
                    <li><a href="#" onclick="loadView('school/consultSchool')">Ver Colegios</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="users"></i>Perfiles</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('user/assignRole')"><i data-lucide="user-plus"></i>Asignar roles</a></li>
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
            <li class="has-submenu">
                <a href="#"><i data-lucide="settings"></i>Configuración BYFROST</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=usuarios')">👥 Usuarios</a></li>
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=agregar')">➕ Agregar usuario</a></li>
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=recuperar')">🔐 Recuperar contraseña</a></li>
                </ul>
            </li>
        </ul>
    </div>