/**
 * JavaScript para manejar la gestión de usuarios (asignación de roles, consulta e historial)
 */

// Definir la URL base solo si no está definida
if (typeof window.USER_MANAGEMENT_BASE_URL === 'undefined') {
    window.USER_MANAGEMENT_BASE_URL = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
}

// Función para inicializar cuando el DOM esté listo
function initializeUserManagement() {
    console.log('Inicializando sistema de gestión de usuarios...');
    
    // Verificar que jQuery esté disponible
    if (typeof $ === 'undefined') {
        console.error('jQuery no está disponible. El sistema de gestión de usuarios no funcionará.');
        return;
    }
    
    // Verificar que los elementos necesarios existan
    const searchForm = document.getElementById('searchUserForm');
    const roleHistoryForm = document.getElementById('roleHistoryForm');
    const usersTable = document.getElementById('usersWithoutRoleTable');
    const modal = document.getElementById('assignRoleModal');
    
    console.log('Elementos encontrados:', {
        searchForm: !!searchForm,
        roleHistoryForm: !!roleHistoryForm,
        usersTable: !!usersTable,
        modal: !!modal
    });
    
    // Si no encontramos ningún formulario, probablemente no estamos en la página correcta
    if (!searchForm && !roleHistoryForm) {
        console.log('No estamos en una página de gestión de usuarios. Saltando inicialización.');
        return;
    }
    
    // Configurar formularios para usar AJAX
    if (searchForm) {
        console.log('Configurando eventos del formulario de búsqueda...');
        searchForm.removeEventListener('submit', handleSearchSubmit);
        searchForm.addEventListener('submit', handleSearchSubmit);
    }
    
    if (roleHistoryForm) {
        console.log('Configurando eventos del formulario de historial de roles...');
        roleHistoryForm.removeEventListener('submit', handleRoleHistorySubmit);
        roleHistoryForm.addEventListener('submit', handleRoleHistorySubmit);
    }
    
    // Determinar qué tipo de página es y inicializar específicamente
    if (usersTable && modal) {
        // Es la página de asignación de roles
        console.log('Detectada página de asignación de roles...');
        initializeAssignRole();
    } else if (roleHistoryForm) {
        // Es la página de historial de roles
        console.log('Detectada página de historial de roles...');
        initializeRoleHistory();
    } else {
        // Es la página de consulta de usuarios
        console.log('Detectada página de consulta de usuarios...');
        initializeConsultUser();
    }
}

// Función para inicializar después de que loadViews.js cargue el contenido
function initUserManagementAfterLoad() {
    console.log('Inicializando gestión de usuarios después de carga de vista...');
    
    // Pequeño delay para asegurar que el DOM esté actualizado
    setTimeout(() => {
        initializeUserManagement();
    }, 100);
}

// Inicializar específicamente para asignación de roles
function initializeAssignRole() {
    console.log('Inicializando funcionalidad de asignación de roles...');
    
    const usersTable = document.getElementById('usersWithoutRoleTable');
    const modal = document.getElementById('assignRoleModal');
    
    if (!usersTable) {
        console.error('Tabla de usuarios sin rol no encontrada');
        return;
    }
    
    if (!modal) {
        console.error('Modal de asignación no encontrado');
    }
    
    // Cargar usuarios sin rol al cargar la página
    console.log('Cargando usuarios sin rol...');
    loadUsersWithoutRole();
}

// Inicializar específicamente para historial de roles
function initializeRoleHistory() {
    console.log('Inicializando funcionalidad de historial de roles...');
    console.log('Formulario de historial de roles configurado correctamente');
}

// Inicializar específicamente para consulta de usuarios
function initializeConsultUser() {
    console.log('Inicializando funcionalidad de consulta de usuarios...');
    
    // Configurar campos dinámicos para búsqueda
    const searchType = document.getElementById('search_type');
    if (searchType) {
        searchType.addEventListener('change', toggleSearchFields);
    }
    
    console.log('Formulario de consulta configurado correctamente');
}

