<?php
// Verificar si hay datos de la escuela
$school = $school ?? [];
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Detalles de la Escuela</h2>
                    <p>Información completa de la escuela.</p>
                </div>
                <div>
                    <button type="button" class="btn btn-warning me-2" onclick="loadView('school/edit?id=<?php echo $school['school_id']; ?>')">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="loadView('school/consultSchool')">
                        <i class="fas fa-arrow-left"></i> Volver a la Lista
                    </button>
                </div>
            </div>
            
            <?php if (empty($school)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    No se encontró información de la escuela.
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-school"></i> 
                            <?php echo htmlspecialchars($school['school_name']); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Información Básica</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td><?php echo htmlspecialchars($school['school_id']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nombre:</strong></td>
                                        <td><?php echo htmlspecialchars($school['school_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Código DANE:</strong></td>
                                        <td><?php echo htmlspecialchars($school['school_dane']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>NIT:</strong></td>
                                        <td><?php echo htmlspecialchars($school['school_document']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cupo Total:</strong></td>
                                        <td><?php echo htmlspecialchars($school['total_quota'] ?? 'No especificado'); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Información de Contacto</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Dirección:</strong></td>
                                        <td><?php echo htmlspecialchars($school['address'] ?? 'No especificada'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Teléfono:</strong></td>
                                        <td><?php echo htmlspecialchars($school['phone'] ?? 'No especificado'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>
                                            <?php if (!empty($school['email'])): ?>
                                                <a href="mailto:<?php echo htmlspecialchars($school['email']); ?>">
                                                    <?php echo htmlspecialchars($school['email']); ?>
                                                </a>
                                            <?php else: ?>
                                                No especificado
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Personal Asignado</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Director:</strong></td>
                                        <td>
                                            <?php if (!empty($school['director_name'])): ?>
                                                <span class="badge bg-primary">
                                                    <?php echo htmlspecialchars($school['director_name']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No asignado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordinador:</strong></td>
                                        <td>
                                            <?php if (!empty($school['coordinator_name'])): ?>
                                                <span class="badge bg-info">
                                                    <?php echo htmlspecialchars($school['coordinator_name']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No asignado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Estado</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Estado:</strong></td>
                                        <td>
                                            <?php if ($school['is_active']): ?>
                                                <span class="badge bg-success">Activa</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactiva</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h6 class="text-primary mb-3">Acciones</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-warning" onclick="loadView('school/edit?id=<?php echo $school['school_id']; ?>')">
                                    <i class="fas fa-edit"></i> Editar Escuela
                                </button>
                                <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $school['school_id']; ?>)">
                                    <i class="fas fa-trash"></i> Eliminar Escuela
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="loadView('school/consultSchool')">
                                    <i class="fas fa-list"></i> Ver Todas las Escuelas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmDelete(schoolId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta escuela? Esta acción no se puede deshacer.')) {
        loadView('school/delete?id=' + schoolId);
    }
}
</script> 