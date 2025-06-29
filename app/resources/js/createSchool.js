/**
 * JavaScript para manejar el formulario de crear escuela dentro del dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createSchool');
    const completeForm = document.getElementById('completeSchoolForm');
    
    if (form) {
        // Remover el event listener del formulario principal ya que ahora se maneja desde el modal
        form.addEventListener('submit', function(e) {
            e.preventDefault();
        });
    }
    
    // Validar formulario principal antes de abrir el modal usando el evento de Bootstrap
    const completeSchoolModal = document.getElementById('completeSchoolModal');
    if (completeSchoolModal) {
        completeSchoolModal.addEventListener('show.bs.modal', function(e) {
            // Validar que el formulario principal esté completo
            const mainFormErrors = validateRequiredFields();
            if (mainFormErrors.length > 0) {
                e.preventDefault(); // Prevenir que se abra el modal
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: 'Campos Requeridos',
                        text: 'Por favor complete todos los campos obligatorios del formulario principal antes de continuar.',
                        icon: 'warning'
                    });
                } else {
                    alert('Por favor complete todos los campos obligatorios del formulario principal antes de continuar.');
                }
                return false;
            }
        });
    }
    
    // Manejar el envío del formulario completo desde el modal
    if (completeForm) {
        completeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Solo validar campos del modal ya que el formulario principal ya se validó
            const modalFormErrors = validateModalFields();
            if (modalFormErrors.length > 0) {
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: 'Campos Requeridos',
                        text: 'Por favor complete todos los campos de ubicación.',
                        icon: 'warning'
                    });
                } else {
                    alert('Por favor complete todos los campos de ubicación.');
                }
                return;
            }
            
            // Mostrar indicador de carga
            const submitBtn = completeForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
            submitBtn.disabled = true;
            
            // Recopilar datos de ambos formularios
            const mainFormData = new FormData(form);
            const modalFormData = new FormData(completeForm);
            
            // Combinar los datos
            const combinedFormData = new FormData();
            
            // Agregar datos del formulario principal
            for (let [key, value] of mainFormData.entries()) {
                combinedFormData.append(key, value);
            }
            
            // Agregar datos del modal
            for (let [key, value] of modalFormData.entries()) {
                combinedFormData.append(key, value);
            }
            
            // Enviar datos via AJAX
            fetch(`${url}app/scripts/routerView.php?view=school&action=createSchool`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: combinedFormData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.text();
            })
            .then(data => {
                // Verificar si la respuesta es JSON (éxito) o HTML (error)
                try {
                    const jsonResponse = JSON.parse(data);
                    
                    if (jsonResponse.success) {
                        // Éxito - mostrar mensaje y redirigir
                        if (typeof Swal !== "undefined") {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: jsonResponse.message || 'Escuela creada exitosamente',
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                // Cerrar modal
                                const modal = bootstrap.Modal.getInstance(document.getElementById('completeSchoolModal'));
                                if (modal) {
                                    modal.hide();
                                }
                                // Cargar la vista de consulta de escuelas
                                loadView('school/consultSchool');
                            });
                        } else {
                            alert('Escuela creada exitosamente');
                            // Cerrar modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('completeSchoolModal'));
                            if (modal) {
                                modal.hide();
                            }
                            loadView('school/consultSchool');
                        }
                    } else {
                        // Error - mostrar mensaje
                        if (typeof Swal !== "undefined") {
                            Swal.fire({
                                title: 'Error',
                                text: jsonResponse.message || 'Error al crear la escuela',
                                icon: 'error'
                            });
                        } else {
                            alert(jsonResponse.message || 'Error al crear la escuela');
                        }
                    }
                } catch (e) {
                    // Si no es JSON, es HTML con errores de validación
                    // Actualizar el contenido del mainContent con el HTML de error
                    const mainContent = document.getElementById('mainContent');
                    if (mainContent) {
                        mainContent.innerHTML = data;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error de conexión. Intente nuevamente.',
                        icon: 'error'
                    });
                } else {
                    alert('Error de conexión. Intente nuevamente.');
                }
            })
            .finally(() => {
                // Restaurar botón
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Función para validar campos requeridos del formulario principal
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
        const directorName = document.getElementById('director_name');
        if (directorUserId && (!directorUserId.value.trim() || !directorName.value.trim())) {
            errors.push('director_user_id');
            directorName.classList.add('is-invalid');
        } else if (directorName) {
            directorName.classList.remove('is-invalid');
        }
        
        return errors;
    }
    
    // Función para validar campos del modal
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
    
    // Validación en tiempo real del formulario principal
    const requiredInputs = document.querySelectorAll('#createSchool input[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Validación en tiempo real para el campo del director
    const directorName = document.getElementById('director_name');
    if (directorName) {
        directorName.addEventListener('blur', function() {
            const directorUserId = document.getElementById('director_user_id');
            if (!this.value.trim() || !directorUserId.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
    
    // Validación en tiempo real del modal
    const requiredModalInputs = document.querySelectorAll('#completeSchoolForm input[required]');
    requiredModalInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Validación de email del modal
    const modalEmailInput = document.getElementById('correo');
    if (modalEmailInput) {
        modalEmailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && !isValidEmail(email)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Manejar búsqueda de directores
    const searchDirectorForm = document.getElementById('searchDirectorForm');
    if (searchDirectorForm) {
        searchDirectorForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = document.getElementById('search_director_query').value.trim();
            
            if (!query) {
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor ingrese un término de búsqueda',
                        icon: 'warning'
                    });
                } else {
                    alert('Por favor ingrese un término de búsqueda');
                }
                return;
            }
            
            // Buscar directores
            fetch(`${url}app/scripts/routerView.php?view=user&action=searchUsers&role=director&query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('searchDirectorResults');
                if (data.success && data.users && data.users.length > 0) {
                    let html = '<div class="list-group">';
                    data.users.forEach(user => {
                        html += `<button type="button" class="list-group-item list-group-item-action" 
                                   onclick="selectDirector('${user.user_id}', '${user.first_name} ${user.last_name}')">
                                   ${user.first_name} ${user.last_name} - ${user.credential_number}
                               </button>`;
                    });
                    html += '</div>';
                    resultsDiv.innerHTML = html;
                } else {
                    resultsDiv.innerHTML = '<div class="alert alert-info">No se encontraron directores con ese criterio.</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('searchDirectorResults').innerHTML = 
                    '<div class="alert alert-danger">Error al buscar directores.</div>';
            });
        });
    }
    
    // Manejar búsqueda de coordinadores
    const searchCoordinatorForm = document.getElementById('searchCoordinatorForm');
    if (searchCoordinatorForm) {
        searchCoordinatorForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = document.getElementById('search_coordinator_query').value.trim();
            
            if (!query) {
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor ingrese un término de búsqueda',
                        icon: 'warning'
                    });
                } else {
                    alert('Por favor ingrese un término de búsqueda');
                }
                return;
            }
            
            // Buscar coordinadores
            fetch(`${url}app/scripts/routerView.php?view=user&action=searchUsers&role=coordinator&query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('searchCoordinatorResults');
                if (data.success && data.users && data.users.length > 0) {
                    let html = '<div class="list-group">';
                    data.users.forEach(user => {
                        html += `<button type="button" class="list-group-item list-group-item-action" 
                                   onclick="selectCoordinator('${user.user_id}', '${user.first_name} ${user.last_name}')">
                                   ${user.first_name} ${user.last_name} - ${user.credential_number}
                               </button>`;
                    });
                    html += '</div>';
                    resultsDiv.innerHTML = html;
                } else {
                    resultsDiv.innerHTML = '<div class="alert alert-info">No se encontraron coordinadores con ese criterio.</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('searchCoordinatorResults').innerHTML = 
                    '<div class="alert alert-danger">Error al buscar coordinadores.</div>';
            });
        });
    }
});

// Función para seleccionar director
function selectDirector(id, name) {
    document.getElementById('director_user_id').value = id;
    document.getElementById('director_name').value = name;
    document.getElementById('director_name').classList.remove('is-invalid');
    
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('searchDirectorModal'));
    if (modal) {
        modal.hide();
    }
}

// Función para seleccionar coordinador
function selectCoordinator(id, name) {
    document.getElementById('coordinator_user_id').value = id;
    document.getElementById('coordinator_name').value = name;
    
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('searchCoordinatorModal'));
    if (modal) {
        modal.hide();
    }
}

// Función para limpiar el formulario
function clearCreateSchoolForm() {
    const form = document.getElementById('createSchool');
    const completeForm = document.getElementById('completeSchoolForm');
    
    if (form) {
        form.reset();
        
        // Limpiar clases de validación
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
        
        // Limpiar mensajes de error
        const alerts = form.querySelectorAll('.alert');
        alerts.forEach(alert => alert.remove());
    }
    
    if (completeForm) {
        completeForm.reset();
        
        // Limpiar clases de validación del modal
        const modalInputs = completeForm.querySelectorAll('input');
        modalInputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
    }
}

// Función para cancelar y volver a la consulta
function cancelCreateSchool() {
    loadView('school/consultSchool');
} 