// Función para mostrar/ocultar campos según el tipo de búsqueda
function toggleSearchFields() {
    const searchType = document.getElementById('search_type').value;
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

// Manejador del envío del formulario de búsqueda general
function handleSearchSubmit(e) {
    e.preventDefault(); // Prevenir envío normal del formulario
    console.log('Formulario enviado, procesando búsqueda...');
    
    // Determinar qué tipo de página es
    const usersTable = document.getElementById('usersWithoutRoleTable');
    const modal = document.getElementById('assignRoleModal');
    
    if (usersTable && modal) {
        // Es asignación de roles - búsqueda por documento
        const credentialType = document.getElementById('credential_type').value;
        const credentialNumber = document.getElementById('credential_number').value;
        
        if (!credentialType || !credentialNumber) {
            showError('Por favor, selecciona el tipo de documento e ingresa el número.');
            return;
        }
        
        console.log('Realizando búsqueda para asignación de roles...');
        searchUsersByDocument(credentialType, credentialNumber);
    } else {
        // Es consulta de usuarios - puede ser por documento o por rol
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
            
            console.log('Realizando búsqueda por documento para consulta...');
            searchUsersForConsult(credentialType, credentialNumber);
        } else if (searchType === 'role') {
            const roleType = document.getElementById('role_type').value;
            
            if (!roleType) {
                showError('Por favor, selecciona un rol.');
                return;
            }
            
            console.log('Realizando búsqueda por rol para consulta...');
            searchUsersByRole(roleType);
        }
    }
}

// Manejador del envío del formulario de historial de roles
function handleRoleHistorySubmit(e) {
    e.preventDefault(); // Prevenir envío normal del formulario
    console.log('Formulario de historial enviado, procesando búsqueda...');
    
    const credentialType = document.getElementById('credential_type').value;
    const credentialNumber = document.getElementById('credential_number').value;
    
    if (!credentialType || !credentialNumber) {
        showError('Por favor, selecciona el tipo de documento e ingresa el número.');
        return;
    }
    
    console.log('Realizando búsqueda de historial de roles...');
    searchRoleHistory(credentialType, credentialNumber);
}

// Función para mostrar errores
function showError(message) {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            title: 'Campos requeridos',
            text: message,
            icon: 'warning'
        });
    } else {
        alert(message);
    }
}

// Función para inicializar cuando el DOM esté completamente listo (para páginas que se cargan directamente)
function waitForDOM() {
    if (document.readyState === 'complete') {
        console.log('DOM completamente cargado, verificando si estamos en una página de gestión de usuarios...');
        initializeUserManagement();
    } else if (document.readyState === 'interactive') {
        console.log('DOM interactivo, esperando...');
        setTimeout(waitForDOM, 100);
    } else {
        console.log('DOM cargando, esperando...');
        setTimeout(waitForDOM, 100);
    }
}

// Iniciar el proceso de espera solo si no estamos usando loadViews.js
// Verificar si estamos en una página que usa loadViews.js
const isLoadViewsPage = window.location.search.includes('view=index&action=loadPartial') || 
                       document.getElementById('mainContent') !== null;

if (!isLoadViewsPage) {
    console.log('Página de carga directa detectada, iniciando waitForDOM...');
    waitForDOM();
} else {
    console.log('Página con loadViews.js detectada, esperando inicialización manual...');
}

/**
 * Busca el historial de roles de un usuario
 */
