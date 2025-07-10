<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li class="has-submenu"><a href="#" onclick="loadView('director/dashboardHome')" class="active"><i data-lucide="home"></i>Dashboard</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('director/dashboardPartial')"><i data-lucide="layout-dashboard"></i>Vista General</a></li>
                    <li><a href="#" onclick="loadView('director/dashboard')"><i data-lucide="bar-chart-3"></i>Dashboard Completo</a></li>
                    <li><a href="#" onclick="loadView('director/dashboard-simple')"><i data-lucide="activity"></i>Dashboard Simple</a></li>
                    <li><a href="#" onclick="loadView('studentStats/dashboard')"><i data-lucide="trending-up"></i>Estadísticas</a></li>
                    <li><a href="#" onclick="loadView('academicAverages')"><i data-lucide="calculator"></i>Promedios</a></li>
                </ul>
            </li>
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
            <li class="has-submenu"><a href="#"><i data-lucide="bar-chart-2"></i>Reportes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('director/editDirector')">Crear Reporte</a></li>
                    <li><a href="#" onclick="loadView('director/createDirector')">Consultar Reporte</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="settings"></i>Configuración</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=usuarios')">👥 Gestión de Usuarios</a></li>
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=recuperar')">🔐 Recuperar Contraseña</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="book-open"></i>Gestión Académica</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('activity/dashboard')">Actividades</a></li>
                    <li><a href="#" onclick="loadView('schedule/schedule')">Horarios</a></li>
                    <li><a href="#" onclick="loadView('student/academicHistory')">Historial Académico</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<script>
// Debug y fallback mejorado para loadView
console.log('Sidebar del director cargado, verificando loadView...');

if (typeof loadView !== 'function') {
    console.log('loadView no está disponible, creando fallback...');
    window.loadView = function(viewName) {
        console.log('Fallback loadView llamado con:', viewName);
        const baseUrl = window.location.origin + window.location.pathname;
        let url;
        
        if (viewName.includes('/')) {
            const [module, partialView] = viewName.split('/');
            url = `${baseUrl}?view=${module}&action=loadPartial&partialView=${partialView}`;
        } else {
            url = `${baseUrl}?view=${viewName}&action=loadPartial`;
        }
        
        window.location.href = url;
    };
} else {
    console.log('loadView está disponible');
}
</script>