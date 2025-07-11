/**
 * Módulo de búsqueda de usuarios
 */

// Establece una URL base para las peticiones si no está definida
if (typeof window.USER_MANAGEMENT_BASE_URL === 'undefined') {
    window.USER_MANAGEMENT_BASE_URL = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
}

// Usar la función global toggleSearchFields si está disponible, sino crear una local
if (typeof window.toggleSearchFields === 'undefined') {
    window.toggleSearchFields = function() {
        const searchType = document.getElementById('search_type')?.value;
        document.getElementById('document_type_field')?.style.setProperty('display', 'none');
        document.getElementById('document_number_field')?.style.setProperty('display', 'none');
        document.getElementById('role_type_field')?.style.setProperty('display', 'none');

        if (searchType === 'document') {
            document.getElementById('document_type_field')?.style.setProperty('display', 'block');
            document.getElementById('document_number_field')?.style.setProperty('display', 'block');
        } else if (searchType === 'role') {
            document.getElementById('role_type_field')?.style.setProperty('display', 'block');
        }
    };
}

// Función AJAX genérica para búsquedas
function searchUsers(options) {
    const { data, onSuccess } = options;

    $.ajax({
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        method: 'POST',
        data: data,
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.status === 'ok') {
                    onSuccess(result.data);
                } else {
                    showError(result.msg || 'Error en la búsqueda');
                }
            } catch {
                showError('Error procesando la respuesta del servidor');
            }
        },
        error: function() {
            showError('Error de conexión con el servidor');
        }
    });
}

// Búsqueda por documento
function searchUsersByDocument(credentialType, credentialNumber) {
    searchUsers({
        data: {
            subject: 'search_users',
            credential_type: credentialType,
            credential_number: credentialNumber
        },
        onSuccess: displaySearchResults
    });
}

// Búsqueda para consulta por documento
function searchUsersForConsult(credentialType, credentialNumber) {
    searchUsers({
        data: {
            subject: 'search_users',
            credential_type: credentialType,
            credential_number: credentialNumber
        },
        onSuccess: displayConsultResults
    });
}

// Búsqueda por rol
function searchUsersByRole(roleType) {
    searchUsers({
        data: {
            subject: 'search_users_by_role',
            role_type: roleType
        },
        onSuccess: displayConsultResults
    });
}

// Mostrar resultados de búsqueda con botón de asignación
function displaySearchResults(users) {
    const container = document.getElementById('searchResults');
    if (!container) return;

    if (!users || users.length === 0) {
        container.innerHTML = '<div class="alert alert-info">No se encontraron usuarios con los criterios especificados.</div>';
        return;
    }

    const html = `
        <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>Usuario</th><th>Email</th><th>Rol Actual</th><th>Acciones</th></tr></thead>
            <tbody>
                ${users.map(user => `
                    <tr>
                        <td>${user.first_name} ${user.last_name}</td>
                        <td>${user.email}</td>
                        <td><span class="badge bg-secondary">${traducirRol(user.role_type || 'Sin rol')}</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="showAssignRoleModal(${user.user_id}, '${user.first_name} ${user.last_name}', '${user.role_type || ''}')">
                                <i class="fas fa-user-plus"></i> Asignar Rol
                            </button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
        </div>`;
    
    container.innerHTML = html;
}

// Mostrar resultados de consulta sin acciones
function displayConsultResults(users) {
    const container = document.getElementById('searchResults');
    if (!container) return;

    if (!users || users.length === 0) {
        container.innerHTML = '<div class="alert alert-info">No se encontraron usuarios con los criterios especificados.</div>';
        return;
    }

    const html = `
        <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>Usuario</th><th>Email</th><th>Rol</th><th>Estado</th></tr></thead>
            <tbody>
                ${users.map(user => `
                    <tr>
                        <td>${user.first_name} ${user.last_name}</td>
                        <td>${user.email}</td>
                        <td><span class="badge bg-${user.role_type ? 'primary' : 'secondary'}">${traducirRol(user.role_type || 'Sin rol')}</span></td>
                        <td><span class="badge bg-success">Activo</span></td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
        </div>`;
    
    container.innerHTML = html;
}

// Traducción de tipos de rol
function traducirRol(rol) {
    const roles = {
        root: 'Administrador',
        director: 'Director',
        coordinator: 'Coordinador',
        teacher: 'Profesor',
        student: 'Estudiante',
        parent: 'Padre',
        treasurer: 'Tesorero'
    };
    return roles[rol] || rol;
}

// Mostrar errores
function showError(message) {
    console.error('Error:', message);
    if (typeof Swal !== 'undefined') {
        Swal.fire('Error', message, 'error');
    } else {
        alert('Error: ' + message);
    }
}

// Limpiar formulario y resultados
function clearSearchForm() {
    document.getElementById('searchUserForm')?.reset();
    const container = document.getElementById('searchResults');
    if (container) container.innerHTML = '';
}

// Inicializar formulario de búsqueda
function initializeConsultUserForm() {
    window.toggleSearchFields(); // Usar la función global

    const form = document.getElementById('searchUserForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const searchType = document.getElementById('search_type')?.value;

        if (searchType === 'document') {
            const credentialType = document.getElementById('credential_type')?.value;
            const credentialNumber = document.getElementById('credential_number')?.value;
            if (!credentialType || !credentialNumber) {
                return showError('Debes seleccionar el tipo y número de documento');
            }
            searchUsersForConsult(credentialType, credentialNumber);

        } else if (searchType === 'role') {
            const roleType = document.getElementById('role_type')?.value;
            if (!roleType) {
                return showError('Debes seleccionar un rol');
            }
            searchUsersByRole(roleType);
        } else {
            return showError('Selecciona un tipo de búsqueda');
        }

        const resultsCard = document.getElementById('searchResultsCard');
        if (resultsCard) resultsCard.style.display = 'block';
    });
}

// Inicialización automática
function initializeWhenReady() {
    console.log('userSearch.js: Inicializando...');
    window.toggleSearchFields(); // Usar la función global
    initializeConsultUserForm();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeWhenReady);
} else {
    initializeWhenReady();
}

window.addEventListener('load', initializeWhenReady);

// Exposición global
window.searchUsersByDocument = searchUsersByDocument;
window.searchUsersForConsult = searchUsersForConsult;
window.searchUsersByRole = searchUsersByRole;
window.displaySearchResults = displaySearchResults;
window.displayConsultResults = displayConsultResults;
window.initializeConsultUserForm = initializeConsultUserForm;
window.showError = showError;
window.clearSearchForm = clearSearchForm;
