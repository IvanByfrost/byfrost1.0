<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="#" onclick="loadView('director/dashboardHome')" class="active"><i data-lucide="home"></i>Dashboard</a></li>
            
            <li class="has-submenu"><a href="#"><i data-lucide="school"></i>Colegios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('school/createSchool')"><i data-lucide="sparkles"></i>Registrar Colegio</a></li>
                    <li><a href="#" onclick="loadView('school/consultSchool')">Consultar Colegios</a></li>
                </ul>
            </li>
            
            <li class="has-submenu"><a href="#"><i data-lucide="users"></i>Usuarios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('user/consultUser')"><i data-lucide="user"></i>Consultar Usuarios</a></li>
                    <li><a href="#" onclick="loadView('user/assignRole')"><i data-lucide="user-plus"></i>Asignar Roles</a></li>
                    <li><a href="#" onclick="loadView('user/roleHistory')"><i data-lucide="history"></i>Historial de Roles</a></li>
                </ul>
            </li>
            
            <li class="has-submenu"><a href="#"><i data-lucide="dollar-sign"></i>Nómina</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('payroll/dashboard')">Dashboard de Nómina</a></li>
                    <li><a href="#" onclick="loadView('payroll/employees')">Gestionar Empleados</a></li>
                    <li><a href="#" onclick="loadView('payroll/periods')">Períodos de Pago</a></li>
                </ul>
            </li>
            
            <li class="has-submenu"><a href="#"><i data-lucide="book-open"></i>Gestión Académica</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('activity/dashboard')">Actividades</a></li>
                </ul>
            </li>
            
            <li class="has-submenu"><a href="#"><i data-lucide="settings"></i>Configuración</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=usuarios')">👥 Gestión de Usuarios</a></li>
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=recuperar')">🔐 Recuperar Contraseña</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

