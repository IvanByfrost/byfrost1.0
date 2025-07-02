<div class="coordinator-dashboard">
    <div class="coordinator-sidebar">
        <ul>
            <li><a href="#" onclick="loadView('coordinator/dashboard')"><i data-lucide="home"></i>Inicio</a></li>
            <li class="has-submenu"><a href="#"><i data-lucide="users"></i>Estudiantes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('student/createStudent')">Registrar estudiantes</a></li>
                    <li><a href="#" onclick="loadView('student/readStudent')">Consultar estudiantes</a></li>
                    <li><a href="#" onclick="loadView('student/editStudent')">Editar estudiantes</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="graduation-cap"></i>Profesores</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('teacher/createTeacher')">Registrar Profesor</a></li>
                    <li><a href="#" onclick="loadView('teacher/readTeacher')">Consultar Profesores</a></li>
                    <li><a href="#" onclick="loadView('teacher/editTeacher')">Editar Profesores</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="book-open"></i>Materias</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('subject/createSubject')">Registrar Materia</a></li>
                    <li><a href="#" onclick="loadView('subject/readSubject')">Consultar Materias</a></li>
                    <li><a href="#" onclick="loadView('subject/editSubject')">Editar Materias</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="calendar"></i>Horarios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('schedule/createSchedule')">Crear Horario</a></li>
                    <li><a href="#" onclick="loadView('schedule/readSchedule')">Consultar Horarios</a></li>
                    <li><a href="#" onclick="loadView('schedule/editSchedule')">Editar Horarios</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="bar-chart-2"></i>Reportes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('reports/academic')">Reportes Académicos</a></li>
                    <li><a href="#" onclick="loadView('reports/attendance')">Reportes de Asistencia</a></li>
                    <li><a href="#" onclick="loadView('reports/statistics')">Estadísticas</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="settings"></i>Configuración BYFROST</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=usuarios')">👥 Usuarios</a></li>
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=recuperar')">🔐 Recuperar contraseña</a></li>
                </ul>
            </li>
        </ul>
    </div>