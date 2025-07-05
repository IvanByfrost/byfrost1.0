<?php require_once ROOT . '/app/views/layouts/dashHead.php'; ?>
<?php require_once ROOT . '/app/views/student/studentSidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="content-header">
            <h1>Relaciones Clave</h1>
            <p>Acudientes, profesores y relaciones importantes</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-users"></i> Acudientes</h5>
                    </div>
                    <div class="card-body">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <h3>No hay acudientes registrados</h3>
                            <p>Los acudientes serán registrados por el administrador.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-chalkboard-teacher"></i> Profesores</h5>
                    </div>
                    <div class="card-body">
                        <div class="empty-state">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <h3>No hay profesores asignados</h3>
                            <p>Los profesores serán asignados por el coordinador.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-header {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.content-header h1 {
    margin: 0;
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

.content-header p {
    margin: 5px 0 0 0;
    color: #666;
}

.content-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
}

.card-header h5 {
    margin: 0;
    color: #333;
    font-weight: 600;
}

.card-header i {
    margin-right: 8px;
    color: #667eea;
}

.card-body {
    padding: 20px;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.empty-state i {
    font-size: 48px;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 18px;
}

.empty-state p {
    margin: 0;
    font-size: 14px;
}
</style>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?> 