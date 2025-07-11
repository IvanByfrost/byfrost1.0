/**
 * Módulo de búsqueda de usuarios
 * Refactorizado sin jQuery
 */

if (typeof window.USER_MANAGEMENT_BASE_URL === 'undefined') {
    window.USER_MANAGEMENT_BASE_URL = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
}

// Toggle de campos en el formulario de búsqueda
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

// Genérica función fetch para búsquedas
function searchUsers({ data, onSuccess }) {
    console.log('[UserSearch] Enviando búsqueda:', data);

    const params = new URLSearchParams(data);
    fetch(`${window.USER_MANAGEMENT_BASE_URL}app/processes/assignProcess.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: params
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        console.log('[UserSearch] Respuesta:', text);
        let result;
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error(e);
            showError('Error procesando la respuesta del servidor');
            return;
        }

        if (result.status === 'ok') {
            onSuccess(result.data);
        } else {
            showError(result.msg || 'Error en la búsqueda');
        }
    })
    .catch(error => {
        console.error('[UserSearch] Error:', error);
        showError('Error de conexión con el servidor');
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

// Muestra resultados de búsqueda (para asignación de roles)
function displaySearchResults(users) {
    renderUserTable(users, {
        showActions: true,
        containerId: 'searchResults'
    });
}

// Muestra resultados de consulta (sin acciones)
function displayConsultResults(users) {
    renderUserTable(users, {
        showActions: false,
        containerId: 'searchResults'
    });
}

// Renderiza tabla de usuarios
function renderUserTable(users, { showActions, containerId }) {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (!users || users.length === 0) {
        container.innerHTML = '<div class="alert alert-info">No se encontraron usuarios con los criterios especificados.</div>';
        return;
    }

    const rows = users.map(user => {
        return `
            <tr>
                <td>${user.first_name} ${user.last_name}</td>
                <td>${user.email}</td>
                <td>
                    <span class="badge bg-${user.role_type ? 'primary' : 'secondary'}">
                        ${traducirRol(user.role_type || 'Sin rol')}
                    </span>
                </td>
                <td>
                    ${showActions ? `
                        <button class="btn btn-sm btn-primary" 
                            onclick="showAssignRoleModal(${user.user_id}, '${user.first_name} ${user.last_name}', '${user.role_type || ''}')">
                            <i class="fas fa-user-plus"></i> Asignar Rol
                        </button>` 
                        : `<span class="badge bg-success">Activo</span>`
                    }
                </td>
            </tr>`;
    }).join('');

    const tableHtml = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>${showActions ? 'Acciones' : 'Estado'}</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        </div>
    `;

    container.innerHTML = tableHtml;
}

// Traduce el tipo de rol
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
    console.error('[UserSearch] Error:', message);
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

// Inicializa formulario de búsqueda
function initializeConsultUserForm() {
    window.toggleSearchFields();

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
    console.log('[UserSearch] Inicializando...');
    window.toggleSearchFields();
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