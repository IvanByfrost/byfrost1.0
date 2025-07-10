/**
 * Módulo de manejo de formularios de escuela
 */

// Inicializar formulario de crear escuela
window.initCreateSchoolForm = function() {
    const form = document.getElementById('createSchool');
    const completeForm = document.getElementById('completeSchoolForm');
    const completeSchoolModal = document.getElementById('completeSchoolModal');
    
    if (form) {
        // Remover el event listener del formulario principal ya que ahora se maneja desde el modal
        form.addEventListener('submit', function(e) {
            e.preventDefault();
        });
    }
    
    // Validar formulario principal antes de abrir el modal usando el evento de Bootstrap
    if (completeSchoolModal) {
        completeSchoolModal.addEventListener('show.bs.modal', function(e) {
            // Validar que el formulario principal esté completo
            const mainFormErrors = validateRequiredFields();
            if (mainFormErrors.length > 0) {
                e.preventDefault(); // Prevenir que se abra el modal
                showValidationErrors(mainFormErrors);
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
                showValidationErrors(modalFormErrors);
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
};

// Seleccionar director
function selectDirector(id, name) {
    document.getElementById('director_user_id').value = id;
    document.getElementById('selectedDirectorName').textContent = name;
    document.getElementById('selectedDirectorName').classList.remove('text-danger');
}

// Seleccionar coordinador
function selectCoordinator(id, name) {
    document.getElementById('coordinator_user_id').value = id;
    document.getElementById('selectedCoordinatorName').textContent = name;
    document.getElementById('selectedCoordinatorName').classList.remove('text-danger');
}

// Limpiar formulario de crear escuela
function clearCreateSchoolForm() {
    const form = document.getElementById('createSchool');
    if (form) {
        form.reset();
    }
    
    // Limpiar campos de selección
    const directorUserId = document.getElementById('director_user_id');
    const directorNameSpan = document.getElementById('selectedDirectorName');
    const coordinatorUserId = document.getElementById('coordinator_user_id');
    const coordinatorNameSpan = document.getElementById('selectedCoordinatorName');
    
    if (directorUserId) directorUserId.value = '';
    if (directorNameSpan) directorNameSpan.textContent = '';
    if (coordinatorUserId) coordinatorUserId.value = '';
    if (coordinatorNameSpan) coordinatorNameSpan.textContent = '';
    
    // Remover clases de error
    document.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
    });
}

// Cancelar creación de escuela
function cancelCreateSchool() {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            title: '¿Cancelar?',
            text: '¿Estás seguro de que deseas cancelar la creación de la escuela?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, continuar'
        }).then((result) => {
            if (result.isConfirmed) {
                clearCreateSchoolForm();
                loadView('school/consultSchool');
            }
        });
    } else {
        if (confirm('¿Estás seguro de que deseas cancelar la creación de la escuela?')) {
            clearCreateSchoolForm();
            loadView('school/consultSchool');
        }
    }
} 