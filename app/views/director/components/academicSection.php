<!-- Sección Académica -->
<div class="card h-100 border-success">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">
            <i class="fas fa-graduation-cap"></i>
            Gestión Académica
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6 mb-3">
                <div class="text-center">
                    <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                    <h6>Actividades</h6>
                    <button class="btn btn-success btn-sm" onclick="loadView('activity/dashboard')">
                        <i class="fas fa-plus"></i> Gestionar
                    </button>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="text-center">
                    <i class="fas fa-clock fa-2x text-info mb-2"></i>
                    <h6>Horarios</h6>
                    <button class="btn btn-info btn-sm" onclick="loadView('schedule/schedule')">
                        <i class="fas fa-edit"></i> Gestionar
                    </button>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="text-center">
                    <i class="fas fa-graduation-cap fa-2x text-warning mb-2"></i>
                    <h6>Historial</h6>
                    <button class="btn btn-warning btn-sm" onclick="loadView('student/academicHistory')">
                        <i class="fas fa-search"></i> Ver
                    </button>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="text-center">
                    <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                    <h6>Promedios</h6>
                    <button class="btn btn-primary btn-sm" onclick="loadView('academicAverages')">
                        <i class="fas fa-chart-bar"></i> Ver
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 