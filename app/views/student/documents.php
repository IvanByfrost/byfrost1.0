<?php require_once ROOT . '/app/views/layouts/dashHead.php'; ?>
<?php require_once ROOT . '/app/views/student/studentSidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="content-header">
            <h1>Documentos</h1>
            <p>Gestiona tus documentos académicos</p>
        </div>

        <div class="content-card">
            <div class="card-header">
                <h5><i class="fas fa-folder"></i> Documentos Académicos</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($documents)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Fecha de Subida</th>
                                    <th>Tamaño</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documents as $document): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($document['name'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($document['type'] ?? ''); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($document['upload_date'] ?? '')); ?></td>
                                        <td><?php echo htmlspecialchars($document['file_size'] ?? ''); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="<?php echo $document['file_path'] ?? '#'; ?>" class="btn btn-sm btn-outline-primary" title="Descargar" target="_blank">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="?controller=student&action=editDocument&documentId=<?php echo $document['id']; ?>" class="btn btn-sm btn-outline-secondary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?controller=student&action=deleteDocument&documentId=<?php echo $document['id']; ?>" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar este documento?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-folder"></i>
                        <h3>No hay documentos</h3>
                        <p>No tienes documentos registrados. Sube documentos para comenzar.</p>
                    </div>
                <?php endif; ?>
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

.table {
    margin-bottom: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #555;
    background: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

.action-buttons {
    display: flex;
    gap: 5px;
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
}

.empty-state p {
    margin: 0;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 14px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 2px;
    }
}
</style>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?> 