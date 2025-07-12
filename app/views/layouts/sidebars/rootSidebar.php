<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="?view=rootDashboard"><i data-lucide="home"></i>Inicio</a></li>
            <li class="has-submenu"><a href="#"><i data-lucide="school"></i>Colegios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('school/createSchool')">Registrar Colegio</a></li>
                    <li><a href="#" onclick="loadView('school/consultSchool')">Consultar Colegio</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="user"></i>Usuarios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('user/consultUser')"><i data-lucide="user-search"></i>Consultar Usuario</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="dollar-sign"></i>Nómina</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('payroll/dashboard')"><i data-lucide="bar-chart-3"></i>Dashboard</a></li>
                    <li><a href="#" onclick="loadView('payroll/employees')"><i data-lucide="users"></i>Empleados</a></li>
                    <li><a href="#" onclick="loadView('payroll/periods')"><i data-lucide="calendar"></i>Períodos</a></li>
                    <li><a href="#" onclick="loadView('payroll/absences')"><i data-lucide="user-x"></i>Ausencias</a></li>
                    <li><a href="#" onclick="loadView('payroll/overtime')"><i data-lucide="clock"></i>Horas Extras</a></li>
                    <li><a href="#" onclick="loadView('payroll/bonuses')"><i data-lucide="gift"></i>Bonificaciones</a></li>
                    <li><a href="#" onclick="loadView('payroll/reports')"><i data-lucide="file-text"></i>Reportes</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="key"></i>Permisos</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('role/index')">Editar permisos</a></li>
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
                <a href="#"><i data-lucide="settings"></i>Configuración</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=usuarios')">Gestión de Usuarios</a></li>
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=recuperar')">Recuperar Contraseña</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

