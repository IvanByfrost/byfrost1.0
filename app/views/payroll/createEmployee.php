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
                <h1 class="h2">Crear Nuevo Empleado</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="index.php?controller=payroll&action=employees" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a Empleados
                    </a>
                </div>
            </div>

            <!-- Mensajes de error -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Empleado</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?controller=payroll&action=createEmployee" id="createEmployeeForm">
                        <div class="row">
                            <!-- Información básica -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Información Básica</h5>
                                
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Usuario *</label>
                                    <select class="form-select" id="user_id" name="user_id" required>
                                        <option value="">Seleccionar usuario</option>
                                        <!-- Aquí se cargarían los usuarios disponibles -->
                                        <option value="1" <?php echo (isset($data['user_id']) && $data['user_id'] == 1) ? 'selected' : ''; ?>>Juan Pérez</option>
                                        <option value="2" <?php echo (isset($data['user_id']) && $data['user_id'] == 2) ? 'selected' : ''; ?>>María García</option>
                                        <option value="3" <?php echo (isset($data['user_id']) && $data['user_id'] == 3) ? 'selected' : ''; ?>>Carlos López</option>
                                    </select>
                                    <div class="form-text">Seleccione el usuario que será empleado</div>
                                </div>

                                <div class="mb-3">
                                    <label for="employee_code" class="form-label">Código de Empleado *</label>
                                    <input type="text" class="form-control" id="employee_code" name="employee_code" 
                                           value="<?php echo isset($data['employee_code']) ? htmlspecialchars($data['employee_code']) : ''; ?>" 
                                           required maxlength="20">
                                    <div class="form-text">Código único del empleado (ej: EMP001)</div>
                                </div>

                                <div class="mb-3">
                                    <label for="position" class="form-label">Cargo *</label>
                                    <select class="form-select" id="position" name="position" required>
                                        <option value="">Seleccionar cargo</option>
                                        <option value="Director" <?php echo (isset($data['position']) && $data['position'] === 'Director') ? 'selected' : ''; ?>>Director</option>
                                        <option value="Coordinador" <?php echo (isset($data['position']) && $data['position'] === 'Coordinador') ? 'selected' : ''; ?>>Coordinador</option>
                                        <option value="Profesor" <?php echo (isset($data['position']) && $data['position'] === 'Profesor') ? 'selected' : ''; ?>>Profesor</option>
                                        <option value="Administrativo" <?php echo (isset($data['position']) && $data['position'] === 'Administrativo') ? 'selected' : ''; ?>>Administrativo</option>
                                        <option value="Tesorero" <?php echo (isset($data['position']) && $data['position'] === 'Tesorero') ? 'selected' : ''; ?>>Tesorero</option>
                                        <option value="Auxiliar" <?php echo (isset($data['position']) && $data['position'] === 'Auxiliar') ? 'selected' : ''; ?>>Auxiliar</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="department" class="form-label">Departamento *</label>
                                    <select class="form-select" id="department" name="department" required>
                                        <option value="">Seleccionar departamento</option>
                                        <option value="Administración" <?php echo (isset($data['department']) && $data['department'] === 'Administración') ? 'selected' : ''; ?>>Administración</option>
                                        <option value="Académico" <?php echo (isset($data['department']) && $data['department'] === 'Académico') ? 'selected' : ''; ?>>Académico</option>
                                        <option value="Financiero" <?php echo (isset($data['department']) && $data['department'] === 'Financiero') ? 'selected' : ''; ?>>Financiero</option>
                                        <option value="Recursos Humanos" <?php echo (isset($data['department']) && $data['department'] === 'Recursos Humanos') ? 'selected' : ''; ?>>Recursos Humanos</option>
                                        <option value="Tecnología" <?php echo (isset($data['department']) && $data['department'] === 'Tecnología') ? 'selected' : ''; ?>>Tecnología</option>
                                        <option value="Mantenimiento" <?php echo (isset($data['department']) && $data['department'] === 'Mantenimiento') ? 'selected' : ''; ?>>Mantenimiento</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Información laboral -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Información Laboral</h5>
                                
                                <div class="mb-3">
                                    <label for="hire_date" class="form-label">Fecha de Contratación *</label>
                                    <input type="date" class="form-control" id="hire_date" name="hire_date" 
                                           value="<?php echo isset($data['hire_date']) ? $data['hire_date'] : date('Y-m-d'); ?>" 
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label for="salary" class="form-label">Salario Base Mensual *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="salary" name="salary" 
                                               value="<?php echo isset($data['salary']) ? $data['salary'] : ''; ?>" 
                                               required min="0" step="0.01">
                                    </div>
                                    <div class="form-text">Salario base sin deducciones</div>
                                </div>

                                <div class="mb-3">
                                    <label for="contract_type" class="form-label">Tipo de Contrato *</label>
                                    <select class="form-select" id="contract_type" name="contract_type" required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="full_time" <?php echo (isset($data['contract_type']) && $data['contract_type'] === 'full_time') ? 'selected' : ''; ?>>Tiempo Completo</option>
                                        <option value="part_time" <?php echo (isset($data['contract_type']) && $data['contract_type'] === 'part_time') ? 'selected' : ''; ?>>Medio Tiempo</option>
                                        <option value="temporary" <?php echo (isset($data['contract_type']) && $data['contract_type'] === 'temporary') ? 'selected' : ''; ?>>Temporal</option>
                                        <option value="contractor" <?php echo (isset($data['contract_type']) && $data['contract_type'] === 'contractor') ? 'selected' : ''; ?>>Contratista</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="work_schedule" class="form-label">Horario de Trabajo</label>
                                    <input type="text" class="form-control" id="work_schedule" name="work_schedule" 
                                           value="<?php echo isset($data['work_schedule']) ? htmlspecialchars($data['work_schedule']) : ''; ?>" 
                                           placeholder="Ej: Lunes a Viernes 8:00 AM - 5:00 PM">
                                </div>
                            </div>
                        </div>

                        <!-- Información bancaria -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">Información Bancaria</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bank_name" class="form-label">Banco</label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" 
                                           value="<?php echo isset($data['bank_name']) ? htmlspecialchars($data['bank_name']) : ''; ?>" 
                                           placeholder="Nombre del banco">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bank_account" class="form-label">Número de Cuenta</label>
                                    <input type="text" class="form-control" id="bank_account" name="bank_account" 
                                           value="<?php echo isset($data['bank_account']) ? htmlspecialchars($data['bank_account']) : ''; ?>" 
                                           placeholder="Número de cuenta bancaria">
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="index.php?controller=payroll&action=employees" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary me-2" onclick="saveDraft()">
                                            <i class="fas fa-save"></i> Guardar Borrador
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-check"></i> Crear Empleado
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Validación del formulario
document.getElementById('createEmployeeForm').addEventListener('submit', function(e) {
    const employeeCode = document.getElementById('employee_code').value;
    const salary = document.getElementById('salary').value;
    
    // Validar código de empleado
    if (!/^[A-Z]{3}\d{3}$/.test(employeeCode)) {
        e.preventDefault();
        alert('El código de empleado debe tener el formato EMP001 (3 letras + 3 números)');
        document.getElementById('employee_code').focus();
        return false;
    }
    
    // Validar salario
    if (salary <= 0) {
        e.preventDefault();
        alert('El salario debe ser mayor a 0');
        document.getElementById('salary').focus();
        return false;
    }
});

// Función para guardar borrador
function saveDraft() {
    // Implementar guardado de borrador
    alert('Función de guardado de borrador en desarrollo');
}

// Generar código de empleado automáticamente
document.getElementById('user_id').addEventListener('change', function() {
    const userId = this.value;
    if (userId) {
        // Aquí se podría generar un código automático basado en el usuario seleccionado
        const employeeCode = 'EMP' + userId.padStart(3, '0');
        document.getElementById('employee_code').value = employeeCode;
    }
});

// Calcular años de servicio
document.getElementById('hire_date').addEventListener('change', function() {
    const hireDate = new Date(this.value);
    const today = new Date();
    const years = today.getFullYear() - hireDate.getFullYear();
    
    if (years > 0) {
        // Mostrar años de servicio en algún lugar si es necesario
        console.log('Años de servicio:', years);
    }
});
</script>

<?php
// Incluir el footer del dashboard
include 'app/views/layouts/dashFooter.php';
?> 