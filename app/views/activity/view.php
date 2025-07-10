<div class="row" id="activityDetails">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Detalles de la Actividad
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Información General</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td><?= $activity['activity_id'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nombre:</strong></td>
                                <td><?= htmlspecialchars($activity['activity_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tipo:</strong></td>
                                <td><span class="badge bg-info"><?= htmlspecialchars($activity['activity_type']) ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Puntaje Máximo:</strong></td>
                                <td><span class="badge bg-success"><?= $activity['max_score'] ?></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Información Académica</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Profesor:</strong></td>
                                <td><?= htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Grupo:</strong></td>
                                <td><?= htmlspecialchars($activity['grade_name'] . ' ' . $activity['group_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Colegio:</strong></td>
                                <td><?= htmlspecialchars($activity['school_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Fecha Límite:</strong></td>
                                <td>
                                    <?php 
                                    $dueDate = new DateTime($activity['due_date']);
                                    $now = new DateTime();
                                    $isOverdue = $dueDate < $now;
                                    $isToday = $dueDate->format('Y-m-d') === $now->format('Y-m-d');
                                    ?>
                                    <span class="badge <?= $isOverdue ? 'bg-danger' : ($isToday ? 'bg-warning' : 'bg-secondary') ?>" 
                                          data-due-date="<?= $activity['due_date'] ?>">
                                        <?= $dueDate->format('d/m/Y H:i') ?>
                                    </span>
                                    <?php if ($isOverdue): ?>
                                        <br><small class="text-danger">Vencida</small>
                                    <?php elseif ($isToday): ?>
                                        <br><small class="text-warning">Vence hoy</small>
                                    <?php else: ?>
                                        <br><small class="text-muted">Pendiente</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <?php if (!empty($activity['description'])): ?>
                    <div class="mt-4">
                        <h6 class="text-muted">Descripción</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <?= nl2br(htmlspecialchars($activity['description'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Estadísticas
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="display-4 text-primary"><?= $activity['max_score'] ?></div>
                    <small class="text-muted">Puntaje Máximo</small>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="editActivity(<?= $activity['activity_id'] ?>)">
                        <i class="fas fa-edit me-2"></i>Editar Actividad
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="viewScores(<?= $activity['activity_id'] ?>)">
                        <i class="fas fa-chart-line me-2"></i>Ver Calificaciones
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteActivity(<?= $activity['activity_id'] ?>)">
                        <i class="fas fa-trash me-2"></i>Eliminar Actividad
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Información Temporal
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="text-primary"><?= $dueDate->format('d') ?></div>
                        <small class="text-muted">Día</small>
                    </div>
                    <div class="col-6">
                        <div class="text-primary"><?= $dueDate->format('M') ?></div>
                        <small class="text-muted">Mes</small>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <small class="text-muted" id="timeRemaining">
                        <?php
                        $interval = $now->diff($dueDate);
                        if ($isOverdue) {
                            echo "Vencida hace " . $interval->days . " días";
                        } elseif ($isToday) {
                            echo "Vence hoy";
                        } else {
                            echo "Vence en " . $interval->days . " días";
                        }
                        ?>
                    </small>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Acciones Adicionales
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-print" data-toggle="tooltip" title="Imprimir detalles">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm btn-share" data-toggle="tooltip" title="Compartir enlace">
                        <i class="fas fa-share me-2"></i>Compartir
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm btn-download" data-toggle="tooltip" title="Descargar PDF">
                        <i class="fas fa-download me-2"></i>Descargar PDF
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm btn-reminder" data-toggle="tooltip" title="Enviar recordatorio">
                        <i class="fas fa-bell me-2"></i>Enviar Recordatorio
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm btn-complete" data-toggle="tooltip" title="Marcar como completada">
                        <i class="fas fa-check me-2"></i>Marcar Completada
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm btn-duplicate" data-toggle="tooltip" title="Duplicar actividad">
                        <i class="fas fa-copy me-2"></i>Duplicar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editActivity(activityId) {
    window.location.href = `activity/showEditForm/${activityId}`;
}

function viewScores(activityId) {
    // Implementar vista de calificaciones
    Swal.fire({
        icon: 'info',
        title: 'Funcionalidad en desarrollo',
        text: 'La vista de calificaciones estará disponible próximamente'
    });
}

function deleteActivity(activityId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `activity/deleteActivity/${activityId}`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: response.msg
                        }).then(() => {
                            window.location.href = 'activity/showDashboard';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al eliminar la actividad'
                    });
                }
            });
        }
    });
}
</script>