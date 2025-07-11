/**
 * Módulo de búsqueda de usuarios
 */

if (typeof window.USER_MANAGEMENT_BASE_URL === 'undefined') {
    window.USER_MANAGEMENT_BASE_URL = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
}

// Función para mostrar/ocultar campos según el tipo de búsqueda
function toggleSearchFields() {
    const searchTypeSelect = document.getElementById('search_type');
    if (!searchTypeSelect) return;
    const searchType = searchTypeSelect.value;
    const documentTypeField = document.getElementById('document_type_field');
    const documentNumberField = document.getElementById('document_number_field');
    const roleTypeField = document.getElementById('role_type_field');
    
    // Ocultar todos los campos primero
    if (documentTypeField) documentTypeField.style.display = 'none';
    if (documentNumberField) documentNumberField.style.display = 'none';
    if (roleTypeField) roleTypeField.style.display = 'none';
    
    // Mostrar campos según el tipo seleccionado
    if (searchType === 'document') {
        if (documentTypeField) documentTypeField.style.display = 'block';
        if (documentNumberField) documentNumberField.style.display = 'block';
    } else if (searchType === 'role') {
        if (roleTypeField) roleTypeField.style.display = 'block';
    }
}

// Búsqueda de usuarios por documento
function searchUsersByDocument(credentialType, credentialNumber) {
    console.log('Buscando usuarios por documento:', credentialType, credentialNumber);
    
    $.ajax({
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        method: 'POST',
        data: {
            subject: 'search_users',
            credential_type: credentialType,
            credential_number: credentialNumber
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.status === 'ok') {
                    displaySearchResults(data.data);
                } else {
                    showError(data.msg || 'Error en la búsqueda');
                }
            } catch (e) {
                showError('Error procesando la respuesta del servidor');
            }
        },
        error: function() {
            showError('Error de conexión con el servidor');
        }
    });
}

// Búsqueda de usuarios para consulta
function searchUsersForConsult(credentialType, credentialNumber) {
    console.log('Buscando usuarios para consulta:', credentialType, credentialNumber);
    
    $.ajax({
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        method: 'POST',
        data: {
            subject: 'search_users',
            credential_type: credentialType,
            credential_number: credentialNumber
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.status === 'ok') {
                    displayConsultResults(data.data);
                } else {
                    showError(data.msg || 'Error en la búsqueda');
                }
            } catch (e) {
                showError('Error procesando la respuesta del servidor');
            }
        },
        error: function() {
            showError('Error de conexión con el servidor');
        }
    });
}

// Búsqueda de usuarios por rol
function searchUsersByRole(roleType) {
    console.log('Buscando usuarios por rol:', roleType);
    
    $.ajax({
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        method: 'POST',
        data: {
            subject: 'search_users_by_role',
            role_type: roleType
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.status === 'ok') {
                    displayConsultResults(data.data);
                } else {
                    showError(data.msg || 'Error en la búsqueda');
                }
            } catch (e) {
                showError('Error procesando la respuesta del servidor');
            }
        },
        error: function() {
            showError('Error de conexión con el servidor');
        }
    });
}

// Mostrar resultados de búsqueda
function displaySearchResults(users) {
    const resultsContainer = document.getElementById('searchResults');
    if (!resultsContainer) {
        console.error('Contenedor de resultados no encontrado');
        return;
    }
    
    if (!users || users.length === 0) {
        resultsContainer.innerHTML = '<div class="alert alert-info">No se encontraron usuarios con los criterios especificados.</div>';
        return;
    }
    
    let html = '<div class="table-responsive"><table class="table table-striped">';
    html += '<thead><tr><th>Usuario</th><th>Email</th><th>Rol Actual</th><th>Acciones</th></tr></thead><tbody>';
    
    users.forEach(user => {
        html += `<tr>
            <td>${user.first_name} ${user.last_name}</td>
            <td>${user.email}</td>
            <td><span class="badge bg-secondary">${traducirRol(user.role_type || 'Sin rol')}</span></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="showAssignRoleModal(${user.user_id}, '${user.first_name} ${user.last_name}', '${user.role_type || ''}')">
                    <i class="fas fa-user-plus"></i> Asignar Rol
                </button>
            </td>
        </tr>`;
    });
    
    html += '</tbody></table></div>';
    resultsContainer.innerHTML = html;
}

