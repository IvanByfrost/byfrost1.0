// Formularios de Actividades - Funcionalidades JavaScript
$(document).ready(function() {
    // Configurar eventos del formulario
    setupFormEvents();
    
    // Cargar materias iniciales si estamos en edición
    if ($('#professor_subject_id').val()) {
        loadProfessorSubjects();
    }
    
    // Establecer fecha mínima como hoy
    setMinDate();
});

// Configurar eventos del formulario
function setupFormEvents() {
    // Cargar materias del profesor cuando se seleccione un grupo
    $('#class_group_id').on('change', function() {
        const classGroupId = $(this).val();
        if (classGroupId) {
            loadProfessorSubjects();
        } else {
            // Limpiar selector de materias si no hay grupo seleccionado
            $('#professor_subject_id').empty().append('<option value="">Seleccionar materia</option>');
        }
    });
    
    // Validación en tiempo real
    setupRealTimeValidation();
    
    // Configurar formulario de creación
    if ($('#createActivityForm').length) {
        $('#createActivityForm').on('submit', function(e) {
            e.preventDefault();
            submitCreateForm($(this));
        });
    }
    
    // Configurar formulario de edición
    if ($('#editActivityForm').length) {
        $('#editActivityForm').on('submit', function(e) {
            e.preventDefault();
            submitEditForm($(this));
        });
    }
}

// Configurar validación en tiempo real
function setupRealTimeValidation() {
    const requiredFields = ['activity_name', 'activity_type_id', 'class_group_id', 
                           'term_id', 'professor_subject_id', 'max_score', 'due_date'];
    
    requiredFields.forEach(function(field) {
        $(`#${field}`).on('blur', function() {
            validateField(field);
        });
        
        $(`#${field}`).on('input', function() {
            if ($(this).hasClass('is-invalid')) {
                validateField(field);
            }
        });
    });
    
    // Validación especial para puntaje máximo
    $('#max_score').on('input', function() {
        const value = parseFloat($(this).val());
        if (value < 0) {
            $(this).addClass('is-invalid');
            showFieldError('max_score', 'El puntaje no puede ser negativo');
        } else if (value > 100) {
            $(this).addClass('is-invalid');
            showFieldError('max_score', 'El puntaje máximo es 100');
        } else {
            $(this).removeClass('is-invalid');
            hideFieldError('max_score');
        }
    });
    
    // Validación especial para fecha límite
    $('#due_date').on('change', function() {
        const selectedDate = new Date($(this).val());
        const now = new Date();
        
        if (selectedDate < now) {
            $(this).addClass('is-invalid');
            showFieldError('due_date', 'La fecha límite no puede ser anterior a hoy');
        } else {
            $(this).removeClass('is-invalid');
            hideFieldError('due_date');
        }
    });
}

// Validar campo individual
function validateField(fieldName) {
    const field = $(`#${fieldName}`);
    const value = field.val().trim();
    
    if (!value) {
        field.addClass('is-invalid');
        showFieldError(fieldName, 'Este campo es obligatorio');
        return false;
    } else {
        field.removeClass('is-invalid');
        hideFieldError(fieldName);
        return true;
    }
}

// Mostrar error de campo
function showFieldError(fieldName, message) {
    let errorElement = $(`#${fieldName}-error`);
    if (errorElement.length === 0) {
        errorElement = $(`<div id="${fieldName}-error" class="invalid-feedback"></div>`);
        $(`#${fieldName}`).after(errorElement);
    }
    errorElement.text(message);
}

// Ocultar error de campo
function hideFieldError(fieldName) {
    $(`#${fieldName}-error`).remove();
}

