/**
 * Módulo de validación para formularios de escuela
 */

// Validar campos requeridos del formulario principal
function validateRequiredFields() {
    const requiredFields = ['school_name', 'school_dane', 'school_document'];
    const errors = [];
    
    // Validar campos de texto requeridos
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && !field.value.trim()) {
            errors.push(fieldName);
            field.classList.add('is-invalid');
        } else if (field) {
            field.classList.remove('is-invalid');
        }
    });
    
    // Validar que se haya seleccionado un director
    const directorUserId = document.getElementById('director_user_id');
    const directorNameSpan = document.getElementById('selectedDirectorName');
    if (directorUserId && !directorUserId.value.trim()) {
        errors.push('director_user_id');
        if (directorNameSpan) directorNameSpan.classList.add('text-danger');
    } else {
        if (directorNameSpan) directorNameSpan.classList.remove('text-danger');
    }
    
    return errors;
}

// Validar campos del modal
function validateModalFields() {
    const requiredModalFields = ['departamento', 'municipio', 'direccion', 'telefono', 'correo'];
    const errors = [];
    
    requiredModalFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && !field.value.trim()) {
            errors.push(fieldName);
            field.classList.add('is-invalid');
        } else if (field) {
            field.classList.remove('is-invalid');
        }
    });
    
    // Validar formato de email
    const emailField = document.getElementById('correo');
    if (emailField && emailField.value.trim() && !isValidEmail(emailField.value.trim())) {
        errors.push('correo');
        emailField.classList.add('is-invalid');
    }
    
    return errors;
}

// Validar formato de email
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Mostrar errores de validación
function showValidationErrors(errors) {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            title: 'Campos Requeridos',
            text: 'Por favor complete todos los campos obligatorios.',
            icon: 'warning'
        });
    } else {
        alert('Por favor complete todos los campos obligatorios.');
    }
} 