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
                        <a class="nav-link active" href="index.php?controller=payroll&action=dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=payroll&action=employees">
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
                <h1 class="h2">Dashboard de Nómina</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="index.php?controller=payroll&action=createPeriod" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i> Nuevo Período
                        </a>
                        <a href="index.php?controller=payroll&action=createEmployee" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-user-plus"></i> Nuevo Empleado
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de estadísticas -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Empleados
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_employees; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Período Actual
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $current_period ? $current_period['period_name'] : 'No hay período activo'; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Períodos Cerrados
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($recent_periods); ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Acciones Pendientes
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="row">
                <!-- Período actual -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Período Actual</h6>
                        </div>
                        <div class="card-body">
                            <?php if ($current_period): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Período:</strong> <?php echo htmlspecialchars($current_period['period_name']); ?></p>
                                        <p><strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($current_period['start_date'])); ?></p>
                                        <p><strong>Fin:</strong> <?php echo date('d/m/Y', strtotime($current_period['end_date'])); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Pago:</strong> <?php echo date('d/m/Y', strtotime($current_period['payment_date'])); ?></p>
                                        <p><strong>Estado:</strong> 
                                            <span class="badge bg-<?php echo $current_period['status'] === 'open' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($current_period['status']); ?>
                                            </span>
                                        </p>
                                        <p><strong>Creado por:</strong> <?php echo htmlspecialchars($current_period['created_by_name']); ?></p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="index.php?controller=payroll&action=viewPeriod&id=<?php echo $current_period['period_id']; ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Ver Detalles
                                    </a>
                                    <a href="index.php?controller=payroll&action=generatePayroll&period_id=<?php echo $current_period['period_id']; ?>" 
                                       class="btn btn-success btn-sm">
                                        <i class="fas fa-calculator"></i> Generar Nómina
                                    </a>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No hay período activo actualmente.</p>
                                <a href="index.php?controller=payroll&action=createPeriod" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Crear Nuevo Período
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Períodos recientes -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Períodos Recientes</h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recent_periods)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Período</th>
                                                <th>Estado</th>
                                                <th>Total</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_periods as $period): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($period['period_name']); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $period['status'] === 'closed' ? 'success' : 'secondary'; ?>">
                                                            <?php echo ucfirst($period['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>$<?php echo number_format($period['total_payroll'], 2); ?></td>
                                                    <td>
                                                        <a href="index.php?controller=payroll&action=viewPeriod&id=<?php echo $period['period_id']; ?>" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2">
                                    <a href="index.php?controller=payroll&action=periods" class="btn btn-outline-primary btn-sm">
                                        Ver Todos los Períodos
                                    </a>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No hay períodos recientes.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="index.php?controller=payroll&action=createEmployee" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                                        Nuevo Empleado
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="index.php?controller=payroll&action=createPeriod" class="btn btn-outline-success w-100">
                                        <i class="fas fa-calendar-plus fa-2x mb-2"></i><br>
                                        Nuevo Período
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="index.php?controller=payroll&action=createAbsence" class="btn btn-outline-warning w-100">
                                        <i class="fas fa-user-times fa-2x mb-2"></i><br>
                                        Registrar Ausencia
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="index.php?controller=payroll&action=reports" class="btn btn-outline-info w-100">
                                        <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                                        Generar Reporte
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
// Incluir el footer del dashboard
include 'app/views/layouts/dashFooter.php';
?> 