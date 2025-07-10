/**
 * Módulo de historial de roles
 */

// Buscar historial de roles
function searchRoleHistory(credentialType, credentialNumber) {
    console.log('Buscando historial de roles para:', credentialType, credentialNumber);
    
    $.ajax({
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        method: 'POST',
        data: {
            subject: 'search_role_history',
            credential_type: credentialType,
            credential_number: credentialNumber
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.status === 'ok') {
                    displayRoleHistory(data.data.role_history, data.data.user_info);
                } else {
                    showError(data.msg || 'Error en la búsqueda del historial');
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

// Mostrar historial de roles
function displayRoleHistory(roleHistory, userInfo) {
    const resultsContainer = document.getElementById('roleHistoryResults');
    if (!resultsContainer) {
        console.error('Contenedor de resultados de historial no encontrado');
        return;
    }
    
    if (!userInfo) {
        resultsContainer.innerHTML = '<div class="alert alert-warning">No se encontró información del usuario.</div>';
        return;
    }
    
    let html = '<div class="card mb-3">';
    html += '<div class="card-header"><h5>Información del Usuario</h5></div>';
    html += '<div class="card-body">';
    html += `<p><strong>Nombre:</strong> ${userInfo.first_name} ${userInfo.last_name}</p>`;
    html += `<p><strong>Email:</strong> ${userInfo.email}</p>`;
    html += `<p><strong>Documento:</strong> ${userInfo.credential_type} ${userInfo.credential_number}</p>`;
    html += '</div></div>';
    
    if (!roleHistory || roleHistory.length === 0) {
        html += '<div class="alert alert-info">No se encontró historial de roles para este usuario.</div>';
    } else {
        html += '<div class="card">';
        html += '<div class="card-header"><h5>Historial de Roles</h5></div>';
        html += '<div class="card-body">';
        html += '<div class="table-responsive"><table class="table table-striped">';
        html += '<thead><tr><th>Rol</th><th>Fecha de Asignación</th><th>Asignado por</th></tr></thead><tbody>';
        
        roleHistory.forEach(role => {
            const fecha = new Date(role.assigned_at).toLocaleDateString('es-ES');
            html += `<tr>
                <td><span class="badge bg-primary">${traducirRol(role.role_type)}</span></td>
                <td>${fecha}</td>
                <td>${role.assigned_by_name || 'Sistema'}</td>
            </tr>`;
        });
        
        html += '</tbody></table></div>';
        html += '</div></div>';
    }
    
    resultsContainer.innerHTML = html;
} 