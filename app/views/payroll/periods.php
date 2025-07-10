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
console.log("BASE_URL será configurada en dashFooter.php");

// Función de respaldo para loadView
window.safeLoadView = function(viewName) {
    console.log('safeLoadView llamado desde períodos con:', viewName);
    
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
                    <h1 class="h2 mb-0">Gestión de Períodos de Nómina</h1>
                    <p class="text-muted mb-0">Administra los períodos de pago de nómina</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <?php if ($sessionManager->hasRole(['root', 'director', 'treasurer'])): ?>
                        <button type="button" class="btn btn-primary" onclick="safeLoadView('payroll/createPeriod')">
                            <i class="fas fa-plus"></i> Nuevo Período
                        </button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-outline-secondary" onclick="safeLoadView('payroll/dashboard')">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </button>
                    </div>
                </div>
            </div>

            <!-- Período actual -->
            <?php if (isset($current_period) && $current_period): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-check fa-2x me-3"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Período Actual: <?php echo htmlspecialchars($current_period['period_name']); ?></h6>
                        <p class="mb-0">
                            <strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($current_period['start_date'])); ?> | 
                            <strong>Fin:</strong> <?php echo date('d/m/Y', strtotime($current_period['end_date'])); ?> | 
                            <strong>Pago:</strong> <?php echo date('d/m/Y', strtotime($current_period['payment_date'])); ?>
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="#" class="row g-3">
                        <div class="col-md-3">
                            <label for="year" class="form-label">Año</label>
                            <select class="form-select" id="year" name="year">
                                <option value="">Todos los años</option>
                                <?php 
                                $currentYear = date('Y');
                                for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
                                    echo "<option value='$year'>$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Todos los estados</option>
                                <option value="open">Abierto</option>
                                <option value="closed">Cerrado</option>
                                <option value="processing">En Proceso</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="month" class="form-label">Mes</label>
                            <select class="form-select" id="month" name="month">
                                <option value="">Todos los meses</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 d-flex align-items-end">
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

            <!-- Lista de períodos -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Períodos de Nómina</h6>
                </div>
                <div class="card-body">
                    <?php if (isset($periods) && !empty($periods)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="periodsTable">
                                <thead>
                                    <tr>
                                        <th>Período</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Fecha Pago</th>
                                        <th>Estado</th>
                                        <th>Total Empleados</th>
                                        <th>Total Nómina</th>
                                        <th>Creado por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($periods as $period): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($period['period_name']); ?></strong>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($period['start_date'])); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($period['end_date'])); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($period['payment_date'])); ?></td>
                                            <td>
                                                <?php 
                                                $statusColors = [
                                                    'open' => 'success',
                                                    'closed' => 'secondary',
                                                    'processing' => 'warning'
                                                ];
                                                $statusLabels = [
                                                    'open' => 'Abierto',
                                                    'closed' => 'Cerrado',
                                                    'processing' => 'En Proceso'
                                                ];
                                                $color = $statusColors[$period['status']] ?? 'secondary';
                                                $label = $statusLabels[$period['status']] ?? ucfirst($period['status']);
                                                ?>
                                                <span class="badge bg-<?php echo $color; ?>"><?php echo $label; ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo $period['total_employees'] ?? 0; ?></span>
                                            </td>
                                            <td>
                                                <strong>$<?php echo number_format($period['total_payroll'] ?? 0, 2); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($period['created_by_name'] ?? 'Sistema'); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="safeLoadView('payroll/viewPeriod?id=<?php echo $period['period_id']; ?>')"
                                                            title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if ($sessionManager->hasRole(['root', 'director', 'treasurer'])): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            onclick="safeLoadView('payroll/editPeriod?id=<?php echo $period['period_id']; ?>')"
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                    <?php if ($period['status'] === 'open' && $sessionManager->hasRole(['root', 'director', 'treasurer'])): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                                            onclick="generatePayroll(<?php echo $period['period_id']; ?>)"
                                                            title="Generar Nómina">
                                                        <i class="fas fa-calculator"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                                            onclick="closePeriod(<?php echo $period['period_id']; ?>)"
                                                            title="Cerrar Período">
                                                        <i class="fas fa-lock"></i>
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
                            <div class="col-md-4">
                                <p class="text-muted">
                                    Total períodos: <strong><?php echo count($periods); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted">
                                    Períodos abiertos: <strong><?php echo count(array_filter($periods, function($p) { return $p['status'] === 'open'; })); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted">
                                    Total nómina: <strong>$<?php echo number_format(array_sum(array_column($periods, 'total_payroll')), 2); ?></strong>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay períodos registrados</h5>
                            <p class="text-muted">Comienza creando el primer período de nómina</p>
                            <?php if ($sessionManager->hasRole(['root', 'director', 'treasurer'])): ?>
                            <button type="button" class="btn btn-primary" onclick="safeLoadView('payroll/createPeriod')">
                                <i class="fas fa-plus"></i> Crear Primer Período
                            </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?>

<script>
// Función para generar nómina
function generatePayroll(periodId) {
    if (confirm('¿Estás seguro de que deseas generar la nómina para este período?')) {
        // Aquí se implementaría la lógica de generación de nómina
        alert('Función de generación de nómina en desarrollo');
    }
}

// Función para cerrar período
function closePeriod(periodId) {
    if (confirm('¿Estás seguro de que deseas cerrar este período? Esta acción no se puede deshacer.')) {
        // Aquí se implementaría la lógica de cierre de período
        alert('Función de cierre de período en desarrollo');
    }
}

// Inicializar DataTable si está disponible
if (typeof $.fn.DataTable !== 'undefined') {
    $('#periodsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        },
        order: [[1, 'desc']] // Ordenar por fecha de inicio descendente
    });
}
</script> 