// Cargar materias del profesor
function loadProfessorSubjects() {
    // Mostrar loading en el selector
    const select = $('#professor_subject_id');
    select.empty().append('<option value="">Cargando materias...</option>');
    
    // En una implementación real, esto debería hacer una llamada AJAX
    // para obtener las materias del profesor logueado
    setTimeout(() => {
        const subjects = [
            { professor_subject_id: 1, subject_name: 'Matemáticas' },
            { professor_subject_id: 2, subject_name: 'Español' },
            { professor_subject_id: 3, subject_name: 'Ciencias' },
            { professor_subject_id: 4, subject_name: 'Historia' },
            { professor_subject_id: 5, subject_name: 'Geografía' },
            { professor_subject_id: 6, subject_name: 'Inglés' },
            { professor_subject_id: 7, subject_name: 'Educación Física' },
            { professor_subject_id: 8, subject_name: 'Arte' }
        ];
        
        select.empty();
        select.append('<option value="">Seleccionar materia</option>');
        
        subjects.forEach(function(subject) {
            // Si estamos en modo edición, marcar como seleccionada la materia actual
            const isSelected = subject.professor_subject_id == $('#professor_subject_id').data('current-value');
            const selectedAttr = isSelected ? 'selected' : '';
            select.append(`<option value="${subject.professor_subject_id}" ${selectedAttr}>${subject.subject_name}</option>`);
        });
    }, 500);
}

// Establecer fecha mínima como hoy
function setMinDate() {
    const today = new Date();
    const todayString = today.toISOString().slice(0, 16);
    $('#due_date').attr('min', todayString);
}

// Enviar formulario de creación
function submitCreateForm(form) {
    if (!validateForm()) {
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
                    text: response.msg,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'activity/showDashboard';
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

// Enviar formulario de edición
function submitEditForm(form) {
    if (!validateForm()) {
        return;
    }
    
    // Mostrar loading
    const submitBtn = form.find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...');
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
                    text: response.msg,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'activity/showDashboard';
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
                text: 'Ocurrió un error al actualizar la actividad'
            });
        },
        complete: function() {
            // Restaurar botón
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        }
    });
}

// Validar formulario completo
function validateForm() {
    const requiredFields = ['activity_name', 'activity_type_id', 'class_group_id', 
                           'term_id', 'professor_subject_id', 'max_score', 'due_date'];
    
    let isValid = true;
    
    // Validar cada campo requerido
    requiredFields.forEach(function(field) {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    // Validaciones adicionales
    const maxScore = parseFloat($('#max_score').val());
    if (maxScore < 0 || maxScore > 100) {
        isValid = false;
    }
    
    const dueDate = new Date($('#due_date').val());
    const now = new Date();
    if (dueDate < now) {
        isValid = false;
    }
    
    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Error de Validación',
            text: 'Por favor revisa los campos marcados en rojo'
        });
        
        // Hacer scroll al primer error
        const firstError = $('.is-invalid').first();
        if (firstError.length) {
            $('html, body').animate({
                scrollTop: firstError.offset().top - 100
            }, 500);
        }
    }
    
    return isValid;
}

// Limpiar formulario
function clearForm() {
    const form = $('#createActivityForm, #editActivityForm');
    form[0].reset();
    
    // Limpiar clases de validación
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    // Limpiar selectores
    $('#professor_subject_id').empty().append('<option value="">Seleccionar materia</option>');
}

// Confirmar salida sin guardar
function confirmExit() {
    const form = $('#createActivityForm, #editActivityForm');
    const hasChanges = form.serialize() !== form.data('original-state');
    
    if (hasChanges) {
        return Swal.fire({
            title: '¿Salir sin guardar?',
            text: "Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, salir',
            cancelButtonText: 'Cancelar'
        });
    }
    
    return Promise.resolve({ isConfirmed: true });
}

// Guardar estado original del formulario
function saveOriginalState() {
    const form = $('#createActivityForm, #editActivityForm');
    form.data('original-state', form.serialize());
}

// Configurar confirmación de salida
$(window).on('beforeunload', function() {
    const form = $('#createActivityForm, #editActivityForm');
    if (form.length && form.serialize() !== form.data('original-state')) {
        return 'Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?';
    }
});

// Guardar estado original al cargar la página
$(document).ready(function() {
    setTimeout(saveOriginalState, 1000);
}); 