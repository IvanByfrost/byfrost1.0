window.initCreateSchoolForm = function () {
    console.log('=== DEBUG: Inicializando módulo completo de creación de escuela ===');

    // ----------------------------
    // BUSQUEDA DE DIRECTOR
    // ----------------------------

    const directorForm = document.getElementById('searchDirectorForm');
    if (directorForm) {
        console.log('DEBUG: Formulario de director encontrado');
        
        directorForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const queryInput = document.getElementById('search_director_query');
            const query = queryInput.value.trim();
            const resultsDiv = document.getElementById('searchDirectorResults');

            if (!query) {
                resultsDiv.innerHTML = '<div class="alert alert-warning">Por favor, ingrese un número de documento para buscar.</div>';
                queryInput.focus();
                return;
            }

            if (!/^\d+$/.test(query)) {
                resultsDiv.innerHTML = '<div class="alert alert-warning">Por favor, ingrese solo números para el documento.</div>';
                queryInput.focus();
                return;
            }

            resultsDiv.innerHTML = '<div class="alert alert-info">Buscando...</div>';

            fetch('app/processes/assignProcess.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'subject=search_users_by_role&role_type=director&search_type=document&query=' + encodeURIComponent(query)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta de búsqueda de director:', data);
                
                if (data.status === 'ok' && data.data?.length > 0) {
                    resultsDiv.innerHTML = data.data.map(director =>
                        `<button type="button" class="list-group-item list-group-item-action" 
                            onclick="selectDirector('${director.user_id}', '${director.first_name} ${director.last_name}')">
                            ${director.first_name} ${director.last_name} - ${director.email}
                        </button>`
                    ).join('');
                } else if (data.status === 'error') {
                    resultsDiv.innerHTML = `<div class="alert alert-danger">${data.msg || 'Error al buscar directores.'}</div>`;
                } else {
                    resultsDiv.innerHTML = '<div class="alert alert-warning">No se encontraron directores con ese documento.</div>';
                }
            })
            .catch(error => {
                console.error('Error en búsqueda de director:', error);
                resultsDiv.innerHTML = '<div class="alert alert-danger">Error de conexión al buscar directores. Intente nuevamente.</div>';
            });
        });

        const directorInput = document.getElementById('search_director_query');
        if (directorInput) {
            directorInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    directorForm.dispatchEvent(new Event('submit'));
                }
            });
        }
    }

    // ----------------------------
    // BUSQUEDA DE COORDINADOR
    // ----------------------------

    const coordinatorForm = document.getElementById('searchCoordinatorForm');
    if (coordinatorForm) {
        console.log('DEBUG: Formulario de coordinador encontrado');
        
        coordinatorForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const queryInput = document.getElementById('search_coordinator_query');
            const query = queryInput.value.trim();
            const resultsDiv = document.getElementById('searchCoordinatorResults');

            if (!query) {
                resultsDiv.innerHTML = '<div class="alert alert-warning">Por favor, ingrese un número de documento para buscar.</div>';
                queryInput.focus();
                return;
            }

            if (!/^\d+$/.test(query)) {
                resultsDiv.innerHTML = '<div class="alert alert-warning">Por favor, ingrese solo números para el documento.</div>';
                queryInput.focus();
                return;
            }

            resultsDiv.innerHTML = '<div class="alert alert-info">Buscando...</div>';

            fetch('app/processes/assignProcess.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'subject=search_users_by_role&role_type=coordinator&search_type=document&query=' + encodeURIComponent(query)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta de búsqueda de coordinador:', data);
                
                if (data.status === 'ok' && data.data?.length > 0) {
                    resultsDiv.innerHTML = data.data.map(coordinator =>
                        `<button type="button" class="list-group-item list-group-item-action" 
                            onclick="selectCoordinator('${coordinator.user_id}', '${coordinator.first_name} ${coordinator.last_name}')">
                            ${coordinator.first_name} ${coordinator.last_name} - ${coordinator.email}
                        </button>`
                    ).join('');
                } else if (data.status === 'error') {
                    resultsDiv.innerHTML = `<div class="alert alert-danger">${data.msg || 'Error al buscar coordinadores.'}</div>`;
                } else {
                    resultsDiv.innerHTML = '<div class="alert alert-warning">No se encontraron coordinadores con ese documento.</div>';
                }
            })
            .catch(error => {
                console.error('Error en búsqueda de coordinador:', error);
                resultsDiv.innerHTML = '<div class="alert alert-danger">Error de conexión al buscar coordinadores. Intente nuevamente.</div>';
            });
        });

        const coordinatorInput = document.getElementById('search_coordinator_query');
        if (coordinatorInput) {
            coordinatorInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    coordinatorForm.dispatchEvent(new Event('submit'));
                }
            });
        }
    }

    // ----------------------------
    // SELECCIONAR DIRECTOR
    // ----------------------------

    window.selectDirector = function (userId, name) {
        document.getElementById('director_user_id').value = userId;
        document.getElementById('selectedDirectorName').textContent = name;
        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('searchDirectorModal'));
        modal.hide();
    };

    // ----------------------------
    // SELECCIONAR COORDINADOR
    // ----------------------------

    window.selectCoordinator = function (userId, name) {
        document.getElementById('coordinator_user_id').value = userId;
        document.getElementById('selectedCoordinatorName').textContent = name;
        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('searchCoordinatorModal'));
        modal.hide();
    };

    // ----------------------------
    // LIMPIAR FORMULARIO
    // ----------------------------

    window.clearCreateSchoolForm = function () {
        const form = document.getElementById('createSchool');
        if (form) form.reset();

        document.getElementById('director_user_id').value = '';
        document.getElementById('selectedDirectorName').textContent = '';
        document.getElementById('coordinator_user_id').value = '';
        document.getElementById('selectedCoordinatorName').textContent = '';

        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
    };

    // ----------------------------
    // CANCELAR FORMULARIO
    // ----------------------------

    window.cancelCreateSchool = function () {
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
    };

    console.log('Módulo completo de creación de escuela inicializado');