// Mostrar resultados de consulta
function displayConsultResults(users) {
    const resultsContainer = document.getElementById('searchResults');
    if (!resultsContainer) {
        console.error('Contenedor de resultados no encontrado');
        return;
    }
    
    if (!users || users.length === 0) {
        resultsContainer.innerHTML = '<div class="alert alert-info">No se encontraron usuarios con los criterios especificados.</div>';
        return;
    }
    
    let html = '<div class="table-responsive"><table class="table table-striped">';
    html += '<thead><tr><th>Usuario</th><th>Email</th><th>Rol</th><th>Estado</th></tr></thead><tbody>';
    
    users.forEach(user => {
        const roleBadge = user.role_type ? 
            `<span class="badge bg-primary">${traducirRol(user.role_type)}</span>` : 
            '<span class="badge bg-secondary">Sin rol</span>';
        
        html += `<tr>
            <td>${user.first_name} ${user.last_name}</td>
            <td>${user.email}</td>
            <td>${roleBadge}</td>
            <td><span class="badge bg-success">Activo</span></td>
        </tr>`;
    });
    
    html += '</tbody></table></div>';
    resultsContainer.innerHTML = html;
}

// Traducir roles
function traducirRol(rol) {
    const traducciones = {
        'root': 'Administrador',
        'director': 'Director',
        'coordinator': 'Coordinador',
        'teacher': 'Profesor',
        'student': 'Estudiante',
        'parent': 'Padre',
        'treasurer': 'Tesorero'
    };
    return traducciones[rol] || rol;
}

// Limpiar formulario de búsqueda
function clearSearchForm() {
    const form = document.getElementById('searchUserForm');
    if (form) {
        form.reset();
    }
    
    const resultsContainer = document.getElementById('searchResults');
    if (resultsContainer) {
        resultsContainer.innerHTML = '';
    }
}

// Función para mostrar errores
function showError(message) {
    console.error('Error:', message);
    if (typeof Swal !== 'undefined') {
        Swal.fire('Error', message, 'error');
    } else {
        alert('Error: ' + message);
    }
}

// Inicialización del formulario de consulta de usuarios
function initializeConsultUserForm() {
    // Mostrar campos correctos al cargar
    if (typeof toggleSearchFields === 'function') {
        toggleSearchFields();
    }

    // Manejar el submit del formulario de búsqueda
    const form = document.getElementById('searchUserForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchType = document.getElementById('search_type').value;
            if (searchType === 'document') {
                const credentialType = document.getElementById('credential_type').value;
                const credentialNumber = document.getElementById('credential_number').value;
                if (!credentialType || !credentialNumber) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'Debes seleccionar el tipo y número de documento', 'warning');
                    } else {
                        alert('Debes seleccionar el tipo y número de documento');
                    }
                    return;
                }
                searchUsersForConsult(credentialType, credentialNumber);
            } else if (searchType === 'role') {
                const roleType = document.getElementById('role_type').value;
                if (!roleType) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'Debes seleccionar un rol', 'warning');
                    } else {
                        alert('Debes seleccionar un rol');
                    }
                    return;
                }
                searchUsersByRole(roleType);
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', 'Selecciona un tipo de búsqueda', 'warning');
                } else {
                    alert('Selecciona un tipo de búsqueda');
                }
            }
            // Mostrar la tarjeta de resultados
            const resultsCard = document.getElementById('searchResultsCard');
            if (resultsCard) {
                resultsCard.style.display = 'block';
            }
        });
    }
}

// Auto-inicializar cuando el DOM esté listo
function initializeWhenReady() {
    console.log('userSearch.js: Inicializando...');
    
    // Verificar que las funciones estén disponibles
    if (typeof toggleSearchFields === 'function') {
        console.log('userSearch.js: toggleSearchFields disponible');
        toggleSearchFields();
    } else {
        console.error('userSearch.js: toggleSearchFields NO disponible');
    }
    
    if (typeof initializeConsultUserForm === 'function') {
        console.log('userSearch.js: initializeConsultUserForm disponible');
        initializeConsultUserForm();
    } else {
        console.error('userSearch.js: initializeConsultUserForm NO disponible');
    }
}

// Múltiples formas de inicialización para mayor compatibilidad
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeWhenReady);
} else {
    // Si ya está cargado, ejecutar inmediatamente
    initializeWhenReady();
}

// También ejecutar cuando la ventana esté completamente cargada
window.addEventListener('load', function() {
    console.log('userSearch.js: Window load event');
    if (typeof toggleSearchFields === 'function') {
        toggleSearchFields();
    }
    if (typeof initializeConsultUserForm === 'function') {
        initializeConsultUserForm();
    }
});

// Hacer las funciones disponibles globalmente de forma más explícita
window.toggleSearchFields = toggleSearchFields;
window.searchUsersByDocument = searchUsersByDocument;
window.searchUsersForConsult = searchUsersForConsult;
window.searchUsersByRole = searchUsersByRole;
window.displaySearchResults = displaySearchResults;
window.displayConsultResults = displayConsultResults;
window.initializeConsultUserForm = initializeConsultUserForm;
window.showError = showError;