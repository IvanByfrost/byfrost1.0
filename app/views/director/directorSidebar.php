<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="?view=director&action=dashboard"><i data-lucide="home"></i>Inicio</a></li>
            <li class="has-submenu"><a href="#"><i data-lucide="school"></i>Colegios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('school/createSchool')"><i data-lucide="sparkles"></i>Registrar Colegio</a></li>
                    <li><a href="#" onclick="safeLoadView('school/consultSchool')">Consultar Colegios</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="users"></i>Usuarios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('user/consultUser')"><i data-lucide="user"></i>Consultar Usuarios</a></li>
                    <li><a href="#" onclick="safeLoadView('user/assignRole')"><i data-lucide="user-plus"></i>Asignar Roles</a></li>
                    <li><a href="#" onclick="safeLoadView('user/roleHistory')"><i data-lucide="history"></i>Historial de Roles</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="dollar-sign"></i>Nómina</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('payroll/dashboard')">Dashboard de Nómina</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/employees')">Gestionar Empleados</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/periods')">Períodos de Pago</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="bar-chart-2"></i>Reportes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('director/editDirector')">Crear Reporte</a></li>
                    <li><a href="#" onclick="safeLoadView('director/createDirector')">Consultar Reporte</a></li>
                    <li><a href="#" onclick="safeLoadView('studentStats/dashboard')">📊 Estadísticas de Estudiantes</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="settings"></i>Configuración</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('user/settingsRoles?section=usuarios')">👥 Gestión de Usuarios</a></li>
                    <li><a href="#" onclick="safeLoadView('user/settingsRoles?section=recuperar')">🔐 Recuperar Contraseña</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="book-open"></i>Gestión Académica</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('activity/dashboard')">Actividades</a></li>
                    <li><a href="#" onclick="safeLoadView('schedule/schedule')">Horarios</a></li>
                    <li><a href="#" onclick="safeLoadView('student/academicHistory')">Historial Académico</a></li>
                    <li><a href="#" onclick="safeLoadView('academicAverages')">Promedios Académicos</a></li>
                </ul>
            </li>
        </ul>
    </div>