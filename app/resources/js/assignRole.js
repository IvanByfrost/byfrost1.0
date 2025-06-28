/**
 * JavaScript para manejar la asignación de roles
 */

// Definir la URL base
const BASE_URL = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');

document.addEventListener('DOMContentLoaded', function() {
    // Cargar usuarios sin rol al cargar la página
    loadUsersWithoutRole();
    
    // Configurar el formulario de búsqueda para usar AJAX
    const searchForm = document.getElementById('searchUserForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevenir envío normal del formulario
            
            const credentialType = document.getElementById('credential_type').value;
            const credentialNumber = document.getElementById('credential_number').value;
            
            if (!credentialType || !credentialNumber) {
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: 'Campos requeridos',
                        text: 'Por favor, selecciona el tipo de documento e ingresa el número.',
                        icon: 'warning'
                    });
                } else {
                    alert('Por favor, completa todos los campos requeridos.');
                }
                return;
            }
            
            // Realizar búsqueda via AJAX
            searchUsersByDocument(credentialType, credentialNumber);
        });
    }
});

/**
 * Busca usuarios por documento via AJAX
 */
function searchUsersByDocument(credentialType, credentialNumber) {
    // Mostrar indicador de carga
    const resultsContainer = document.querySelector('.card:nth-child(3) .card-body');
    if (resultsContainer) {
        resultsContainer.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>';
    }
    
    // Construir URL con parámetros
    const url = `${BASE_URL}?view=user&action=assignRole&credential_type=${encodeURIComponent(credentialType)}&credential_number=${encodeURIComponent(credentialNumber)}`;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Extraer solo la sección de resultados de la respuesta HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const resultsSection = doc.querySelector('.card:nth-child(3)');
        
        if (resultsSection) {
            // Reemplazar la sección de resultados
            const currentResultsSection = document.querySelector('.card:nth-child(3)');
            if (currentResultsSection) {
                currentResultsSection.outerHTML = resultsSection.outerHTML;
            }
        } else {
            // Si no hay resultados, mostrar mensaje
            if (resultsContainer) {
                resultsContainer.innerHTML = '<div class="alert alert-warning"><i class="fas fa-search"></i> No se encontraron usuarios con el documento especificado.</div>';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (resultsContainer) {
            resultsContainer.innerHTML = '<div class="alert alert-danger">Error al buscar usuarios.</div>';
        }
    });
}

/**
 * Muestra el modal para asignar rol
 */
function showAssignRoleModal(userId, userName, currentRole) {
    document.getElementById('modal_user_id').value = userId;
    document.getElementById('modal_user_name').value = userName;
    document.getElementById('modal_current_role').value = currentRole || 'Sin rol asignado';
    document.getElementById('modal_role_type').value = '';
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('assignRoleModal'));
    modal.show();
}

/**
 * Asigna el rol al usuario
 */
function assignRole() {
    const userId = document.getElementById('modal_user_id').value;
    const roleType = document.getElementById('modal_role_type').value;
    
    if (!roleType) {
        if (typeof Swal !== "undefined") {
            Swal.fire({
                title: 'Rol requerido',
                text: 'Por favor, selecciona un rol para asignar.',
                icon: 'warning'
            });
        } else {
            alert('Por favor, selecciona un rol para asignar.');
        }
        return;
    }
    
    // Mostrar indicador de carga
    const assignBtn = document.querySelector('#assignRoleModal .btn-primary');
    const originalText = assignBtn.innerHTML;
    assignBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Asignando...';
    assignBtn.disabled = true;
    
    // Preparar datos
    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('role_type', roleType);
    
    // Enviar petición AJAX
    fetch(`${BASE_URL}?view=user&action=processAssignRole`, {
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
        try {
            const jsonResponse = JSON.parse(data);
            
            if (jsonResponse.success) {
                // Éxito
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: jsonResponse.message,
                        icon: 'success',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        // Cerrar modal y recargar solo la sección de usuarios sin rol
                        const modal = bootstrap.Modal.getInstance(document.getElementById('assignRoleModal'));
                        modal.hide();
                        loadUsersWithoutRole();
                    });
                } else {
                    alert(jsonResponse.message);
                    loadUsersWithoutRole();
                }
            } else {
                // Error
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: 'Error',
                        text: jsonResponse.message,
                        icon: 'error'
                    });
                } else {
                    alert(jsonResponse.message);
                }
            }
        } catch (e) {
            // Si no es JSON, mostrar error genérico
            if (typeof Swal !== "undefined") {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al procesar la respuesta del servidor.',
                    icon: 'error'
                });
            } else {
                alert('Error al procesar la respuesta del servidor.');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        if (typeof Swal !== "undefined") {
            Swal.fire({
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor. Intente nuevamente.',
                icon: 'error'
            });
        } else {
            alert('Error de conexión. Intente nuevamente.');
        }
    })
    .finally(() => {
        // Restaurar botón
        assignBtn.innerHTML = originalText;
        assignBtn.disabled = false;
    });
}

/**
 * Carga usuarios sin rol asignado
 */
function loadUsersWithoutRole() {
    const tableBody = document.querySelector('#usersWithoutRoleTable tbody');
    if (!tableBody) return;
    
    // Mostrar indicador de carga
    tableBody.innerHTML = '<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>';
    
    fetch(`${BASE_URL}?view=user&action=getUsersWithoutRole`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.text();
    })
    .then(data => {
        try {
            const jsonResponse = JSON.parse(data);
            
            if (jsonResponse.success) {
                displayUsersWithoutRole(jsonResponse.data);
            } else {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error: ' + jsonResponse.message + '</td></tr>';
            }
        } catch (e) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error al procesar la respuesta</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error de conexión</td></tr>';
    });
}

/**
 * Muestra los usuarios sin rol en la tabla
 */
function displayUsersWithoutRole(users) {
    const tableBody = document.querySelector('#usersWithoutRoleTable tbody');
    if (!tableBody) return;
    
    if (!users || users.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No hay usuarios sin rol asignado</td></tr>';
        return;
    }
    
    let html = '';
    users.forEach(user => {
        html += `
            <tr>
                <td>${user.user_id}</td>
                <td><strong>${user.first_name} ${user.last_name}</strong></td>
                <td><span class="badge bg-info">${user.credential_type} ${user.credential_number}</span></td>
                <td>${user.email || 'No especificado'}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary" 
                            onclick="showAssignRoleModal(${user.user_id}, '${user.first_name} ${user.last_name}', '')">
                        <i class="fas fa-user-tag"></i> Asignar Rol
                    </button>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
}

/**
 * Refresca la lista de usuarios sin rol
 */
function refreshUsersWithoutRole() {
    loadUsersWithoutRole();
}

/**
 * Limpia el formulario de búsqueda
 */
function clearSearchForm() {
    document.getElementById('credential_type').value = '';
    document.getElementById('credential_number').value = '';
}