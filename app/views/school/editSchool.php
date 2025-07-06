<?php
// Verificar si hay mensajes o errores
$error = $error ?? '';
$formData = $formData ?? [];
$directors = $directors ?? [];
$coordinators = $coordinators ?? [];
$school = $school ?? [];

// Si no hay datos del formulario, usar los datos de la escuela
if (empty($formData) && !empty($school)) {
    $formData = $school;
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Editar Escuela</h2>
                    <p>Modifique la información de la escuela.</p>
                </div>
                <button type="button" class="btn btn-secondary" onclick="loadView('school/consultSchool')">
                    <i class="fas fa-arrow-left"></i> Volver a la Lista
                </button>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="editSchoolForm" class="dash-form">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-edit"></i> Información de la Escuela
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="school_name">Nombre de la Escuela *</label>
                                    <input type="text" class="form-control" id="school_name" name="school_name" 
                                           value="<?php echo htmlspecialchars($formData['school_name'] ?? ''); ?>" 
                                           required>
                                    <div class="invalid-feedback">
                                        El nombre de la escuela es obligatorio.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="school_dane">Código DANE *</label>
                                    <input type="text" class="form-control" id="school_dane" name="school_dane" 
                                           value="<?php echo htmlspecialchars($formData['school_dane'] ?? ''); ?>" 
                                           required maxlength="10">
                                    <div class="invalid-feedback">
                                        El código DANE es obligatorio.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="school_document">NIT *</label>
                                    <input type="text" class="form-control" id="school_document" name="school_document" 
                                           value="<?php echo htmlspecialchars($formData['school_document'] ?? ''); ?>" 
                                           required maxlength="10">
                                    <div class="invalid-feedback">
                                        El NIT es obligatorio.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="total_quota">Cupo Total</label>
                                    <input type="number" class="form-control" id="total_quota" name="total_quota" 
                                           title="Only Numbers" onkeyup="onlyNumbers('total_quota',value);" autocomplete="off"
                                           value="<?php echo htmlspecialchars($formData['total_quota'] ?? ''); ?>" 
                                           min="0">
                                    <div class="invalid-feedback">
                                        El cupo debe ser un número válido.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="address">Dirección</label>
                                    <input type="text" class="form-control" id="address" name="address" 
                                           value="<?php echo htmlspecialchars($formData['address'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="phone">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                                    <div class="invalid-feedback">
                                        El formato del email no es válido.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label><strong>Director *</strong></label>
                                    <select class="form-control" id="director_user_id" name="director_user_id" required>
                                        <option value="">Seleccionar Director</option>
                                        <?php foreach ($directors as $director): ?>
                                            <option value="<?php echo $director['user_id']; ?>" 
                                                    <?php echo ($formData['director_user_id'] ?? '') == $director['user_id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($director['first_name'] . ' ' . $director['last_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Debe seleccionar un director.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>Coordinador</label>
                                    <select class="form-control" id="coordinator_user_id" name="coordinator_user_id">
                                        <option value="">Seleccionar Coordinador (Opcional)</option>
                                        <?php foreach ($coordinators as $coordinator): ?>
                                            <option value="<?php echo $coordinator['user_id']; ?>" 
                                                    <?php echo ($formData['coordinator_user_id'] ?? '') == $coordinator['user_id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($coordinator['first_name'] . ' ' . $coordinator['last_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="loadView('school/consultSchool')">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario
    const form = document.getElementById('editSchoolForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar campos obligatorios
        const requiredFields = ['school_name', 'school_dane', 'school_document', 'director_user_id'];
        let isValid = true;
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        // Validar email si está presente
        const email = document.getElementById('email');
        if (email.value && !isValidEmail(email.value)) {
            email.classList.add('is-invalid');
            isValid = false;
        } else {
            email.classList.remove('is-invalid');
        }
        
        if (isValid) {
            // Enviar formulario
            form.submit();
        }
    });
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});
</script>