function searchRoleHistory(credentialType, credentialNumber) {
    console.log('Buscando historial de roles:', credentialType, credentialNumber);
    
    // Mostrar indicador de carga
    const resultsContainer = document.getElementById('searchResultsContainer');
    const resultsCard = document.getElementById('searchResultsCard');
    
    if (resultsContainer && resultsCard) {
        resultsCard.style.display = 'block';
        resultsContainer.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando historial de roles...</div>';
    }
    
    $.ajax({
        type: 'POST',
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        dataType: "JSON",
        data: {
            "credential_type": credentialType,
            "credential_number": credentialNumber,
            "subject": "search_role_history"
        },
        success: function(response) {
            console.log('Respuesta de historial de roles:', response);
            
            if (response.status === 'ok') {
                if (response.data && response.data.length > 0) {
                    // Mostrar historial de roles
                    displayRoleHistory(response.data, response.userInfo);
                } else if (response.data === null) {
                    // No se encontró el usuario
                    if (resultsContainer) {
                        resultsContainer.innerHTML = '<div class="alert alert-warning"><i class="fas fa-search"></i> ' + response.msg + '</div>';
                    }
                } else {
                    // Usuario encontrado pero sin historial
                    if (resultsContainer) {
                        let html = '';
                        if (response.userInfo) {
                            html += '<div class="alert alert-info mb-3">';
                            html += '<strong>Usuario encontrado:</strong> ' + response.userInfo.first_name + ' ' + response.userInfo.last_name;
                            html += ' (' + response.userInfo.credential_type + ' ' + response.userInfo.credential_number + ')';
                            html += '</div>';
                        }
                        html += '<div class="alert alert-info">No hay historial de roles para este usuario.</div>';
                        resultsContainer.innerHTML = html;
                    }
                }
            } else {
                // Error
                if (resultsContainer) {
                    resultsContainer.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> ' + response.msg + '</div>';
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en búsqueda de historial:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            
            if (resultsContainer) {
                resultsContainer.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error al buscar el historial de roles. Detalles: ' + error + '</div>';
            }
        }
    });
}

/**
 * Busca usuarios por documento para asignación de roles
 */
function searchUsersByDocument(credentialType, credentialNumber) {
    console.log('Buscando usuarios para asignación:', credentialType, credentialNumber);
    
    // Mostrar indicador de carga
    const resultsContainer = document.getElementById('searchResultsContainer');
    const resultsCard = document.getElementById('searchResultsCard');
    
    if (resultsContainer && resultsCard) {
        resultsCard.style.display = 'block';
        resultsContainer.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>';
    }
    
    $.ajax({
        type: 'POST',
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        dataType: "JSON",
        data: {
            "credential_type": credentialType,
            "credential_number": credentialNumber,
            "subject": "search_users"
        },
        success: function(response) {
            console.log('Respuesta de búsqueda para asignación:', response);
            
            if (response.status === 'ok') {
                if (response.data && response.data.length > 0) {
                    // Mostrar resultados en tabla
                    displaySearchResults(response.data);
                } else {
                    // No se encontraron usuarios
                    if (resultsContainer) {
                        resultsContainer.innerHTML = '<div class="alert alert-warning"><i class="fas fa-search"></i> ' + response.msg + '</div>';
                    }
                }
            } else {
                // Error
                if (resultsContainer) {
                    resultsContainer.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> ' + response.msg + '</div>';
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en búsqueda para asignación:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            
            if (resultsContainer) {
                resultsContainer.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error de conexión. Inténtalo de nuevo.</div>';
            }
        }
    });
}

/**
 * Busca usuarios por documento para consulta
 */
function searchUsersForConsult(credentialType, credentialNumber) {
    console.log('Buscando usuarios para consulta:', credentialType, credentialNumber);
    
    // Mostrar indicador de carga
    const resultsContainer = document.getElementById('searchResultsContainer');
    const resultsCard = document.getElementById('searchResultsCard');
    
    if (resultsContainer && resultsCard) {
        resultsCard.style.display = 'block';
        resultsContainer.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>';
    }
    
    // Por ahora usamos el mismo endpoint, pero podrías crear uno específico
    $.ajax({
        type: 'POST',
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        dataType: "JSON",
        data: {
            "credential_type": credentialType,
            "credential_number": credentialNumber,
            "subject": "search_users"
        },
        success: function(response) {
            console.log('Respuesta de búsqueda para consulta:', response);
            
            if (response.status === 'ok') {
                if (response.data && response.data.length > 0) {
                    // Mostrar resultados en tabla para consulta
                    displayConsultResults(response.data);
                } else {
                    // No se encontraron usuarios
                    if (resultsContainer) {
                        resultsContainer.innerHTML = '<div class="alert alert-warning"><i class="fas fa-search"></i> ' + response.msg + '</div>';
                    }
                }
            } else {
                // Error
                if (resultsContainer) {
                    resultsContainer.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> ' + response.msg + '</div>';
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en búsqueda para consulta:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            
            if (resultsContainer) {
                resultsContainer.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error de conexión. Inténtalo de nuevo.</div>';
            }
        }
    });
}

/**
 * Busca usuarios por rol para consulta
 */
function searchUsersByRole(roleType) {
    console.log('Buscando usuarios por rol:', roleType);
    
    // Mostrar indicador de carga
    const resultsContainer = document.getElementById('searchResultsContainer');
    const resultsCard = document.getElementById('searchResultsCard');
    
    if (resultsContainer && resultsCard) {
        resultsCard.style.display = 'block';
        resultsContainer.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>';
    }
    
    // Usar el mismo endpoint pero con subject diferente
    $.ajax({
        type: 'POST',
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        dataType: "JSON",
        data: {
            "role_type": roleType,
            "subject": "search_users_by_role"
        },
        success: function(response) {
            console.log('Respuesta de búsqueda por rol:', response);
            
            if (response.status === 'ok') {
                if (response.data && response.data.length > 0) {
                    // Mostrar resultados en tabla para consulta
                    displayConsultResults(response.data);
                } else {
                    // No se encontraron usuarios
                    if (resultsContainer) {
                        resultsContainer.innerHTML = '<div class="alert alert-warning"><i class="fas fa-search"></i> ' + response.msg + '</div>';
                    }
                }
            } else {
                // Error
                if (resultsContainer) {
                    resultsContainer.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> ' + response.msg + '</div>';
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en búsqueda por rol:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            
            if (resultsContainer) {
                resultsContainer.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error de conexión. Inténtalo de nuevo.</div>';
            }
        }
    });
}

/**
 * Muestra los resultados de búsqueda para asignación de roles
 */
function displaySearchResults(users) {
    const resultsContainer = document.getElementById('searchResultsContainer');
    if (!resultsContainer) return;
    
    let html = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Documento</th>
                        <th>Email</th>
                        <th>Rol Actual</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    users.forEach(user => {
        const fullName = user.first_name + ' ' + user.last_name;
        const document = user.credential_type + ' ' + user.credential_number;
        const currentRole = user.user_role ? traducirRol(user.user_role) : 'Sin rol asignado';
        
        html += `
            <tr>
                <td>${user.user_id}</td>
                <td><strong>${fullName}</strong></td>
                <td><span class="badge bg-info">${document}</span></td>
                <td>${user.email || 'No especificado'}</td>
                <td>
                    ${user.user_role ? 
                        `<span class="badge bg-success">${currentRole}</span>` : 
                        `<span class="badge bg-warning">Sin rol asignado</span>`
                    }
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary" 
                            onclick="showAssignRoleModal(${user.user_id}, '${fullName}', '${currentRole}')">
                        <i class="fas fa-user-tag"></i> Asignar Rol
                    </button>
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    resultsContainer.innerHTML = html;
    refreshIcons();
}

/**
 * Muestra los resultados de búsqueda para consulta
 */
function displayConsultResults(users) {
    const resultsContainer = document.getElementById('searchResultsContainer');
    if (!resultsContainer) return;
    
    // Obtener el rol por el que se está buscando
    const searchType = document.getElementById('search_type')?.value;
    const roleType = document.getElementById('role_type')?.value;
    
    let html = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Documento</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    users.forEach(user => {
        const fullName = user.first_name + ' ' + user.last_name;
        const document = user.credential_type + ' ' + user.credential_number;
        
        // Determinar qué rol mostrar
        let displayRole;
        if (searchType === 'role' && roleType === 'no_role') {
            // Si estamos buscando por "Sin Rol", mostrar "Sin Rol"
            displayRole = '<span class="badge bg-warning">Sin Rol</span>';
        } else if (searchType === 'role' && roleType) {
            // Si estamos buscando por un rol específico, mostrar ese rol
            const roleNames = {
                'student': 'Estudiante',
                'parent': 'Padre/Acudiente',
                'professor': 'Profesor',
                'coordinator': 'Coordinador',
                'director': 'Director/Rector',
                'treasurer': 'Tesorero',
                'root': 'Administrador'
            };
            const roleName = roleNames[roleType] || roleType;
            displayRole = `<span class="badge bg-success">${roleName}</span>`;
        } else {
            // Para búsqueda por documento, mostrar el rol actual del usuario
            const currentRole = user.user_role ? user.user_role : 'Sin rol';
            displayRole = user.user_role ? 
                `<span class="badge bg-success">${currentRole}</span>` : 
                `<span class="badge bg-warning">Sin rol</span>`;
        }
        
        html += `
            <tr>
                <td>${user.user_id}</td>
                <td><strong>${fullName}</strong></td>
                <td><span class="badge bg-info">${document}</span></td>
                <td>${user.email || 'No especificado'}</td>
                <td>${displayRole}</td>
                <td>
                    <span class="badge bg-success">Activo</span>
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    resultsContainer.innerHTML = html;
    refreshIcons();
}

/**
 * Muestra el historial de roles de un usuario
 */
function displayRoleHistory(roleHistory, userInfo) {
    const resultsContainer = document.getElementById('searchResultsContainer');
    if (!resultsContainer) return;
    
    let html = '';
    
    // Mostrar información del usuario si está disponible
    if (userInfo) {
        html += '<div class="alert alert-info mb-3">';
        html += '<strong>Usuario encontrado:</strong> ' + userInfo.first_name + ' ' + userInfo.last_name;
        html += ' (' + userInfo.credential_type + ' ' + userInfo.credential_number + ')';
        html += '</div>';
    }
    
    // Mostrar tabla de historial
    html += `
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha de Asignación</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    roleHistory.forEach(rol => {
        const roleNames = {
            'student': 'Estudiante',
            'parent': 'Padre/Acudiente',
            'professor': 'Profesor',
            'coordinator': 'Coordinador',
            'director': 'Director/Rector',
            'treasurer': 'Tesorero',
            'root': 'Administrador'
        };
        
        const roleName = roleNames[rol.role_type] || rol.role_type;
        const statusBadge = rol.is_active ? 
            '<span class="badge bg-success">Activo</span>' : 
            '<span class="badge bg-secondary">Inactivo</span>';
        
        html += `
            <tr>
                <td><strong>${roleName}</strong></td>
                <td>${statusBadge}</td>
                <td>${rol.created_at}</td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    resultsContainer.innerHTML = html;
    refreshIcons();
}

function traducirRol(rol) {
    switch (rol) {
        case 'student': return 'Estudiante';
        case 'professor': return 'Profesor';
        case 'coordinator': return 'Coordinador';
        case 'director': return 'Director';
        case 'treasurer': return 'Tesorero';
        case 'parent': return 'Acudiente';
        case 'root': return 'Administrador';
        case 'Sin rol': return 'Sin rol asignado';
        case 'Sin rol asignado': return 'Sin rol asignado';
        default: return rol;
    }
}

function showAssignRoleModal(userId, userName, currentRole) {
    console.log('Mostrando modal para usuario:', userId, userName, currentRole);
    document.getElementById('modal_user_id').value = userId;
    document.getElementById('modal_user_name').value = userName;
    document.getElementById('modal_current_role').value = traducirRol(currentRole || 'Sin rol');
    document.getElementById('modal_role_type').value = '';
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('assignRoleModal'));
    modal.show();
}

/**
 * Asigna el rol al usuario usando assignProcess.php
 */
function assignRole() {
    const userId = document.getElementById('modal_user_id').value;
    const roleType = document.getElementById('modal_role_type').value;
    
    console.log('Asignando rol:', roleType, 'al usuario:', userId);
    
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
    
    // Usar el patrón de registerFunction.js
    $.ajax({
        type: 'POST',
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        dataType: "JSON",
        data: {
            "user_id": userId,
            "role_type": roleType,
            "subject": "assign_role"
        },
        success: function(response) {
            console.log('Respuesta de asignación:', response);
            
            if (response.status === 'ok' || response.success === true) {
                // Éxito
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: response.msg || response.message,
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
                    alert(response.msg || response.message);
                    loadUsersWithoutRole();
                }
            } else {
                // Error
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: 'Error',
                        text: response.msg || response.message || 'No tienes permisos para realizar esta acción.',
                        icon: 'error'
                    });
                } else {
                    alert(response.msg || response.message || 'No tienes permisos para realizar esta acción.');
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en asignación:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            let msg = 'No se pudo conectar con el servidor. Intente nuevamente.';
            try {
                const json = JSON.parse(xhr.responseText);
                if (json && (json.msg || json.message)) {
                    msg = json.msg || json.message;
                }
            } catch (e) {}
            if (typeof Swal !== "undefined") {
                Swal.fire({
                    title: 'Error',
                    text: msg,
                    icon: 'error'
                });
            } else {
                alert(msg);
            }
        },
        complete: function() {
            // Restaurar botón
            assignBtn.innerHTML = originalText;
            assignBtn.disabled = false;
        }
    });
}

/**
 * Carga usuarios sin rol asignado usando assignProcess.php
 */
function loadUsersWithoutRole() {
    console.log('Cargando usuarios sin rol...');
    
    const tableBody = document.querySelector('#usersWithoutRoleTable tbody');
    if (!tableBody) {
        console.error('Tabla de usuarios sin rol no encontrada');
        return;
    }
    
    // Mostrar indicador de carga
    tableBody.innerHTML = '<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>';
    
    // Usar el patrón de registerFunction.js
    $.ajax({
        type: 'POST',
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        dataType: "JSON",
        data: {
            "subject": "get_users_without_role"
        },
        success: function(response) {
            console.log('Respuesta de usuarios sin rol:', response);
            
            if (response.status === 'ok') {
                displayUsersWithoutRole(response.data);
            } else {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error: ' + response.msg + '</td></tr>';
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar usuarios sin rol:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error de conexión</td></tr>';
        }
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
        const fullName = user.first_name + ' ' + user.last_name;
        const document = user.credential_type + ' ' + user.credential_number;
        
        html += `
            <tr>
                <td>${user.user_id}</td>
                <td><strong>${fullName}</strong></td>
                <td><span class="badge bg-info">${document}</span></td>
                <td>${user.email || 'No especificado'}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary" 
                            onclick="showAssignRoleModal(${user.user_id}, '${fullName}', '')">
                        <i class="fas fa-user-tag"></i> Asignar Rol
                    </button>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
    refreshIcons();
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
    
    // Ocultar resultados de búsqueda
    const resultsCard = document.getElementById('searchResultsCard');
    if (resultsCard) {
        resultsCard.style.display = 'none';
    }
}

/**
 * Limpia el formulario de historial de roles
 */
function clearRoleHistoryForm() {
    document.getElementById('credential_type').value = '';
    document.getElementById('credential_number').value = '';
    
    // Ocultar resultados de búsqueda
    const resultsCard = document.getElementById('searchResultsCard');
    if (resultsCard) {
        resultsCard.style.display = 'none';
    }
}

// --- Llamar a lucide.createIcons() después de insertar HTML dinámico ---
// Si tienes otras funciones que usan innerHTML para insertar vistas, agrega esto después:
function refreshIcons() {
    if (window.lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    }
}

// Ejemplo de uso después de actualizar contenido dinámico:
// target.innerHTML = html;
// refreshIcons();

// Si usas loadView.js u otro archivo para cargar vistas, asegúrate de llamar a refreshIcons() después de cada innerHTML.