<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="#" onclick="loadView('dashboardPartial')"><i data-lucide="home"></i>Inicio</a></li>
            <li class="has-submenu"><a href="#"><i data-lucide="school"></i>Colegios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('createSchool')"><i data-lucide="sparkles"></i>Registrar Colegio</a></li>
                    <li><a href="#" onclick="loadView('consultSchool')">Consultar Colegios</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="users"></i>Usuarios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('consultUser')"><i data-lucide="user"></i>Consultar Usuarios</a></li>
                    <li><a href="#" onclick="loadView('assignRole')"><i data-lucide="user-plus"></i>Asignar Roles</a></li>
                    <li><a href="#" onclick="loadView('roleHistory')"><i data-lucide="history"></i>Historial de Roles</a></li>
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
                    <li><a href="#" onclick="loadView('editDirector')">Crear Reporte</a></li>
                    <li><a href="#" onclick="loadView('createDirector')">Consultar Reporte</a></li>
                    <li><a href="#" onclick="loadView('studentStats/dashboard')">📊 Estadísticas de Estudiantes</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="settings"></i>Configuración</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('settingsRoles?section=usuarios')">👥 Gestión de Usuarios</a></li>
                    <li><a href="#" onclick="loadView('settingsRoles?section=recuperar')">🔐 Recuperar Contraseña</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="book-open"></i>Gestión Académica</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('activity/dashboard')">Actividades</a></li>
                    <li><a href="#" onclick="loadView('schedule/schedule')">Horarios</a></li>
                    <li><a href="#" onclick="loadView('student/academicHistory')">Historial Académico</a></li>
                    <li><a href="#" onclick="loadView('academicAverages')">Promedios Académicos</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<script>
// Debug y fallback mejorado para loadView
console.log('Sidebar cargado, verificando loadView...');

if (typeof loadView !== 'function') {
    console.log('loadView no está disponible, creando fallback...');
    window.loadView = function(viewName) {
        console.log('Fallback loadView llamado con:', viewName);
        window.location.href = '?view=' + viewName;
    };
} else {
    console.log('loadView está disponible');
}

// También para safeLoadView por compatibilidad
if (typeof safeLoadView !== 'function') {
    console.log('safeLoadView no está disponible, creando fallback...');
    window.safeLoadView = function(viewName) {
        console.log('Fallback safeLoadView llamado con:', viewName);
        window.location.href = '?view=' + viewName;
    };
} else {
    console.log('safeLoadView está disponible');
}

// Verificar dashboardManager
if (typeof window.dashboardManager !== 'undefined') {
    console.log('dashboardManager está disponible');
    if (typeof window.dashboardManager.safeLoadView === 'function') {
        console.log('dashboardManager.safeLoadView está disponible');
    } else {
        console.log('dashboardManager.safeLoadView NO está disponible');
    }
} else {
    console.log('dashboardManager NO está disponible');
}

console.log('Sidebar inicializado completamente');
</script>