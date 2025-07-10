/**
 * Módulo de formulario para actividades
 */

// Cargar datos para los selectores
function loadFormData() {
    // Cargar tipos de actividad
    $.get('activity/getActivityTypes', function(data) {
        const select = $('#activity_type_id');
        select.empty();
        select.append('<option value="">Seleccionar tipo</option>');
        data.forEach(function(type) {
            select.append(`<option value="${type.activity_type_id}">${type.type_name}</option>`);
        });
    }).fail(function() {
        console.error('Error al cargar tipos de actividad');
    });
    
    // Cargar grupos de clase
    $.get('activity/getClassGroups', function(data) {
        const select = $('#class_group_id');
        select.empty();
        select.append('<option value="">Seleccionar grupo</option>');
        data.forEach(function(group) {
            select.append(`<option value="${group.class_group_id}">${group.grade_name} ${group.group_name} - ${group.school_name}</option>`);
        });
    }).fail(function() {
        console.error('Error al cargar grupos de clase');
    });
    
    // Cargar períodos académicos
    $.get('activity/getAcademicTerms', function(data) {
        const select = $('#term_id');
        select.empty();
        select.append('<option value="">Seleccionar período</option>');
        data.forEach(function(term) {
            select.append(`<option value="${term.term_id}">${term.term_name} (${term.school_year})</option>`);
        });
    }).fail(function() {
        console.error('Error al cargar períodos académicos');
    });
}

// Configurar eventos del formulario
function setupFormEvents() {
    // Crear actividad
    $('#createActivityForm').on('submit', function(e) {
        e.preventDefault();
        createActivity($(this));
    });
    
    // Cargar materias cuando se seleccione un grupo
    $('#class_group_id').on('change', function() {
        const classGroupId = $(this).val();
        if (classGroupId) {
            loadProfessorSubjects();
        }
    });
}

// Crear actividad
function createActivity(form) {
    // Validar campos requeridos
    const requiredFields = ['activity_name', 'activity_type_id', 'class_group_id', 
                           'term_id', 'professor_subject_id', 'max_score', 'due_date'];
    
    let isValid = true;
    requiredFields.forEach(function(field) {
        if (!$('#' + field).val()) {
            $('#' + field).addClass('is-invalid');
            isValid = false;
        } else {
            $('#' + field).removeClass('is-invalid');
        }
    });
    
    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor completa todos los campos obligatorios'
        });
        return;
    }
    
    // Mostrar loading
    const submitBtn = form.find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creando...');
    submitBtn.prop('disabled', true);
    
    // Enviar formulario
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.msg
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.msg
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al crear la actividad'
            });
        },
        complete: function() {
            // Restaurar botón
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        }
    });
}

// Cargar materias del profesor
function loadProfessorSubjects() {
    // En una implementación real, esto debería hacer una llamada AJAX
    // para obtener las materias del profesor logueado
    const subjects = [
        { professor_subject_id: 1, subject_name: 'Matemáticas' },
        { professor_subject_id: 2, subject_name: 'Español' },
        { professor_subject_id: 3, subject_name: 'Ciencias' },
        { professor_subject_id: 4, subject_name: 'Historia' },
        { professor_subject_id: 5, subject_name: 'Geografía' }
    ];
    
    const select = $('#professor_subject_id');
    select.empty();
    select.append('<option value="">Seleccionar materia</option>');
    
    subjects.forEach(function(subject) {
        select.append(`<option value="${subject.professor_subject_id}">${subject.subject_name}</option>`);
    });
} 