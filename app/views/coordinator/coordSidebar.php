<div class="coordinator-dashboard">
    <div class="coordinator-sidebar">
        <ul>
            <li><a href="#" onclick="safeLoadView('coordinator/dashboard')"><i data-lucide="home"></i>Inicio</a></li>
            <li class="has-submenu"><a href="#"><i data-lucide="users"></i>Estudiantes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('student/createStudent')">Registrar estudiantes</a></li>
                    <li><a href="#" onclick="safeLoadView('student/readStudent')">Consultar estudiantes</a></li>
                    <li><a href="#" onclick="safeLoadView('student/editStudent')">Editar estudiantes</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="graduation-cap"></i>Profesores</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('teacher/createTeacher')">Registrar Profesor</a></li>
                    <li><a href="#" onclick="safeLoadView('teacher/readTeacher')">Consultar Profesores</a></li>
                    <li><a href="#" onclick="safeLoadView('teacher/editTeacher')">Editar Profesores</a></li>
                </ul>
            </li>
            <li class="has-submenu"><a href="#"><i data-lucide="book-open"></i>Materias</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('subject/createSubject')">Registrar Materia</a></li>
                    <li><a href="#" onclick="safeLoadView('subject/readSubject')">Consultar Materias</a></li>
                    <li><a href="#" onclick="safeLoadView('subject/editSubject')">Editar Materias</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="calendar"></i>Horarios</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('schedule/createSchedule')">Crear Horario</a></li>
                    <li><a href="#" onclick="safeLoadView('schedule/readSchedule')">Consultar Horarios</a></li>
                    <li><a href="#" onclick="safeLoadView('schedule/editSchedule')">Editar Horarios</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="dollar-sign"></i>Nómina</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('payroll/dashboard')">📊 Dashboard</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/employees')">👥 Empleados</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/periods')">📅 Períodos</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/absences')">🏥 Ausencias</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/overtime')">⏰ Horas Extras</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/bonuses')">🎁 Bonificaciones</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/reports')">📈 Reportes</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="bar-chart-2"></i>Reportes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('reports/academic')">Reportes Académicos</a></li>
                    <li><a href="#" onclick="safeLoadView('reports/attendance')">Reportes de Asistencia</a></li>
                    <li><a href="#" onclick="safeLoadView('reports/statistics')">Estadísticas</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="settings"></i>Configuración BYFROST</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('user/settingsRoles?section=usuarios')">👥 Usuarios</a></li>
                    <li><a href="#" onclick="safeLoadView('user/settingsRoles?section=recuperar')">🔐 Recuperar contraseña</a></li>
                </ul>
            </li>
        </ul>
    </div>