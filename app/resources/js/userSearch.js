/**
 * Módulo de búsqueda de usuarios
 */

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