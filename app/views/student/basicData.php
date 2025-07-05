<?php require_once ROOT . '/app/views/layouts/dashHead.php'; ?>
<?php require_once ROOT . '/app/views/student/studentSidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="content-header">
            <h1>Datos Básicos del Estudiante</h1>
            <p>Información personal y académica</p>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-user"></i> Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($student): ?>
                            <div class="student-info">
                                <div class="info-row">
                                    <label>Nombre Completo:</label>
                                    <span><?php echo htmlspecialchars($student['name'] ?? 'No disponible'); ?></span>
                                </div>
                                <div class="info-row">
                                    <label>Email:</label>
                                    <span><?php echo htmlspecialchars($student['email'] ?? 'No disponible'); ?></span>
                                </div>
                                <div class="info-row">
                                    <label>Documento:</label>
                                    <span><?php echo htmlspecialchars($student['document'] ?? 'No disponible'); ?></span>
                                </div>
                                <div class="info-row">
                                    <label>Teléfono:</label>
                                    <span><?php echo htmlspecialchars($student['phone'] ?? 'No disponible'); ?></span>
                                </div>
                                <div class="info-row">
                                    <label>Dirección:</label>
                                    <span><?php echo htmlspecialchars($student['address'] ?? 'No disponible'); ?></span>
                                </div>
                                <div class="info-row">
                                    <label>Fecha de Nacimiento:</label>
                                    <span><?php echo htmlspecialchars($student['birth_date'] ?? 'No disponible'); ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No se encontró información del estudiante.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="content-card">
                    <div class="card-header">
                        <h5><i class="fas fa-graduation-cap"></i> Información Académica</h5>
                    </div>
                    <div class="card-body">
                        <div class="academic-info">
                            <div class="info-row">
                                <label>Grado:</label>
                                <span><?php echo htmlspecialchars($student['grade'] ?? 'No disponible'); ?></span>
                            </div>
                            <div class="info-row">
                                <label>Grupo:</label>
                                <span><?php echo htmlspecialchars($student['group'] ?? 'No disponible'); ?></span>
                            </div>
                            <div class="info-row">
                                <label>Estado:</label>
                                <span class="status-badge <?php echo ($student['status'] ?? '') === 'active' ? 'active' : 'inactive'; ?>">
                                    <?php echo ($student['status'] ?? '') === 'active' ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </div>
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

.student-info, .academic-info {
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

.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
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