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
                        <a class="nav-link active" href="index.php?controller=payroll&action=employees">
                            <i class="fas fa-users"></i> Empleados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=payroll&action=periods">
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
                <h1 class="h2">Gestión de Empleados</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="index.php?controller=payroll&action=createEmployee" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i> Nuevo Empleado
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
                        <input type="hidden" name="action" value="employees">
                        
                        <div class="col-md-4">
                            <label for="department" class="form-label">Departamento</label>
                            <select class="form-select" id="department" name="department">
                                <option value="">Todos los departamentos</option>
                                <option value="Administración" <?php echo (isset($filters['department']) && $filters['department'] === 'Administración') ? 'selected' : ''; ?>>Administración</option>
                                <option value="Académico" <?php echo (isset($filters['department']) && $filters['department'] === 'Académico') ? 'selected' : ''; ?>>Académico</option>
                                <option value="Financiero" <?php echo (isset($filters['department']) && $filters['department'] === 'Financiero') ? 'selected' : ''; ?>>Financiero</option>
                                <option value="Recursos Humanos" <?php echo (isset($filters['department']) && $filters['department'] === 'Recursos Humanos') ? 'selected' : ''; ?>>Recursos Humanos</option>
                                <option value="Tecnología" <?php echo (isset($filters['department']) && $filters['department'] === 'Tecnología') ? 'selected' : ''; ?>>Tecnología</option>
                                <option value="Mantenimiento" <?php echo (isset($filters['department']) && $filters['department'] === 'Mantenimiento') ? 'selected' : ''; ?>>Mantenimiento</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="position" class="form-label">Cargo</label>
                            <select class="form-select" id="position" name="position">
                                <option value="">Todos los cargos</option>
                                <option value="Director" <?php echo (isset($filters['position']) && $filters['position'] === 'Director') ? 'selected' : ''; ?>>Director</option>
                                <option value="Coordinador" <?php echo (isset($filters['position']) && $filters['position'] === 'Coordinador') ? 'selected' : ''; ?>>Coordinador</option>
                                <option value="Profesor" <?php echo (isset($filters['position']) && $filters['position'] === 'Profesor') ? 'selected' : ''; ?>>Profesor</option>
                                <option value="Administrativo" <?php echo (isset($filters['position']) && $filters['position'] === 'Administrativo') ? 'selected' : ''; ?>>Administrativo</option>
                                <option value="Tesorero" <?php echo (isset($filters['position']) && $filters['position'] === 'Tesorero') ? 'selected' : ''; ?>>Tesorero</option>
                                <option value="Auxiliar" <?php echo (isset($filters['position']) && $filters['position'] === 'Auxiliar') ? 'selected' : ''; ?>>Auxiliar</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <a href="index.php?controller=payroll&action=employees" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de empleados -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Empleados</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($employees)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="employeesTable">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Cargo</th>
                                        <th>Departamento</th>
                                        <th>Salario</th>
                                        <th>Contrato</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($employees as $employee): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($employee['employee_code']); ?></span>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($employee['name'] . ' ' . $employee['lastname']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($employee['email']); ?></td>
                                            <td><?php echo htmlspecialchars($employee['position']); ?></td>
                                            <td>
                                                <span class="badge bg-info"><?php echo htmlspecialchars($employee['department']); ?></span>
                                            </td>
                                            <td>
                                                <strong>$<?php echo number_format($employee['salary'], 2); ?></strong>
                                            </td>
                                            <td>
                                                <?php 
                                                $contractLabels = [
                                                    'full_time' => 'Tiempo Completo',
                                                    'part_time' => 'Medio Tiempo',
                                                    'temporary' => 'Temporal',
                                                    'contractor' => 'Contratista'
                                                ];
                                                echo $contractLabels[$employee['contract_type']] ?? $employee['contract_type'];
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Activo</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="index.php?controller=payroll&action=editEmployee&id=<?php echo $employee['employee_id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="index.php?controller=payroll&action=viewEmployee&id=<?php echo $employee['employee_id']; ?>" 
                                                       class="btn btn-sm btn-outline-info" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmDeactivate(<?php echo $employee['employee_id']; ?>, '<?php echo htmlspecialchars($employee['name'] . ' ' . $employee['lastname']); ?>')"
                                                            title="Desactivar">
                                                        <i class="fas fa-user-times"></i>
                                                    </button>
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
                                    Mostrando <?php echo count($employees); ?> empleado(s)
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
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No se encontraron empleados</h5>
                            <p class="text-muted">No hay empleados que coincidan con los filtros aplicados.</p>
                            <a href="index.php?controller=payroll&action=createEmployee" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primer Empleado
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal de confirmación para desactivar -->
<div class="modal fade" id="deactivateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Desactivación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea desactivar al empleado <strong id="employeeName"></strong>?</p>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeactivate">Desactivar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para confirmar desactivación
function confirmDeactivate(employeeId, employeeName) {
    document.getElementById('employeeName').textContent = employeeName;
    document.getElementById('confirmDeactivate').onclick = function() {
        // Aquí se haría la llamada AJAX para desactivar
        window.location.href = `index.php?controller=payroll&action=deactivateEmployee&id=${employeeId}`;
    };
    new bootstrap.Modal(document.getElementById('deactivateModal')).show();
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
    $('#employeesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        pageLength: 25,
        order: [[1, 'asc']]
    });
});
</script>

<?php
// Incluir el footer del dashboard
include 'app/views/layouts/dashFooter.php';
?> 