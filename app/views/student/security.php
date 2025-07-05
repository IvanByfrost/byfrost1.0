<?php require_once ROOT . '/app/views/layouts/dashHead.php'; ?>
<?php require_once ROOT . '/app/views/student/studentSidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="content-header">
            <h1>Seguridad y Control de Acceso</h1>
            <p>Gestiona la seguridad de tu cuenta</p>
        </div>

        <div class="content-card">
            <div class="card-header">
                <h5><i class="fas fa-shield-alt"></i> Información de Seguridad</h5>
            </div>
            <div class="card-body">
                <div class="security-info">
                    <div class="info-row">
                        <label>Último Acceso:</label>
                        <span><?php echo date('d/m/Y H:i:s'); ?></span>
                    </div>
                    <div class="info-row">
                        <label>Estado de la Cuenta:</label>
                        <span class="status-badge active">Activa</span>
                    </div>
                    <div class="info-row">
                        <label>Rol Asignado:</label>
                        <span>Estudiante</span>
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

.security-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row label {
    font-weight: 600;
    color: #555;
    min-width: 150px;
}

.info-row span {
    color: #333;
    text-align: right;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

@media (max-width: 768px) {
    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .info-row span {
        text-align: left;
    }
}
</style>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?> 