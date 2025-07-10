<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y tenga permisos
if (!$sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

if (!$sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}
?>

<script>


// Función de respaldo para loadView
window.loadView = function(viewName) {
    console.log('loadView llamado desde ausencias con:', viewName);
    
    if (typeof loadView === 'function') {
        console.log('loadView disponible, ejecutando...');
        loadView(viewName);
    } else {
        console.error('loadView no está disponible, redirigiendo...');
        // Fallback: redirigir a la página
        const url = `${BASE_URL}?view=${viewName.replace('/', '&action=')}`;
        window.location.href = url;
    }
};
</script>

<div class="container-fluid">
    <div class="row">
        <!-- Contenido principal -->
        <main class="col-12 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-3 mb-4 border-bottom">
                <div>
                    <h1 class="h2 mb-0">Gestión de Ausencias</h1>
                    <p class="text-muted mb-0">Administra las ausencias y licencias de los empleados</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <?php if ($sessionManager->hasRole(['root', 'director', 'coordinator'])): ?>
                        <button type="button" class="btn btn-primary" onclick="loadView('payroll/createAbsence')">
                            <i class="fas fa-plus"></i> Registrar Ausencia
                        </button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-outline-secondary" onclick="loadView('payroll/dashboard')">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="#" class="row g-3">
                        <div class="col-md-3">
                            <label for="employee" class="form-label">Empleado</label>
                            <select class="form-select" id="employee" name="employee">
                                <option value="">Todos los empleados</option>
                                <!-- Aquí se cargarían los empleados dinámicamente -->
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="type" class="form-label">Tipo de Ausencia</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Todos los tipos</option>
                                <option value="sick">Enfermedad</option>
                                <option value="personal">Personal</option>
                                <option value="vacation">Vacaciones</option>
                                <option value="maternity">Maternidad</option>
                                <option value="paternity">Paternidad</option>
                                <option value="bereavement">Duelo</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Todos los estados</option>
                                <option value="pending">Pendiente</option>
                                <option value="approved">Aprobada</option>
                                <option value="rejected">Rechazada</option>
                                <option value="cancelled">Cancelada</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="date_range" class="form-label">Rango de Fechas</label>
                            <input type="text" class="form-control" id="date_range" name="date_range" placeholder="Seleccionar fechas">
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de ausencias -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Registro de Ausencias</h6>
                </div>
                <div class="card-body">
                    <?php if (isset($absences) && !empty($absences)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="absencesTable">
                                <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Tipo</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Días</th>
                                        <th>Estado</th>
                                        <th>Justificación</th>
                                        <th>Registrado por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($absences as $absence): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($absence['first_name'] . ' ' . $absence['last_name']); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($absence['employee_code']); ?></small>
                                            </td>
                                            <td>
                                                <?php 
                                                $typeLabels = [
                                                    'sick' => 'Enfermedad',
                                                    'personal' => 'Personal',
                                                    'vacation' => 'Vacaciones',
                                                    'maternity' => 'Maternidad',
                                                    'paternity' => 'Paternidad',
                                                    'bereavement' => 'Duelo',
                                                    'other' => 'Otro'
                                                ];
                                                $typeColors = [
                                                    'sick' => 'danger',
                                                    'personal' => 'warning',
                                                    'vacation' => 'success',
                                                    'maternity' => 'info',
                                                    'paternity' => 'primary',
                                                    'bereavement' => 'secondary',
                                                    'other' => 'dark'
                                                ];
                                                $type = $absence['absence_type'];
                                                $label = $typeLabels[$type] ?? ucfirst($type);
                                                $color = $typeColors[$type] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $color; ?>"><?php echo $label; ?></span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($absence['start_date'])); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($absence['end_date'])); ?></td>
                                            <td>
                                                <span class="badge bg-info"><?php echo $absence['days_count']; ?> días</span>
                                            </td>
                                            <td>
                                                <?php 
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                    'cancelled' => 'secondary'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Pendiente',
                                                    'approved' => 'Aprobada',
                                                    'rejected' => 'Rechazada',
                                                    'cancelled' => 'Cancelada'
                                                ];
                                                $status = $absence['status'];
                                                $statusLabel = $statusLabels[$status] ?? ucfirst($status);
                                                $statusColor = $statusColors[$status] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $statusColor; ?>"><?php echo $statusLabel; ?></span>
                                            </td>
                                            <td>
                                                <?php if (!empty($absence['reason'])): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="viewJustification('<?php echo htmlspecialchars($absence['reason']); ?>')"
                                                            title="Ver Justificación">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin justificación</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($absence['created_at'] ?? 'now')); ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="loadView('payroll/viewAbsence?id=<?php echo $absence['id']; ?>')"
                                                            title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if ($sessionManager->hasRole(['root', 'director', 'coordinator'])): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                                            onclick="approveAbsence(<?php echo $absence['id']; ?>)"
                                                            title="Aprobar">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="rejectAbsence(<?php echo $absence['id']; ?>)"
                                                            title="Rechazar">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                    <?php if ($sessionManager->hasRole(['root', 'director'])): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                                            onclick="loadView('payroll/editAbsence?id=<?php echo $absence['id']; ?>')"
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Estadísticas -->
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <p class="text-muted">
                                    Total ausencias: <strong><?php echo count($absences); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted">
                                    Pendientes: <strong><?php echo count(array_filter($absences, function($a) { return $a['status'] === 'pending'; })); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted">
                                    Aprobadas: <strong><?php echo count(array_filter($absences, function($a) { return $a['status'] === 'approved'; })); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted">
                                    Total días: <strong><?php echo array_sum(array_column($absences, 'days_count')); ?></strong>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay ausencias registradas</h5>
                            <p class="text-muted">Comienza registrando la primera ausencia</p>
                            <?php if ($sessionManager->hasRole(['root', 'director', 'coordinator'])): ?>
                            <button type="button" class="btn btn-primary" onclick="loadView('payroll/createAbsence')">
                                <i class="fas fa-plus"></i> Registrar Primera Ausencia
                            </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal para ver justificación -->
<div class="modal fade" id="justificationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Justificación de Ausencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="justificationText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para ver justificación
function viewJustification(justification) {
    document.getElementById('justificationText').textContent = justification;
    new bootstrap.Modal(document.getElementById('justificationModal')).show();
}

// Función para aprobar ausencia
function approveAbsence(absenceId) {
    if (confirm('¿Estás seguro de que deseas aprobar esta ausencia?')) {
        // Aquí se implementaría la lógica de aprobación
        alert('Función de aprobación en desarrollo');
    }
}

// Función para rechazar ausencia
function rejectAbsence(absenceId) {
    if (confirm('¿Estás seguro de que deseas rechazar esta ausencia?')) {
        // Aquí se implementaría la lógica de rechazo
        alert('Función de rechazo en desarrollo');
    }
}

// Inicializar DataTable si está disponible
if (typeof $.fn.DataTable !== 'undefined') {
    $('#absencesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        },
        order: [[2, 'desc']] // Ordenar por fecha de inicio descendente
    });
}

// Inicializar date range picker si está disponible
if (typeof $.fn.daterangepicker !== 'undefined') {
    $('#date_range').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta'
        }
    });
}
</script> 

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?> 