/**
 * JavaScript para la gestión de roles y permisos
 */

// Función para inicializar el formulario de edición de roles
function initRoleEditForm() {
    console.log('Buscando formulario editRoleForm...');
    const form = document.getElementById('editRoleForm');
    if (form) {
        console.log('Formulario encontrado, agregando event listener...');
        form.addEventListener('submit', function(e) {
            console.log('Formulario enviado, previniendo envío por defecto...');
            e.preventDefault();
            submitRoleForm(this);
        });
    } else {
        console.log('Formulario editRoleForm no encontrado');
    }
}

// Función para enviar el formulario de roles vía AJAX
function submitRoleForm(form) {
    console.log('Iniciando envío del formulario...');
    const formData = new FormData(form);
    
    // Log de los datos del formulario
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
    
    // Mostrar indicador de carga
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    submitBtn.disabled = true;
    
    fetch(BASE_URL + '?view=role&action=update', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Respuesta del servidor recibida:', response.status, response.statusText);
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.text();
    })
    .then(data => {
        console.log('Datos de respuesta:', data);
        // Si la respuesta contiene JavaScript, ejecutarlo
        if (data.includes('<script>')) {
            console.log('Ejecutando JavaScript de respuesta...');
            const scriptMatch = data.match(/<script>([\s\S]*?)<\/script>/);
            if (scriptMatch) {
                eval(scriptMatch[1]);
            }
        } else {
            // Si no hay JavaScript, mostrar mensaje de éxito
            console.log('Mostrando mensaje de éxito...');
            showSuccessMessage('Permisos guardados correctamente');
            loadView('role/index');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error al guardar los permisos');
    })
    .finally(() => {
        // Restaurar botón
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Función para mostrar mensaje de éxito
function showSuccessMessage(message) {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            title: '¡Éxito!',
            text: message,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
}

// Función para mostrar mensaje de error
function showErrorMessage(message) {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            timer: 4000
        });
    } else {
        alert(message);
    }
}

// Función para confirmar cambios antes de guardar
function confirmRoleChanges() {
    if (typeof Swal !== "undefined") {
        return Swal.fire({
            title: '¿Guardar cambios?',
            text: '¿Estás seguro de que quieres guardar los cambios en los permisos?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        });
    } else {
        return Promise.resolve({ isConfirmed: confirm('¿Estás seguro de que quieres guardar los cambios?') });
    }
}

// Función para validar el formulario antes de enviar
function validateRoleForm(form) {
    const roleType = form.querySelector('input[name="role_type"]').value;
    
    if (!roleType) {
        showErrorMessage('Debe seleccionar un rol');
        return false;
    }
    
    // Verificar que al menos un permiso esté seleccionado
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    let hasPermission = false;
    
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            hasPermission = true;
        }
    });
    
    if (!hasPermission) {
        showErrorMessage('Debe seleccionar al menos un permiso');
        return false;
    }
    
    return true;
}

// Inicializar cuando el DOM esté listo (solo para páginas que no usan loadView)
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, verificando si necesitamos inicializar formulario de roles...');
    // Solo inicializar si estamos en una página que no usa loadView
    if (!window.location.search.includes('view=')) {
        initRoleEditForm();
    }
});

// Función para exportar (si se necesita en otros archivos)
window.roleManagement = {
    initRoleEditForm,
    submitRoleForm,
    showSuccessMessage,
    showErrorMessage,
    confirmRoleChanges,
    validateRoleForm
}; 