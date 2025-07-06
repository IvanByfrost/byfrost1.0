<h1>Bienvenido al Dashboard del Director</h1>
<br>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Colegios</h4>
                <p class="card-text">Gestiona la información de los colegios.</p>
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
                <p class="card-text">Gestiona usuarios y asigna roles.</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-success" onclick="loadView('user/consultUser')">
                        <i class="fa fa-users"></i> Consultar Usuarios
                    </button>
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('user/assignRole')">
                        Asignar Roles
                    </a>
                    <a href="#" class="btn btn-outline-info" onclick="loadView('user/roleHistory')">
                        Historial de Roles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Nómina</h4>
                <p class="card-text">Administra la nómina y empleados.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('payroll/dashboard')">
                        Dashboard de Nómina
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('payroll/employees')">
                        Gestionar Empleados
                    </a>
                    <a href="#" class="btn btn-outline-info" onclick="loadView('payroll/periods')">
                        Períodos de Pago
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
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('director/editDirector')">
                        Crear Reporte
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('director/createDirector')">
                        Consultar Reporte
                    </a>
                    <a href="#" class="btn btn-outline-info" onclick="loadView('director/createDirector')">
                        Estadísticas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Configuración</h4>
                <p class="card-text">Configuración del sistema y usuarios.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('user/settingsRoles?section=usuarios')">
                        <i class="fas fa-users"></i> Gestión de Usuarios
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('user/settingsRoles?section=recuperar')">
                        <i class="fas fa-key"></i> Recuperar Contraseña
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="card-body">
                <h4 class="card-title">Gestión Académica</h4>
                <p class="card-text">Administra aspectos académicos.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('activity/dashboard')">
                        <i class="fas fa-calendar"></i> Actividades
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="loadView('schedule/schedule')">
                        <i class="fas fa-clock"></i> Horarios
                    </a>
                    <a href="#" class="btn btn-outline-info" onclick="loadView('student/academicHistory')">
                        <i class="fas fa-graduation-cap"></i> Historial Académico
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
