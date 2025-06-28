/**
 * JavaScript para manejar el formulario de crear escuela dentro del dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createSchool');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Mostrar indicador de carga
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
            submitBtn.disabled = true;
            
            // Recopilar datos del formulario
            const formData = new FormData(form);
            
            // Enviar datos via AJAX
            fetch(`${BASE_URL}app/scripts/routerView.php?view=school&action=createSchool`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
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
                                // Cargar la vista de consulta de escuelas
                                loadView('school/consultSchool');
                            });
                        } else {
                            alert('Escuela creada exitosamente');
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
    
    // Función para validar campos requeridos
    function validateRequiredFields() {
        const requiredFields = ['school_name', 'school_dane', 'school_document'];
        const errors = [];
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field && !field.value.trim()) {
                errors.push(fieldName);
                field.classList.add('is-invalid');
            } else if (field) {
                field.classList.remove('is-invalid');
            }
        });
        
        return errors;
    }
    
    // Validación en tiempo real
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
    
    // Validación de email
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
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
});

// Función para limpiar el formulario
function clearCreateSchoolForm() {
    const form = document.getElementById('createSchool');
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
}

// Función para cancelar y volver a la consulta
function cancelCreateSchool() {
    loadView('school/consultSchool');
} 