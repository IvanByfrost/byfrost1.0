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
    console.log('safeLoadView llamado desde empleados con:', viewName);
    
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
                    <h1 class="h2 mb-0">Gestión de Empleados</h1>
                    <p class="text-muted mb-0">Administra la información de los empleados del sistema</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <?php if ($sessionManager->hasRole(['root', 'director'])): ?>
                        <button type="button" class="btn btn-primary" onclick="safeLoadView('payroll/createEmployee')">
                            <i class="fas fa-plus"></i> Nuevo Empleado
                        </button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-outline-secondary" onclick="safeLoadView('payroll/dashboard')">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </button>
                    </div>
                </div>
            </div>

            <!-- Información sobre restricciones de empleados -->
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle"></i> 
                <strong>Información importante:</strong> Solo los usuarios con roles de <strong>Profesor</strong>, <strong>Coordinador</strong>, <strong>Director</strong>, <strong>Tesorero</strong> o <strong>Administrador</strong> pueden ser empleados. Los estudiantes y padres no pueden ser empleados del sistema.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="#" class="row g-3">
                        <div class="col-md-4">
                            <label for="department" class="form-label">Departamento</label>
                            <select class="form-select" id="department" name="department">
                                <option value="">Todos los departamentos</option>
                                <option value="Administración">Administración</option>
                                <option value="Académico">Académico</option>
                                <option value="Financiero">Financiero</option>
                                <option value="Recursos Humanos">Recursos Humanos</option>
                                <option value="Tecnología">Tecnología</option>
                                <option value="Mantenimiento">Mantenimiento</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="position" class="form-label">Cargo</label>
                            <select class="form-select" id="position" name="position">
                                <option value="">Todos los cargos</option>
                                <option value="Director">Director</option>
                                <option value="Coordinador">Coordinador</option>
                                <option value="Profesor">Profesor</option>
                                <option value="Administrativo">Administrativo</option>
                                <option value="Tesorero">Tesorero</option>
                                <option value="Auxiliar">Auxiliar</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 d-flex align-items-end">
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

            <!-- Lista de empleados -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Empleados</h6>
                </div>
                <div class="card-body">
                    <?php if (isset($employees) && !empty($employees)): ?>
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
                                                <strong><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></strong>
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
                                                    <?php if ($sessionManager->hasRole(['root', 'director'])): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            onclick="safeLoadView('payroll/editEmployee?id=<?php echo $employee['employee_id']; ?>')"
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="safeLoadView('payroll/viewEmployee?id=<?php echo $employee['employee_id']; ?>')"
                                                            title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if ($sessionManager->hasRole(['root', 'director'])): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmDeactivate(<?php echo $employee['employee_id']; ?>, '<?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?>')"
                                                            title="Desactivar">
                                                        <i class="fas fa-user-times"></i>
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
                                    Mostrando <strong><?php echo count($employees); ?></strong> empleados
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="text-muted">
                                    Total de salarios: <strong>$<?php echo number_format(array_sum(array_column($employees, 'salary')), 2); ?></strong>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay empleados registrados</h5>
                            <p class="text-muted">Comienza agregando el primer empleado al sistema</p>
                            <?php if ($sessionManager->hasRole(['root', 'director'])): ?>
                            <button type="button" class="btn btn-primary" onclick="safeLoadView('payroll/createEmployee')">
                                <i class="fas fa-plus"></i> Agregar Primer Empleado
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
 