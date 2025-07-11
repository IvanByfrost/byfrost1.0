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
                <h4 class="card-title">Reportes</h4>
                <p class="card-text">Genera y consulta reportes del sistema.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary" onclick="loadView('report/dashboard')">
                        <i class="fas fa-chart-bar"></i> Dashboard Reportes
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