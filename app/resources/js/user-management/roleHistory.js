/**
 * Módulo de historial de roles
 */

/**
 * Lanza una búsqueda de historial de roles por documento.
 * @param {string} credentialType
 * @param {string} credentialNumber
 */
async function searchRoleHistory(credentialType, credentialNumber) {
    console.log('[RoleHistory] Buscando historial de roles para:', credentialType, credentialNumber);
  
    const body = new URLSearchParams({
      subject: 'search_role_history',
      credential_type: credentialType,
      credential_number: credentialNumber
    });
  
    try {
      const res = await fetch(`${window.USER_MANAGEMENT_BASE_URL}app/processes/assignProcess.php`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: body
      });
  
      if (!res.ok) {
        throw new Error(`HTTP error: ${res.status}`);
      }
  
      const data = await res.json();
      console.log('[RoleHistory] Respuesta:', data);
  
      if (data.status === 'ok') {
        displayRoleHistory(data.data.role_history, data.data.user_info);
      } else {
        showError(data.msg || 'Error en la búsqueda del historial');
      }
    } catch (e) {
      console.error('[RoleHistory] Error:', e);
      showError('Error de conexión con el servidor');
    }
  }
  
  /**
   * Renderiza el historial de roles en el contenedor.
   * @param {Array} roleHistory
   * @param {Object|null} userInfo
   */
  function displayRoleHistory(roleHistory, userInfo) {
    const resultsContainer = document.getElementById('roleHistoryResults');
    if (!resultsContainer) {
      console.error('[RoleHistory] Contenedor de resultados no encontrado');
      return;
    }
  
    let html = '';
  
    if (!userInfo) {
      html = `<div class="alert alert-warning">
        No se encontró información del usuario.
      </div>`;
      resultsContainer.innerHTML = html;
      return;
    }
  
    html += `
      <div class="card mb-3">
        <div class="card-header"><h5>Información del Usuario</h5></div>
        <div class="card-body">
          <p><strong>Nombre:</strong> ${userInfo.first_name} ${userInfo.last_name}</p>
          <p><strong>Email:</strong> ${userInfo.email}</p>
          <p><strong>Documento:</strong> ${userInfo.credential_type} ${userInfo.credential_number}</p>
        </div>
      </div>`;
  
    if (!roleHistory || roleHistory.length === 0) {
      html += `<div class="alert alert-info">
        No se encontró historial de roles para este usuario.
      </div>`;
    } else {
      html += `
        <div class="card">
          <div class="card-header"><h5>Historial de Roles</h5></div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Rol</th>
                    <th>Fecha de Asignación</th>
                    <th>Asignado por</th>
                  </tr>
                </thead>
                <tbody>
                  ${roleHistory.map(role => {
                    const fecha = role.assigned_at
                      ? new Date(role.assigned_at).toLocaleDateString('es-ES')
                      : 'N/A';
                    return `
                      <tr>
                        <td><span class="badge bg-primary">${traducirRol(role.role_type)}</span></td>
                        <td>${fecha}</td>
                        <td>${role.assigned_by_name || 'Sistema'}</td>
                      </tr>`;
                  }).join('')}
                </tbody>
              </table>
            </div>
          </div>
        </div>`;
    }
  
    resultsContainer.innerHTML = html;
    console.log('[RoleHistory] HTML generado para historial de roles');
  }
  
  /**
   * Muestra un error con SweetAlert o alert.
   * @param {string} message
   */
  function showError(message) {
    console.error('[RoleHistory] Error:', message);
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Error',
        text: message,
        icon: 'error',
        confirmButtonText: 'OK'
      });
    } else {
      alert(message);
    }
  }
  