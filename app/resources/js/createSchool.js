/**
 * JavaScript para el formulario de creación de escuela
 * Maneja validaciones y envío del formulario
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createSchool');
    
    if (!form) {
        console.error('Formulario createSchool no encontrado');
        return;
    }
    
    // Validación del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            submitForm();
        }
    });
    
    // Validar formato de email
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            validateEmail(this.value);
        });
    }
    
    // Validar código DANE (solo números)
    const daneInput = document.getElementById('school_dane');
    if (daneInput) {
        daneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
    // Validar NIT (números y guión)
    const nitInput = document.getElementById('school_document');
    if (nitInput) {
        nitInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9-]/g, '');
        });
    }
    
    // Validar teléfono (números, paréntesis, espacios y guiones)
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9()\s-]/g, '');
        });
    }
    
    // Validar cupo total (solo números positivos)
    const quotaInput = document.getElementById('total_quota');
    if (quotaInput) {
        quotaInput.addEventListener('input', function() {
            if (this.value < 0) {
                this.value = 0;
            }
        });
    }
});

/**
 * Valida el formulario completo
 */
function validateForm() {
    const schoolName = document.getElementById('school_name').value.trim();
    const schoolDane = document.getElementById('school_dane').value.trim();
    const schoolDocument = document.getElementById('school_document').value.trim();
    const email = document.getElementById('email').value.trim();
    
    // Validar campos obligatorios
    if (!schoolName) {
        showError('El nombre de la escuela es obligatorio');
        document.getElementById('school_name').focus();
        return false;
    }
    
    if (!schoolDane) {
        showError('El código DANE es obligatorio');
        document.getElementById('school_dane').focus();
        return false;
    }
    
    if (schoolDane.length < 10) {
        showError('El código DANE debe tener al menos 10 dígitos');
        document.getElementById('school_dane').focus();
        return false;
    }
    
    if (!schoolDocument) {
        showError('El NIT es obligatorio');
        document.getElementById('school_document').focus();
        return false;
    }
    
    if (schoolDocument.length < 8) {
        showError('El NIT debe tener al menos 8 caracteres');
        document.getElementById('school_document').focus();
        return false;
    }
    
    // Validar formato de email si se proporciona
    if (email && !isValidEmail(email)) {
        showError('Por favor ingrese un formato de email válido');
        document.getElementById('email').focus();
        return false;
    }
    
    // Validar que el cupo total sea un número válido
    const quota = document.getElementById('total_quota').value;
    if (quota && (isNaN(quota) || quota < 0)) {
        showError('El cupo total debe ser un número positivo');
        document.getElementById('total_quota').focus();
        return false;
    }
    
    return true;
}

/**
 * Valida el formato de email
 */
function validateEmail(email) {
    if (email && !isValidEmail(email)) {
        showFieldError('email', 'Formato de email inválido');
        return false;
    } else {
        clearFieldError('email');
        return true;
    }
}

/**
 * Verifica si el email tiene formato válido
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Envía el formulario
 */
function submitForm() {
    const form = document.getElementById('createSchool');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Deshabilitar botón y mostrar loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
    
    // Obtener datos del formulario
    const formData = new FormData(form);
    
    // Enviar petición AJAX
    fetch('?view=school&action=createSchool', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showSuccess(data.msg);
            // Redirigir después de 2 segundos
            setTimeout(() => {
                window.location.href = '?view=school&action=consultSchool';
            }, 2000);
        } else {
            showError(data.msg);
            // Habilitar botón
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Error de conexión. Intente nuevamente.');
        // Habilitar botón
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

/**
 * Muestra mensaje de error
 */
function showError(message) {
    // Remover mensajes existentes
    clearMessages();
    
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

/**
 * Muestra mensaje de éxito
 */
function showSuccess(message) {
    // Remover mensajes existentes
    clearMessages();
    
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-check-circle"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
}

/**
 * Muestra error en un campo específico
 */
function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.add('is-invalid');
        
        // Remover mensaje de error existente
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
        
        // Agregar mensaje de error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
}

/**
 * Limpia error de un campo específico
 */
function clearFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
}

/**
 * Limpia todos los mensajes
 */
function clearMessages() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => alert.remove());
}

/**
 * Formatea el NIT automáticamente
 */
function formatNIT(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length > 0) {
        if (value.length <= 9) {
            value = value.replace(/(\d{1,3})(\d{1,3})(\d{1,3})/, '$1-$2-$3');
        } else {
            value = value.replace(/(\d{1,3})(\d{1,3})(\d{1,3})(\d{1,3})/, '$1-$2-$3-$4');
        }
    }
    
    input.value = value;
}

/**
 * Formatea el teléfono automáticamente
 */
function formatPhone(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length > 0) {
        if (value.length <= 3) {
            value = `(${value}`;
        } else if (value.length <= 6) {
            value = `(${value.slice(0, 3)}) ${value.slice(3)}`;
        } else {
            value = `(${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6, 10)}`;
        }
    }
    
    input.value = value;
} 