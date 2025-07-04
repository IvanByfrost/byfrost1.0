<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y tenga permisos
if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

if (!$this->sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}
?>

<script>
console.log("BASE_URL será configurada en dashFooter.php");

// Función de respaldo para loadView
window.safeLoadView = function(viewName) {
    console.log('safeLoadView llamado desde bonificaciones con:', viewName);
    
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
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/dashboard')">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/employees')">
                            <i class="fas fa-users"></i> Empleados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/periods')">
                            <i class="fas fa-calendar-alt"></i> Períodos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/absences')">
                            <i class="fas fa-user-times"></i> Ausencias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/overtime')">
                            <i class="fas fa-clock"></i> Horas Extras
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="safeLoadView('payroll/bonuses')">
                            <i class="fas fa-gift"></i> Bonificaciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="safeLoadView('payroll/reports')">
                            <i class="fas fa-chart-bar"></i> Reportes
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestión de Bonificaciones</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <?php if ($sessionManager->hasRole(['root', 'director', 'treasurer'])): ?>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="safeLoadView('payroll/createBonus')">
                            <i class="fas fa-plus"></i> Nueva Bonificación
                        </button>
                        <?php endif; ?>
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
                            <label for="type" class="form-label">Tipo de Bonificación</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Todos los tipos</option>
                                <option value="performance">Rendimiento</option>
                                <option value="loyalty">Lealtad</option>
                                <option value="special">Especial</option>
                                <option value="christmas">Navidad</option>
                                <option value="anniversary">Aniversario</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Todos los estados</option>
                                <option value="pending">Pendiente</option>
                                <option value="approved">Aprobada</option>
                                <option value="paid">Pagada</option>
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

            <!-- Lista de bonificaciones -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Registro de Bonificaciones</h6>
                </div>
                <div class="card-body">
                    <?php if (isset($bonuses) && !empty($bonuses)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="bonusesTable">
                                <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th>Período</th>
                                        <th>Estado</th>
                                        <th>Aprobado por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bonuses as $bonus): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($bonus['employee_name']); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($bonus['position']); ?></small>
                                            </td>
                                            <td>
                                                <?php 
                                                $typeLabels = [
                                                    'performance' => 'Rendimiento',
                                                    'loyalty' => 'Lealtad',
                                                    'special' => 'Especial',
                                                    'christmas' => 'Navidad',
                                                    'anniversary' => 'Aniversario',
                                                    'other' => 'Otro'
                                                ];
                                                $typeColors = [
                                                    'performance' => 'success',
                                                    'loyalty' => 'primary',
                                                    'special' => 'warning',
                                                    'christmas' => 'danger',
                                                    'anniversary' => 'info',
                                                    'other' => 'secondary'
                                                ];
                                                $type = $bonus['type'];
                                                $label = $typeLabels[$type] ?? ucfirst($type);
                                                $color = $typeColors[$type] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $color; ?>"><?php echo $label; ?></span>
                                            </td>
                                            <td>
                                                <?php if (strlen($bonus['description']) > 50): ?>
                                                    <?php echo htmlspecialchars(substr($bonus['description'], 0, 50)) . '...'; ?>
                                                    <button type="button" class="btn btn-sm btn-link" 
                                                            onclick="viewDescription('<?php echo htmlspecialchars($bonus['description']); ?>')"
                                                            title="Ver descripción completa">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <?php echo htmlspecialchars($bonus['description']); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong class="text-success">$<?php echo number_format($bonus['amount'], 2); ?></strong>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($bonus['date'])); ?></td>
                                            <td>
                                                <?php if (isset($bonus['period_name'])): ?>
                                                    <span class="badge bg-info"><?php echo htmlspecialchars($bonus['period_name']); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin período</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'paid' => 'info',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Pendiente',
                                                    'approved' => 'Aprobada',
                                                    'paid' => 'Pagada',
                                                    'cancelled' => 'Cancelada'
                                                ];
                                                $status = $bonus['status'];
                                                $statusLabel = $statusLabels[$status] ?? ucfirst($status);
                                                $statusColor = $statusColors[$status] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $statusColor; ?>"><?php echo $statusLabel; ?></span>
                                            </td>
                                            <td>
                                                <small><?php echo htmlspecialchars($bonus['approved_by_name'] ?? 'Pendiente'); ?></small>
                                                <?php if (isset($bonus['approved_at'])): ?>
                                                    <br><small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($bonus['approved_at'])); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="safeLoadView('payroll/viewBonus?id=<?php echo $bonus['bonus_id']; ?>')"
                                                            title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if ($sessionManager->hasRole(['root', 'director', 'treasurer']) && $bonus['status'] === 'pending'): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                                            onclick="approveBonus(<?php echo $bonus['bonus_id']; ?>)"
                                                            title="Aprobar">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="cancelBonus(<?php echo $bonus['bonus_id']; ?>)"
                                                            title="Cancelar">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                    <?php if ($sessionManager->hasRole(['root', 'director']) && $bonus['status'] === 'pending'): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                                            onclick="safeLoadView('payroll/editBonus?id=<?php echo $bonus['bonus_id']; ?>')"
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
                                    Total bonificaciones: <strong><?php echo count($bonuses); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted">
                                    Pendientes: <strong><?php echo count(array_filter($bonuses, function($b) { return $b['status'] === 'pending'; })); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted">
                                    Aprobadas: <strong><?php echo count(array_filter($bonuses, function($b) { return $b['status'] === 'approved'; })); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted">
                                    Total monto: <strong>$<?php echo number_format(array_sum(array_column($bonuses, 'amount')), 2); ?></strong>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay bonificaciones registradas</h5>
                            <p class="text-muted">Comienza registrando la primera bonificación</p>
                            <?php if ($sessionManager->hasRole(['root', 'director', 'treasurer'])): ?>
                            <button type="button" class="btn btn-primary" onclick="safeLoadView('payroll/createBonus')">
                                <i class="fas fa-plus"></i> Registrar Primera Bonificación
                            </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal para ver descripción -->
<div class="modal fade" id="descriptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Descripción de Bonificación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="descriptionText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para ver descripción completa
function viewDescription(description) {
    document.getElementById('descriptionText').textContent = description;
    new bootstrap.Modal(document.getElementById('descriptionModal')).show();
}

// Función para aprobar bonificación
function approveBonus(bonusId) {
    if (confirm('¿Estás seguro de que deseas aprobar esta bonificación?')) {
        // Aquí se implementaría la lógica de aprobación
        alert('Función de aprobación en desarrollo');
    }
}

// Función para cancelar bonificación
function cancelBonus(bonusId) {
    if (confirm('¿Estás seguro de que deseas cancelar esta bonificación?')) {
        // Aquí se implementaría la lógica de cancelación
        alert('Función de cancelación en desarrollo');
    }
}

// Inicializar DataTable si está disponible
if (typeof $.fn.DataTable !== 'undefined') {
    $('#bonusesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        },
        order: [[4, 'desc']] // Ordenar por fecha descendente
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