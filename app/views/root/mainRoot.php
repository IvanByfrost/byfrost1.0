<h1>Bienvenido al Dashboard de Inicio</h1>
<br>
<div class="row g-3">
    <div class="card col-md-4 p-3">
        <div class="card-body">
            <h4 class="card-title">Colegios</h4>
            <p class="card-text">Registra y gestiona la información de los colegios.</p>
            <div class="d-grid gap-2">
                <button class="btn btn-outline-success" onclick="loadView('headMaster/createHmaster')">
                    <i class="fa fa-plus"></i> Registrar Colegio
                </button>
                <a href="#" class="btn btn-outline-primary" onclick="loadView('headMaster/editHmaster')">
                    Reportes
                </a>
            </div>
        </div>
    </div>
    <div class="card col-md-4 p-3">
        <div class="card-body">
            <h4 class="card-title">Rectores</h4>
            <p class="card-text">Registra y gestiona la información de los rectores.</p>
            <div class="d-grid gap-2">
                <button class="btn btn-outline-success" onclick="loadView('headMaster/createHmaster')">
                    <i class="fa fa-plus"></i> Registrar Rectores
                </button>
                <a href="#" class="btn btn-outline-primary" onclick="loadView('headMaster/editHmaster')">
                    Reportes
                </a>
            </div>
        </div>
    </div>
    <div class="card col-md-4 p-3">
        <div class="card-body">
            <h4 class="card-title">Usuarios</h4>
    </div>
</div>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#" onclick="loadView('root/mainRoot')">Inicio</a>
        </li>
        <li class="breadcrumb-item active">Página Actual</li>
    </ol>
</nav>