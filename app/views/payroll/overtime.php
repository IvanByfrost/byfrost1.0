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
    console.log('loadView llamado desde horas extras con:', viewName);
    
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
                    <h1 class="h2 mb-0">Gestión de Horas Extras</h1>
                    <p class="text-muted mb-0">Administra las horas extras de los empleados</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <?php if ($sessionManager->hasRole(['root', 'director', 'coordinator'])): ?>
                        <button type="button" class="btn btn-primary" onclick="loadView('payroll/createOvertime')">
                            <i class="fas fa-plus"></i> Registrar Horas Extras
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
                            <label for="type" class="form-label">Tipo de Hora Extra</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Todos los tipos</option>
                                <option value="regular">Regular (1.5x)</option>
                                <option value="holiday">Festivo (2x)</option>
                                <option value="night">Nocturna (1.75x)</option>
                                <option value="sunday">Domingo (2x)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Todos los estados</option>
                                <option value="pending">Pendiente</option>
                                <option value="approved">Aprobada</option>
                                <option value="rejected">Rechazada</option>
                                <option value="paid">Pagada</option>
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

            <!-- Lista de horas extras -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Registro de Horas Extras</h6>
                </div>
                <div class="card-body">
                    <?php if (isset($overtime_records) && !empty($overtime_records)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="overtimeTable">
                                <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Fecha</th>
                                        <th>Hora Inicio</th>
                                        <th>Hora Fin</th>
                                        <th>Horas</th>
                                        <th>Tipo</th>
                                        <th>Tarifa</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Justificación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($overtime_records as $overtime): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($overtime['employee_name']); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($overtime['position']); ?></small>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($overtime['date'])); ?></td>
                                            <td><?php echo date('H:i', strtotime($overtime['start_time'])); ?></td>
                                            <td><?php echo date('H:i', strtotime($overtime['end_time'])); ?></td>
                                            <td>
                                                <span class="badge bg-info"><?php echo $overtime['hours']; ?> hrs</span>
                                            </td>
                                            <td>
                                                <?php 
                                                $typeLabels = [
                                                    'regular' => 'Regular (1.5x)',
                                                    'holiday' => 'Festivo (2x)',
                                                    'night' => 'Nocturna (1.75x)',
                                                    'sunday' => 'Domingo (2x)'
                                                ];
                                                $typeColors = [
                                                    'regular' => 'primary',
                                                    'holiday' => 'warning',
                                                    'night' => 'dark',
                                                    'sunday' => 'info'
                                                ];
                                                $type = $overtime['type'];
                                                $label = $typeLabels[$type] ?? ucfirst($type);
                                                $color = $typeColors[$type] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $color; ?>"><?php echo $label; ?></span>
                                            </td>
                                            <td>
                                                <strong>$<?php echo number_format($overtime['hourly_rate'], 2); ?></strong>
                                            </td>
                                            <td>
                                                <strong class="text-success">$<?php echo number_format($overtime['total_amount'], 2); ?></strong>
                                            </td>
                                            <td>
                                                <?php 
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                    'paid' => 'info'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Pendiente',
                                                    'approved' => 'Aprobada',
                                                    'rejected' => 'Rechazada',
                                                    'paid' => 'Pagada'
                                                ];
                                                $status = $overtime['status'];
                                                $statusLabel = $statusLabels[$status] ?? ucfirst($status);
                                                $statusColor = $statusColors[$status] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $statusColor; ?>"><?php echo $statusLabel; ?></span>
                                            </td>
                                            <td>
                                                <?php if (!empty($overtime['justification'])): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="viewJustification('<?php echo htmlspecialchars($overtime['justification']); ?>')"
                                                            title="Ver Justificación">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin justificación</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="loadView('payroll/viewOvertime?id=<?php echo $overtime['overtime_id']; ?>')"
                                                            title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if ($sessionManager->hasRole(['root', 'director', 'coordinator']) && $overtime['status'] === 'pending'): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                                            onclick="approveOvertime(<?php echo $overtime['overtime_id']; ?>)"
                                                            title="Aprobar">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="rejectOvertime(<?php echo $overtime['overtime_id']; ?>)"
                                                            title="Rechazar">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                    <?php if ($sessionManager->hasRole(['root', 'director']) && $overtime['status'] === 'pending'): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                                            onclick="loadView('payroll/editOvertime?id=<?php echo $overtime['overtime_id']; ?>')"
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
                                    Total registros: <strong><?php echo count($overtime_records); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted">
                                    Pendientes: <strong><?php echo count(array_filter($overtime_records, function($o) { return $o['status'] === 'pending'; })); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted">
                                    Total horas: <strong><?php echo array_sum(array_column($overtime_records, 'hours')); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted">
                                    Total a pagar: <strong>$<?php echo number_format(array_sum(array_column($overtime_records, 'total_amount')), 2); ?></strong>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay horas extras registradas</h5>
                            <p class="text-muted">Comienza registrando las primeras horas extras</p>
                            <?php if ($sessionManager->hasRole(['root', 'director', 'coordinator'])): ?>
                            <button type="button" class="btn btn-primary" onclick="loadView('payroll/createOvertime')">
                                <i class="fas fa-plus"></i> Registrar Primeras Horas Extras
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
                <h5 class="modal-title">Justificación de Horas Extras</h5>
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

// Función para aprobar horas extras
function approveOvertime(overtimeId) {
    if (confirm('¿Estás seguro de que deseas aprobar estas horas extras?')) {
        // Aquí se implementaría la lógica de aprobación
        alert('Función de aprobación en desarrollo');
    }
}

// Función para rechazar horas extras
function rejectOvertime(overtimeId) {
    if (confirm('¿Estás seguro de que deseas rechazar estas horas extras?')) {
        // Aquí se implementaría la lógica de rechazo
        alert('Función de rechazo en desarrollo');
    }
}

// Inicializar DataTable si está disponible
if (typeof $.fn.DataTable !== 'undefined') {
    $('#overtimeTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        },
        order: [[1, 'desc']] // Ordenar por fecha descendente
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