/**
 * Módulo de gestión de roles
 */

// Mostrar modal de asignación de roles
function showAssignRoleModal(userId, userName, currentRole) {
    console.log('Mostrando modal para asignar rol a:', userName);
    
    const modal = document.getElementById('assignRoleModal');
    const modalTitle = document.getElementById('assignRoleModalLabel');
    const roleSelect = document.getElementById('role_type');
    const userIdInput = document.getElementById('user_id');
    
    if (!modal || !modalTitle || !roleSelect || !userIdInput) {
        console.error('Elementos del modal no encontrados');
        return;
    }
    
    // Configurar modal
    modalTitle.textContent = `Asignar Rol a ${userName}`;
    userIdInput.value = userId;
    
    // Limpiar selección anterior
    roleSelect.value = '';
    
    // Mostrar modal
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

// Asignar rol a usuario
function assignRole() {
    const userId = document.getElementById('user_id').value;
    const roleType = document.getElementById('role_type').value;
    
    if (!userId || !roleType) {
        showError('Por favor, selecciona un rol para asignar.');
        return;
    }
    
    console.log('Asignando rol:', roleType, 'a usuario:', userId);
    
    $.ajax({
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        method: 'POST',
        data: {
            subject: 'assign_role',
            user_id: userId,
            role_type: roleType
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.status === 'ok') {
                    // Cerrar modal
                    const modal = document.getElementById('assignRoleModal');
                    const bootstrapModal = bootstrap.Modal.getInstance(modal);
                    bootstrapModal.hide();
                    
                    // Mostrar mensaje de éxito
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'Rol asignado correctamente',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        alert('Rol asignado correctamente');
                    }
                    
                    // Recargar lista de usuarios sin rol
                    loadUsersWithoutRole();
                    
                } else {
                    showError(data.msg || 'Error al asignar el rol');
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

// Cargar usuarios sin rol
function loadUsersWithoutRole() {
    console.log('Cargando usuarios sin rol...');
    
    $.ajax({
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        method: 'POST',
        data: {
            subject: 'get_users_without_role'
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.status === 'ok') {
                    displayUsersWithoutRole(data.data);
                } else {
                    showError(data.msg || 'Error cargando usuarios');
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

// Mostrar usuarios sin rol
function displayUsersWithoutRole(users) {
    const table = document.getElementById('usersWithoutRoleTable');
    if (!table) {
        console.error('Tabla de usuarios sin rol no encontrada');
        return;
    }
    
    const tbody = table.querySelector('tbody');
    if (!tbody) {
        console.error('Cuerpo de tabla no encontrado');
        return;
    }
    
    if (!users || users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay usuarios sin rol asignado</td></tr>';
        return;
    }
    
    let html = '';
    users.forEach(user => {
        html += `<tr>
            <td>${user.first_name} ${user.last_name}</td>
            <td>${user.email}</td>
            <td>${user.credential_type} ${user.credential_number}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="showAssignRoleModal(${user.user_id}, '${user.first_name} ${user.last_name}', '')">
                    <i class="fas fa-user-plus"></i> Asignar Rol
                </button>
            </td>
        </tr>`;
    });
    
    tbody.innerHTML = html;
}

// Recargar usuarios sin rol
function refreshUsersWithoutRole() {
    loadUsersWithoutRole();
}

// Limpiar formulario de historial de roles
function clearRoleHistoryForm() {
    const form = document.getElementById('roleHistoryForm');
    if (form) {
        form.reset();
    }
    
    const resultsContainer = document.getElementById('roleHistoryResults');
    if (resultsContainer) {
        resultsContainer.innerHTML = '';
    }
} 