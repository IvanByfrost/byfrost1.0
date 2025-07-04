<?php
// Incluir el header del dashboard
include 'app/views/layouts/dashHead.php';
include 'app/views/layouts/dashHeader.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=payroll&action=dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=payroll&action=employees">
                            <i class="fas fa-users"></i> Empleados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?controller=payroll&action=periods">
                            <i class="fas fa-calendar-alt"></i> Períodos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=payroll&action=absences">
                            <i class="fas fa-user-times"></i> Ausencias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=payroll&action=overtime">
                            <i class="fas fa-clock"></i> Horas Extras
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=payroll&action=bonuses">
                            <i class="fas fa-gift"></i> Bonificaciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=payroll&action=reports">
                            <i class="fas fa-chart-bar"></i> Reportes
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Períodos de Nómina</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="index.php?controller=payroll&action=createPeriod" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i> Nuevo Período
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mensajes de éxito/error -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> Operación realizada con éxito.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="index.php" class="row g-3">
                        <input type="hidden" name="controller" value="payroll">
                        <input type="hidden" name="action" value="periods">
                        
                        <div class="col-md-4">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Todos los estados</option>
                                <option value="open" <?php echo (isset($filters['status']) && $filters['status'] === 'open') ? 'selected' : ''; ?>>Abierto</option>
                                <option value="processing" <?php echo (isset($filters['status']) && $filters['status'] === 'processing') ? 'selected' : ''; ?>>En Proceso</option>
                                <option value="closed" <?php echo (isset($filters['status']) && $filters['status'] === 'closed') ? 'selected' : ''; ?>>Cerrado</option>
                                <option value="paid" <?php echo (isset($filters['status']) && $filters['status'] === 'paid') ? 'selected' : ''; ?>>Pagado</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <a href="index.php?controller=payroll&action=periods" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de períodos -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Períodos</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($periods)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="periodsTable">
                                <thead>
                                    <tr>
                                        <th>Período</th>
                                        <th>Fechas</th>
                                        <th>Pago</th>
                                        <th>Estado</th>
                                        <th>Empleados</th>
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
                                            <td>
                                                <small>
                                                    <strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($period['start_date'])); ?><br>
                                                    <strong>Fin:</strong> <?php echo date('d/m/Y', strtotime($period['end_date'])); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php echo date('d/m/Y', strtotime($period['payment_date'])); ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $statusColors = [
                                                    'open' => 'success',
                                                    'processing' => 'warning',
                                                    'closed' => 'info',
                                                    'paid' => 'primary'
                                                ];
                                                $statusLabels = [
                                                    'open' => 'Abierto',
                                                    'processing' => 'En Proceso',
                                                    'closed' => 'Cerrado',
                                                    'paid' => 'Pagado'
                                                ];
                                                ?>
                                                <span class="badge bg-<?php echo $statusColors[$period['status']] ?? 'secondary'; ?>">
                                                    <?php echo $statusLabels[$period['status']] ?? ucfirst($period['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo $period['total_employees']; ?></span>
                                            </td>
                                            <td>
                                                <strong>$<?php echo number_format($period['total_payroll'], 2); ?></strong>
                                            </td>
                                            <td>
                                                <small><?php echo htmlspecialchars($period['created_by_name']); ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="index.php?controller=payroll&action=viewPeriod&id=<?php echo $period['period_id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <?php if ($period['status'] === 'open'): ?>
                                                        <a href="index.php?controller=payroll&action=generatePayroll&period_id=<?php echo $period['period_id']; ?>" 
                                                           class="btn btn-sm btn-outline-success" title="Generar Nómina"
                                                           onclick="return confirm('¿Está seguro de generar la nómina para este período?')">
                                                            <i class="fas fa-calculator"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($period['status'] === 'processing'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                                onclick="closePeriod(<?php echo $period['period_id']; ?>)"
                                                                title="Cerrar Período">
                                                            <i class="fas fa-lock"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($period['status'] === 'closed'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                                onclick="markAsPaid(<?php echo $period['period_id']; ?>)"
                                                                title="Marcar como Pagado">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Estadísticas de la tabla -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Mostrando <?php echo count($periods); ?> período(s)
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-outline-success btn-sm" onclick="exportToExcel()">
                                    <i class="fas fa-file-excel"></i> Exportar a Excel
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="exportToPDF()">
                                    <i class="fas fa-file-pdf"></i> Exportar a PDF
                                </button>
                            </div>
                        </div>
                        
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No se encontraron períodos</h5>
                            <p class="text-muted">No hay períodos de nómina registrados.</p>
                            <a href="index.php?controller=payroll&action=createPeriod" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primer Período
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal de confirmación para cerrar período -->
<div class="modal fade" id="closePeriodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Cierre de Período</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea cerrar este período de nómina?</p>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Una vez cerrado, no se podrán hacer modificaciones.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" id="confirmClosePeriod">Cerrar Período</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para marcar como pagado -->
<div class="modal fade" id="markAsPaidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea marcar este período como pagado?</p>
                <p class="text-success">
                    <i class="fas fa-check-circle"></i> 
                    Esto confirmará que todos los pagos han sido realizados.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmMarkAsPaid">Marcar como Pagado</button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para cerrar período
function closePeriod(periodId) {
    document.getElementById('confirmClosePeriod').onclick = function() {
        window.location.href = `index.php?controller=payroll&action=closePeriod&id=${periodId}`;
    };
    new bootstrap.Modal(document.getElementById('closePeriodModal')).show();
}

// Función para marcar como pagado
function markAsPaid(periodId) {
    document.getElementById('confirmMarkAsPaid').onclick = function() {
        window.location.href = `index.php?controller=payroll&action=markAsPaid&id=${periodId}`;
    };
    new bootstrap.Modal(document.getElementById('markAsPaidModal')).show();
}

// Función para exportar a Excel
function exportToExcel() {
    // Implementar exportación a Excel
    alert('Función de exportación a Excel en desarrollo');
}

// Función para exportar a PDF
function exportToPDF() {
    // Implementar exportación a PDF
    alert('Función de exportación a PDF en desarrollo');
}

// Inicializar DataTable
$(document).ready(function() {
    $('#periodsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 25,
        order: [[0, 'desc']]
    });
});
</script>

<?php
// Incluir el footer del dashboard
include 'app/views/layouts/dashFooter.php';
?> 