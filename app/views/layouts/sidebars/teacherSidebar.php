<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="#" onclick="loadView('teacher/dashboard')"><i data-lucide="home"></i>Inicio</a></li>
            <li><a href="#" onclick="loadView('teacher/gradesDashboard')"><i data-lucide="graduation-cap"></i>Calificaciones</a></li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="bar-chart-2"></i>Actividades</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('reports/attendance')">Reportes de Asistencia</a></li>
                    <li><a href="#" onclick="loadView('reports/actStatistics')">Estadísticas</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="bar-chart-2"></i>Reportes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('reports/attendance')">Reportes de Asistencia</a></li>
                    <li><a href="#" onclick="loadView('reports/attStatistics')">Estadísticas</a></li>
                </ul>
            </li>
            <li><a href="#" onclick="loadView('teacher/assessStudent')"><i data-lucide="user"></i>Evaluar Estudiantes</a></li>
            <li><a href="#" onclick="loadView('teacher/readSchedule')"><i data-lucide="calendar"></i>Horario</a></li>
            <li><a href="#" onclick="loadView('user/profileSettings')"><i data-lucide="settings"></i>Configuración</a></li>
        </ul>
    </div>