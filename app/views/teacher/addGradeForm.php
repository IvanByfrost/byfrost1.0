<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-plus"></i> Agregar Nueva Calificación</h3>
    </div>
    <div class="card-body">
        <form id="addGradeForm">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="student_id">Estudiante *</label>
                        <select class="form-control" id="student_id" name="student_id" required>
                            <option value="">Seleccione un estudiante</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= $student['student_id'] ?>"><?= htmlspecialchars($student['student_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="subject_id">Materia *</label>
                        <select class="form-control" id="subject_id" name="subject_id" required>
                            <option value="">Seleccione una materia</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['subject_id'] ?>"><?= htmlspecialchars($subject['subject_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="activity_name">Actividad *</label>
                        <input type="text" class="form-control" id="activity_name" name="activity_name" 
                               placeholder="Ej: Examen Parcial, Tarea, Proyecto" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="score">Calificación *</label>
                        <input type="number" class="form-control" id="score" name="score" 
                               min="0" max="10" step="0.1" placeholder="0.0 - 10.0" required>
                        <small class="form-text text-muted">Ingrese una calificación entre 0 y 10</small>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="score_date">Fecha de Calificación</label>
                        <input type="date" class="form-control" id="score_date" name="score_date" 
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Calificación
                </button>
                <button type="button" class="btn btn-secondary" onclick="loadView('teacher/listGrades')">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
            </div>
            
            <div id="message" class="mt-3"></div>
        </form>
    </div>
</div>

<script>
document.getElementById('addGradeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('subject', 'addGrade');
    
    // Validaciones del lado del cliente
    const score = parseFloat(document.getElementById('score').value);
    if (score < 0 || score > 10) {
        showMessage('La calificación debe estar entre 0 y 10', 'danger');
        return;
    }
    
    if (!document.getElementById('student_id').value) {
        showMessage('Debe seleccionar un estudiante', 'danger');
        return;
    }
    
    if (!document.getElementById('subject_id').value) {
        showMessage('Debe seleccionar una materia', 'danger');
        return;
    }
    
    if (!document.getElementById('activity_name').value.trim()) {
        showMessage('Debe ingresar el nombre de la actividad', 'danger');
        return;
    }
    
    // Enviar formulario
    fetch('?view=teacher&action=addGrade', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            // Limpiar formulario
            document.getElementById('addGradeForm').reset();
            document.getElementById('score_date').value = new Date().toISOString().split('T')[0];
        } else {
            showMessage(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error al guardar la calificación', 'danger');
    });
});

function showMessage(message, type) {
    const messageDiv = document.getElementById('message');
    messageDiv.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    
    // Auto-ocultar después de 5 segundos
    setTimeout(() => {
        messageDiv.innerHTML = '';
    }, 5000);
}

// Validación en tiempo real para la calificación
document.getElementById('score').addEventListener('input', function() {
    const score = parseFloat(this.value);
    if (score < 0 || score > 10) {
        this.setCustomValidity('La calificación debe estar entre 0 y 10');
    } else {
        this.setCustomValidity('');
    }
});
</script> 