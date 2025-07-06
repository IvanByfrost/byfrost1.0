<h1>Bienvenido al Dashboard de Administración</h1>
<br>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Colegios</h4>
                <p class="card-text">Registra y gestiona la información de los colegios.</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-success" onclick="loadView('school/createSchool')">
                        <i class="fa fa-plus"></i> Registrar Colegio
                    </button>
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('school/consultSchool')">
                        Consultar Colegios
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Usuarios</h4>
                <p class="card-text">Registra y gestiona la información de los usuarios.</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-success" onclick="loadView('user/consultUser')">
                        <i class="fa fa-plus"></i> Consultar usuarios
                    </button>
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('user/assignRole')">
                        Asignar roles de usuario
                    </a>
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('user/roleHistory')">
                        Consultar historial
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Permisos</h4>
                <p class="card-text">Consulta y asigna permisos a los usuarios.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('user/assignRole')">
                        Asignar permisos de usuario
                    </a>
                    <a href="#" class="btn btn-outline-warning" onclick="loadView('role/index')">
                        <i class="fas fa-shield-alt"></i> Gestionar Roles y Permisos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Nómina</h4>
                <p class="card-text">Administra la nómina y empleados del sistema.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('payroll/dashboard')">
                        <i class="fas fa-chart-line"></i> Dashboard de Nómina
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('payroll/employees')">
                        <i class="fas fa-users"></i> Gestionar Empleados
                    </a>
                    <a href="#" class="btn btn-outline-info" onclick="loadView('payroll/periods')">
                        <i class="fas fa-calendar"></i> Períodos de Pago
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Directores</h4>
                <p class="card-text">Gestiona los directores del sistema.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('director/editDirector')">
                        <i class="fas fa-user-tie"></i> Gestionar Directores
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('director/createDirector')">
                        <i class="fas fa-plus"></i> Crear Director
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Coordinadores</h4>
                <p class="card-text">Administra los coordinadores académicos.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('coordinator/dashboard')">
                        <i class="fas fa-user-graduate"></i> Gestionar Coordinadores
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('coordinator/createCoordinator')">
                        <i class="fas fa-plus"></i> Crear Coordinador
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Estudiantes</h4>
                <p class="card-text">Gestiona la información de los estudiantes.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('student/dashboard')">
                        <i class="fas fa-user-graduate"></i> Dashboard Estudiantes
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('student/academicHistory')">
                        <i class="fas fa-history"></i> Historial Académico
                    </a>
                    <a href="#" class="btn btn-outline-info" onclick="loadView('student/consultStudent')">
                        <i class="fas fa-search"></i> Consultar Estudiantes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Profesores</h4>
                <p class="card-text">Administra la información de los profesores.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('teacher/dashboard')">
                        <i class="fas fa-chalkboard-teacher"></i> Dashboard Profesores
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('teacher/assessStudent')">
                        <i class="fas fa-star"></i> Evaluar Estudiantes
                    </a>
                    <a href="#" class="btn btn-outline-info" onclick="loadView('teacher/readSchedule')">
                        <i class="fas fa-calendar-alt"></i> Ver Horarios
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Actividades</h4>
                <p class="card-text">Gestiona las actividades académicas.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('activity/dashboard')">
                        <i class="fas fa-calendar"></i> Dashboard Actividades
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('activity/create')">
                        <i class="fas fa-plus"></i> Crear Actividad
                    </a>
                    <a href="#" class="btn btn-outline-info" onclick="loadView('activity/edit')">
                        <i class="fas fa-edit"></i> Editar Actividades
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Horarios</h4>
                <p class="card-text">Administra los horarios académicos.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('schedule/schedule')">
                        <i class="fas fa-clock"></i> Ver Horarios
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('schedule/Events')">
                        <i class="fas fa-calendar-day"></i> Eventos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Reportes</h4>
                <p class="card-text">Genera y consulta reportes del sistema.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('report/dashboard')">
                        <i class="fas fa-chart-bar"></i> Dashboard Reportes
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('report/academic')">
                        <i class="fas fa-graduation-cap"></i> Reportes Académicos
                    </a>
                    <a href="#" class="btn btn-outline-info" onclick="loadView('report/financial')">
                        <i class="fas fa-dollar-sign"></i> Reportes Financieros
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Configuración</h4>
                <p class="card-text">Configuración avanzada del sistema.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('user/settingsRoles?section=usuarios')">
                        <i class="fas fa-users"></i> Gestión de Usuarios
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('user/settingsRoles?section=recuperar')">
                        <i class="fas fa-key"></i> Recuperar Contraseña
                    </a>
                    <a href="#" class="btn btn-outline-warning" onclick="loadView('system/settings')">
                        <i class="fas fa-cog"></i> Configuración del Sistema
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Mantenimiento</h4>
                <p class="card-text">Herramientas de mantenimiento del sistema.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('system/backup')">
                        <i class="fas fa-database"></i> Respaldos
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('system/logs')">
                        <i class="fas fa-file-alt"></i> Logs del Sistema
                    </a>
                    <a href="#" class="btn btn-outline-warning" onclick="loadView('system/cleanup')">
                        <i class="fas fa-broom"></i> Limpieza
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>