/**
 * JavaScript para gestión de usuarios (asignación, consulta e historial)
 * Módulo principal
 */

if (typeof window.USER_MANAGEMENT_BASE_URL === 'undefined') {
    window.USER_MANAGEMENT_BASE_URL = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
}

function showError(message) {
    console.error('[UserManagement] Error:', message);

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    } else {
        alert('Error: ' + message);
    }
}

function showConfirm({ title, text, confirmButtonText, onConfirm }) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title,
            text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmButtonText || 'Sí, continuar'
        }).then(result => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
    } else {
        if (confirm(text)) {
            if (typeof onConfirm === 'function') {
                onConfirm();
            }
        }
    }
}

function waitForDOM() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeUserManagement);
    } else {
        initializeUserManagement();
    }
}

function initializeUserManagement() {
    console.log('[UserManagement] Inicializando...');

    const searchForm = document.getElementById('searchUserForm');
    const roleHistoryForm = document.getElementById('roleHistoryForm');
    const usersTable = document.getElementById('usersWithoutRoleTable');
    const modal = document.getElementById('assignRoleModal');

    console.log('[UserManagement] Elementos encontrados:', {
        searchForm: !!searchForm,
        roleHistoryForm: !!roleHistoryForm,
        usersTable: !!usersTable,
        modal: !!modal
    });

    if (!searchForm && !roleHistoryForm) {
        console.log('[UserManagement] No es una página de gestión de usuarios.');
        return;
    }

    if (searchForm) {
        searchForm.removeEventListener('submit', handleSearchSubmit);
        searchForm.addEventListener('submit', handleSearchSubmit);
    }

    if (roleHistoryForm) {
        roleHistoryForm.removeEventListener('submit', handleRoleHistorySubmit);
        roleHistoryForm.addEventListener('submit', handleRoleHistorySubmit);
    }

    if (usersTable && modal) {
        console.log('[UserManagement] Página de asignación de roles detectada.');
        initializeAssignRole();
    } else if (roleHistoryForm) {
        console.log('[UserManagement] Página de historial de roles detectada.');
        initializeRoleHistory();
    } else {
        console.log('[UserManagement] Página de consulta de usuarios detectada.');
        initializeConsultUser();
    }
}

window.initUserManagementAfterLoad = function() {
    console.log('[UserManagement] Reinicializando tras carga dinámica...');
    setTimeout(() => {
        initializeUserManagement();
    }, 100);
};

function initializeAssignRole() {
    console.log('[UserManagement] Inicializando asignación de roles...');
    loadUsersWithoutRole();
}

function initializeRoleHistory() {
    console.log('[UserManagement] Inicializando historial de roles...');
}

function initializeConsultUser() {
    console.log('[UserManagement] Inicializando consulta de usuarios...');
    const searchType = document.getElementById('search_type');
    if (searchType) {
        searchType.addEventListener('change', window.toggleSearchFields);
    }
}

function handleSearchSubmit(e) {
    e.preventDefault();
    console.log('[UserManagement] Formulario de búsqueda enviado.');

    const usersTable = document.getElementById('usersWithoutRoleTable');
    const modal = document.getElementById('assignRoleModal');

    if (usersTable && modal) {
        const credentialType = document.getElementById('credential_type').value;
        const credentialNumber = document.getElementById('credential_number').value;

        if (!credentialType || !credentialNumber) {
            showError('Por favor, selecciona el tipo de documento e ingresa el número.');
            return;
        }

        console.log('[UserManagement] Búsqueda para asignación de roles...');
        searchUsersByDocument(credentialType, credentialNumber);
    } else {
        const searchType = document.getElementById('search_type').value;

        if (!searchType) {
            showError('Por favor, selecciona el tipo de búsqueda.');
            return;
        }

        if (searchType === 'document') {
            const credentialType = document.getElementById('credential_type').value;
            const credentialNumber = document.getElementById('credential_number').value;

            if (!credentialType || !credentialNumber) {
                showError('Por favor, selecciona el tipo de documento e ingresa el número.');
                return;
            }

            console.log('[UserManagement] Búsqueda por documento.');
            searchUsersForConsult(credentialType, credentialNumber);
        } else if (searchType === 'role') {
            const roleType = document.getElementById('role_type').value;

            if (!roleType) {
                showError('Por favor, selecciona un rol.');
                return;
            }

            console.log('[UserManagement] Búsqueda por rol.');
            searchUsersByRole(roleType);
        }
    }
}

function handleRoleHistorySubmit(e) {
    e.preventDefault();
    console.log('[UserManagement] Formulario de historial enviado.');

    const credentialType = document.getElementById('credential_type').value;
    const credentialNumber = document.getElementById('credential_number').value;

    if (!credentialType || !credentialNumber) {
        showError('Por favor, selecciona el tipo de documento e ingresa el número.');
        return;
    }

    console.log('[UserManagement] Buscando historial de roles...');
    searchRoleHistory(credentialType, credentialNumber);
}

function refreshIcons() {
    if (typeof FontAwesome !== 'undefined') {
        FontAwesome.dom.i2svg();
    }
}

function searchUserAJAX(e) {
    e.preventDefault();

    const searchType = document.getElementById('search_type').value;
    let searchData = {};

    switch (searchType) {
        case 'document':
            const credentialType = document.getElementById('credential_type').value;
            const credentialNumber = document.getElementById('credential_number').value;

            if (!credentialType || !credentialNumber) {
                showError('Por favor, completa los datos de documento.');
                return false;
            }

            searchData = {
                search_type: 'document',
                credential_type: credentialType,
                credential_number: credentialNumber
            };
            break;

        case 'role':
            const roleType = document.getElementById('role_type').value;

            if (!roleType) {
                showError('Por favor, selecciona un rol.');
                return false;
            }

            searchData = {
                search_type: 'role',
                role_type: roleType
            };
            break;

        case 'name':
            const nameSearch = document.getElementById('name_search').value.trim();

            if (!nameSearch) {
                showError('Por favor, ingresa un nombre.');
                return false;
            }

            searchData = {
                search_type: 'name',
                name_search: nameSearch
            };
            break;

        default:
            showError('Selecciona un tipo de búsqueda.');
            return false;
    }

    const params = new URLSearchParams(searchData);
    if (typeof loadView === 'function') {
        loadView('user/consultUser?' + params.toString());
    } else {
        const url = `${window.location.origin}${window.location.pathname}?view=user&action=consultUser&${params.toString()}`;
        window.location.href = url;
    }

    return false;
}

function confirmDeleteUser(userId) {
    showConfirm({
        title: 'Eliminar Usuario',
        text: '¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.',
        confirmButtonText: 'Sí, eliminar',
        onConfirm: () => {
            if (typeof loadView === 'function') {
                loadView(`user/delete?id=${userId}`);
            } else {
                window.location.href = `${window.location.origin}${window.location.pathname}?view=user&action=delete&id=${userId}`;
            }
        }
    });
}

function confirmDeactivateUser(userId) {
    showConfirm({
        title: 'Desactivar Usuario',
        text: '¿Estás seguro de que deseas desactivar este usuario? No podrá acceder al sistema.',
        confirmButtonText: 'Sí, desactivar',
        onConfirm: () => {
            loadView(`user/deactivate?id=${userId}`);
        }
    });
}

function confirmActivateUser(userId) {
    showConfirm({
        title: 'Activar Usuario',
        text: '¿Estás seguro de que deseas activar este usuario? Podrá acceder al sistema nuevamente.',
        confirmButtonText: 'Sí, activar',
        onConfirm: () => {
            loadView(`user/activate?id=${userId}`);
        }
    });
}

waitForDOM();
