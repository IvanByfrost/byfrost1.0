<?php
// Verificar sesión y permisos
if (!isset($sessionManager)) {
    require_once ROOT . '/app/library/SessionManager.php';
    $sessionManager = new SessionManager();
}

// Verificar permisos
if (!$sessionManager->hasRole(['root', 'director', 'treasurer'])) {
    header('Location: ?view=unauthorized');
    exit;
}

// Incluir layout modular
include 'app/views/layouts/formView.php';
?>

<!-- Contenido del formulario -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Crear Nuevo Período de Nómina</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadView('payroll/periods')">
                        <i class="fas fa-arrow-left"></i> Volver a Períodos
                    </button>
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
                    <h6 class="m-0 font-weight-bold text-primary">Información del Período</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?controller=payroll&action=createPeriod" id="createPeriodForm">
                        <div class="row">
                            <!-- Información básica -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Información Básica</h5>
                                
                                <div class="mb-3">
                                    <label for="period_name" class="form-label">Nombre del Período *</label>
                                    <input type="text" class="form-control" id="period_name" name="period_name" 
                                           value="<?php echo isset($data['period_name']) ? htmlspecialchars($data['period_name']) : ''; ?>" 
                                           required placeholder="Ej: Enero 2024">
                                    <div class="form-text">Nombre descriptivo del período (ej: Enero 2024, Febrero 2024)</div>
                                </div>

                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Fecha de Inicio *</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="<?php echo isset($data['start_date']) ? $data['start_date'] : ''; ?>" 
                                           required>
                                    <div class="form-text">Primer día del período de nómina</div>
                                </div>

                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Fecha de Fin *</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="<?php echo isset($data['end_date']) ? $data['end_date'] : ''; ?>" 
                                           required>
                                    <div class="form-text">Último día del período de nómina</div>
                                </div>
                            </div>

                            <!-- Información de pago -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Información de Pago</h5>
                                
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Fecha de Pago *</label>
                                    <input type="date" class="form-control" id="payment_date" name="payment_date" 
                                           value="<?php echo isset($data['payment_date']) ? $data['payment_date'] : ''; ?>" 
                                           required>
                                    <div class="form-text">Fecha en la que se realizará el pago</div>
                                </div>

                                <div class="mb-3">
                                    <label for="period_type" class="form-label">Tipo de Período</label>
                                    <select class="form-select" id="period_type" name="period_type">
                                        <option value="monthly" <?php echo (isset($data['period_type']) && $data['period_type'] === 'monthly') ? 'selected' : ''; ?>>Mensual</option>
                                        <option value="biweekly" <?php echo (isset($data['period_type']) && $data['period_type'] === 'biweekly') ? 'selected' : ''; ?>>Quincenal</option>
                                        <option value="weekly" <?php echo (isset($data['period_type']) && $data['period_type'] === 'weekly') ? 'selected' : ''; ?>>Semanal</option>
                                    </select>
                                    <div class="form-text">Tipo de período para cálculos automáticos</div>
                                </div>

                                <div class="mb-3">
                                    <label for="working_days" class="form-label">Días Laborales</label>
                                    <input type="number" class="form-control" id="working_days" name="working_days" 
                                           value="<?php echo isset($data['working_days']) ? $data['working_days'] : '30'; ?>" 
                                           min="1" max="31">
                                    <div class="form-text">Número de días laborales en el período</div>
                                </div>
                            </div>
                        </div>

                        <!-- Configuraciones adicionales -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">Configuraciones Adicionales</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="include_overtime" name="include_overtime" 
                                               <?php echo (isset($data['include_overtime']) && $data['include_overtime']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="include_overtime">
                                            Incluir horas extras
                                        </label>
                                    </div>
                                    <div class="form-text">Incluir automáticamente las horas extras registradas</div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="include_bonuses" name="include_bonuses" 
                                               <?php echo (isset($data['include_bonuses']) && $data['include_bonuses']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="include_bonuses">
                                            Incluir bonificaciones
                                        </label>
                                    </div>
                                    <div class="form-text">Incluir automáticamente las bonificaciones registradas</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="include_absences" name="include_absences" 
                                               <?php echo (isset($data['include_absences']) && $data['include_absences']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="include_absences">
                                            Considerar ausencias
                                        </label>
                                    </div>
                                    <div class="form-text">Descontar automáticamente las ausencias no pagadas</div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="auto_generate" name="auto_generate" 
                                               <?php echo (isset($data['auto_generate']) && $data['auto_generate']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="auto_generate">
                                            Generar nómina automáticamente
                                        </label>
                                    </div>
                                    <div class="form-text">Generar registros de nómina para todos los empleados activos</div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen del período -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Resumen del Período</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <small class="text-muted">Duración:</small><br>
                                                <strong id="duration">-</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Días laborales:</small><br>
                                                <strong id="workingDaysDisplay">-</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Días hasta pago:</small><br>
                                                <strong id="daysToPayment">-</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Estado:</small><br>
                                                <span class="badge bg-success">Abierto</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" onclick="loadView('payroll/periods')">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary me-2" onclick="saveDraft()">
                                            <i class="fas fa-save"></i> Guardar Borrador
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-check"></i> Crear Período
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
document.getElementById('createPeriodForm').addEventListener('submit', function(e) {
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(document.getElementById('end_date').value);
    const paymentDate = new Date(document.getElementById('payment_date').value);
    
    // Validar que la fecha de fin sea posterior a la de inicio
    if (endDate <= startDate) {
        e.preventDefault();
        alert('La fecha de fin debe ser posterior a la fecha de inicio');
        document.getElementById('end_date').focus();
        return false;
    }
    
    // Validar que la fecha de pago sea posterior a la fecha de fin
    if (paymentDate <= endDate) {
        e.preventDefault();
        alert('La fecha de pago debe ser posterior a la fecha de fin del período');
        document.getElementById('payment_date').focus();
        return false;
    }
});

// Función para guardar borrador
function saveDraft() {
    // Implementar guardado de borrador
    alert('Función de guardado de borrador en desarrollo');
}

// Calcular y mostrar resumen del período
function updateSummary() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const paymentDate = document.getElementById('payment_date').value;
    const workingDays = document.getElementById('working_days').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const daysDiff = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
        
        document.getElementById('duration').textContent = daysDiff + ' días';
    }
    
    if (workingDays) {
        document.getElementById('workingDaysDisplay').textContent = workingDays + ' días';
    }
    
    if (endDate && paymentDate) {
        const end = new Date(endDate);
        const payment = new Date(paymentDate);
        const daysToPayment = Math.ceil((payment - end) / (1000 * 60 * 60 * 24));
        
        document.getElementById('daysToPayment').textContent = daysToPayment + ' días';
    }
}

// Event listeners para actualizar resumen
document.getElementById('start_date').addEventListener('change', updateSummary);
document.getElementById('end_date').addEventListener('change', updateSummary);
document.getElementById('payment_date').addEventListener('change', updateSummary);
document.getElementById('working_days').addEventListener('input', updateSummary);

// Generar nombre automático del período
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = new Date(this.value);
    const monthNames = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    
    const monthName = monthNames[startDate.getMonth()];
    const year = startDate.getFullYear();
    
    document.getElementById('period_name').value = monthName + ' ' + year;
});

// Inicializar resumen al cargar la página
document.addEventListener('DOMContentLoaded', updateSummary);
</script>

<!-- Fin del contenido del formulario --> 