// ----------------------------
// FORMULARIO DE CREAR ESCUELA (SUBMIT FINAL)
// ----------------------------

const form = document.getElementById('createSchool');
const completeForm = document.getElementById('completeSchoolForm');
const completeSchoolModal = document.getElementById('completeSchoolModal');

if (form && completeForm && completeSchoolModal) {
    // Evitar submit directo del form principal
    form.addEventListener('submit', e => e.preventDefault());

    // Validar antes de abrir el modal
    completeSchoolModal.addEventListener('show.bs.modal', function(e) {
        const mainFormErrors = validateRequiredFields?.() || [];
        if (mainFormErrors.length > 0) {
            e.preventDefault();
            showValidationErrors(mainFormErrors);
            return false;
        }
    });

    completeForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const modalFormErrors = validateModalFields?.() || [];
        if (modalFormErrors.length > 0) {
            showValidationErrors(modalFormErrors);
            return;
        }

        const submitBtn = completeForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
        submitBtn.disabled = true;

        const mainFormData = new FormData(form);
        const modalFormData = new FormData(completeForm);

        const combinedFormData = new FormData();
        for (let [key, value] of mainFormData.entries()) {
            combinedFormData.append(key, value);
        }
        for (let [key, value] of modalFormData.entries()) {
            combinedFormData.append(key, value);
        }

        fetch(`${window.BASE_URL}app/scripts/schoolProcess.php`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: combinedFormData
        })
        .then(response => response.text())
        .then(data => {
            try {
                const jsonResponse = JSON.parse(data);
                if (jsonResponse.success) {
                    if (typeof Swal !== "undefined") {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: jsonResponse.message || 'Escuela creada exitosamente',
                            icon: 'success',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            const modal = bootstrap.Modal.getInstance(completeSchoolModal);
                            if (modal) modal.hide();
                            loadView('school/consultSchool');
                        });
                    } else {
                        alert('Escuela creada exitosamente');
                        const modal = bootstrap.Modal.getInstance(completeSchoolModal);
                        if (modal) modal.hide();
                        loadView('school/consultSchool');
                    }
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: jsonResponse.message || 'Error al crear la escuela',
                        icon: 'error'
                    });
                }
            } catch {
                const mainContent = document.getElementById('mainContent');
                if (mainContent) mainContent.innerHTML = data;
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                title: 'Error',
                text: 'Error de conexión. Intente nuevamente.',
                icon: 'error'
            });
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
}

};
