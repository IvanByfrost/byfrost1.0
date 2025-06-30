/**
 * Búsqueda flexible de usuarios por rol, documento o ambos.
 * @param {string} roleType - El tipo de rol a buscar (ej: 'director', 'coordinator', etc.)
 * @param {string} query - El criterio de búsqueda (nombre, documento, etc.)
 * @param {string} resultsContainerId - El id del contenedor donde mostrar los resultados
 * @param {string} selectCallback - El nombre de la función a llamar al seleccionar un usuario
 * @param {string|null} searchType - 'document' para búsqueda exacta por documento, 'role' para solo rol, null para búsqueda flexible
 */
window.searchUsersForModal = function(roleType, query, resultsContainerId, selectCallback, searchType = null) {
    const resultsContainer = document.getElementById(resultsContainerId);
    if (resultsContainer) {
        resultsContainer.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>';
    }

    let subject = 'search_users_by_role';
    if (searchType === 'document') {
        subject = 'search_users_by_document';
    }

    const data = {
        "role_type": roleType,
        "query": query,
        "subject": subject
    };
    if (searchType) {
        data.search_type = searchType;
    }

    $.ajax({
        type: 'POST',
        url: window.USER_MANAGEMENT_BASE_URL + 'app/processes/assignProcess.php',
        dataType: "JSON",
        data: data,
        success: function(response) {
            if (response.status === 'ok' && response.data && response.data.length > 0) {
                resultsContainer.innerHTML = `<div class="list-group">` +
                    response.data.map(user => `
                        <button type="button" class="list-group-item list-group-item-action"
                            onclick="${selectCallback}('${user.user_id}', '${user.first_name} ${user.last_name}')">
                            ${user.first_name} ${user.last_name} - ${user.email}
                        </button>
                    `).join('') +
                    `</div>`;
            } else {
                resultsContainer.innerHTML = `<div class="alert alert-warning"><i class="fas fa-search"></i> ${response.msg}</div>`;
            }
        },
        error: function(xhr, status, error) {
            resultsContainer.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error de conexión. Inténtalo de nuevo.</div>';
        }
    });
}

// Delegación de eventos global para formularios de búsqueda de director y coordinador
if (!window._delegatedSearchListeners) {
    document.body.addEventListener('submit', function(e) {
        if (e.target && e.target.id === 'searchDirectorForm') {
            e.preventDefault();
            const query = document.getElementById('search_director_query').value.trim();
            if (!query) {
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor ingrese un término de búsqueda',
                        icon: 'warning'
                    });
                } else {
                    alert('Por favor ingrese un término de búsqueda');
                }
                return;
            }
            searchUsersForModal('director', query, 'searchDirectorResults', 'selectDirector', 'document');
        }
        if (e.target && e.target.id === 'searchCoordinatorForm') {
            e.preventDefault();
            const query = document.getElementById('search_coordinator_query').value.trim();
            if (!query) {
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor ingrese un término de búsqueda',
                        icon: 'warning'
                    });
                } else {
                    alert('Por favor ingrese un término de búsqueda');
                }
                return;
            }
            searchUsersForModal('coordinator', query, 'searchCoordinatorResults', 'selectCoordinator', 'document');
        }
    });
    window._delegatedSearchListeners = true;
}