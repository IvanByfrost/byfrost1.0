<?php
// Verificar que se pasen los datos necesarios
if (!isset($employee) || empty($employee)) {
    echo '<div class="alert alert-danger">Empleado no encontrado</div>';
    return;
}

// Verificar permisos
if (!$sessionManager->hasRole(['root', 'director', 'coordinator', 'treasurer'])) {
    echo '<div class="alert alert-danger">No tienes permisos para ver esta información</div>';
    return;
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Contenido principal -->
        <main class="col-12 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-3 mb-4 border-bottom">
                <div>
                    <h1 class="h2 mb-0">Detalles del Empleado</h1>
                    <p class="text-muted mb-0">Información completa del empleado</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <?php if ($sessionManager->hasRole(['root', 'director'])): ?>
                        <button type="button" class="btn btn-primary" onclick="safeLoadView('payroll/editEmployee?id=<?php echo $employee['employee_id']; ?>')">
                            <i class="fas fa-edit"></i> Editar Empleado
                        </button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-outline-secondary" onclick="safeLoadView('payroll/employees')">
                            <i class="fas fa-arrow-left"></i> Volver a Empleados
                        </button>
                    </div>
                </div>
            </div>

            <!-- Información del empleado -->
            <div class="row">
                <!-- Información personal -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-user"></i> Información Personal
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Código:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-primary"><?php echo htmlspecialchars($employee['employee_code']); ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Nombre:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Email:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <a href="mailto:<?php echo htmlspecialchars($employee['email']); ?>">
                                        <?php echo htmlspecialchars($employee['email']); ?>
                                    </a>
                                </div>
                            </div>
                            <?php if (!empty($employee['phone'])): ?>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Teléfono:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <a href="tel:<?php echo htmlspecialchars($employee['phone']); ?>">
                                        <?php echo htmlspecialchars($employee['phone']); ?>
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Información laboral -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-briefcase"></i> Información Laboral
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Cargo:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <?php echo htmlspecialchars($employee['position']); ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Departamento:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-info"><?php echo htmlspecialchars($employee['department']); ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Salario:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <strong class="text-success">$<?php echo number_format($employee['salary'], 2); ?></strong>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Tipo de Contrato:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <?php 
                                    $contractLabels = [
                                        'full_time' => 'Tiempo Completo',
                                        'part_time' => 'Medio Tiempo',
                                        'temporary' => 'Temporal',
                                        'contractor' => 'Contratista'
                                    ];
                                    echo $contractLabels[$employee['contract_type']] ?? $employee['contract_type'];
                                    ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Fecha de Contratación:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <?php echo date('d/m/Y', strtotime($employee['hire_date'])); ?>
                                </div>
                            </div>
                            <?php if (!empty($employee['work_schedule'])): ?>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Horario:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <?php echo htmlspecialchars($employee['work_schedule']); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información bancaria -->
            <?php if (!empty($employee['bank_account']) || !empty($employee['bank_name'])): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-university"></i> Información Bancaria
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (!empty($employee['bank_name'])): ?>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Banco:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <?php echo htmlspecialchars($employee['bank_name']); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($employee['bank_account'])): ?>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Cuenta:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <code><?php echo htmlspecialchars($employee['bank_account']); ?></code>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Historial de nómina -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-file-invoice-dollar"></i> Historial de Nómina
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (isset($payrollHistory) && !empty($payrollHistory)): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Período</th>
                                                <th>Salario Base</th>
                                                <th>Ingresos</th>
                                                <th>Deducciones</th>
                                                <th>Neto</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($payrollHistory as $record): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($record['period_name']); ?></td>
                                                    <td>$<?php echo number_format($record['base_salary'], 2); ?></td>
                                                    <td>$<?php echo number_format($record['total_income'], 2); ?></td>
                                                    <td>$<?php echo number_format($record['total_deductions'], 2); ?></td>
                                                    <td><strong>$<?php echo number_format($record['net_salary'], 2); ?></strong></td>
                                                    <td>
                                                        <span class="badge bg-success">Procesado</span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                                onclick="safeLoadView('payroll/viewRecord?id=<?php echo $record['record_id']; ?>')"
                                                                title="Ver Detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">No hay registros de nómina</h6>
                                    <p class="text-muted">Este empleado aún no tiene registros de nómina procesados</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Salario Mensual
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        $<?php echo number_format($employee['salary'], 2); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Años de Servicio
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php 
                                        $hireDate = new DateTime($employee['hire_date']);
                                        $now = new DateTime();
                                        $years = $now->diff($hireDate)->y;
                                        echo $years;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Registros de Nómina
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo isset($payrollHistory) ? count($payrollHistory) : 0; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Estado
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Activo
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div> 