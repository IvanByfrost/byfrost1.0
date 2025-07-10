<!-- Sección Administrativa -->
<div class="card h-100 border-primary">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-cogs"></i>
            Gestión Administrativa
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6 mb-3">
                <div class="text-center">
                    <i class="fas fa-school fa-2x text-primary mb-2"></i>
                    <h6>Colegios</h6>
                    <button class="btn btn-primary btn-sm" onclick="loadView('school/createSchool')">
                        <i class="fas fa-plus"></i> Registrar
                    </button>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="text-center">
                    <i class="fas fa-users fa-2x text-info mb-2"></i>
                    <h6>Usuarios</h6>
                    <button class="btn btn-info btn-sm" onclick="loadView('user/consultUser')">
                        <i class="fas fa-users"></i> Gestionar
                    </button>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="text-center">
                    <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                    <h6>Nómina</h6>
                    <button class="btn btn-success btn-sm" onclick="loadView('payroll/dashboard')">
                        <i class="fas fa-chart-bar"></i> Dashboard
                    </button>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="text-center">
                    <i class="fas fa-file-alt fa-2x text-warning mb-2"></i>
                    <h6>Reportes</h6>
                    <button class="btn btn-warning btn-sm" onclick="loadView('director/editDirector')">
                        <i class="fas fa-plus"></i> Crear
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 