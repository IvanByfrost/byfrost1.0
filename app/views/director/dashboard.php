<?php
// Dashboard del Director - Versión Modular y Simplificada
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y sea director
if (!$sessionManager->isLoggedIn() || !$sessionManager->hasRole('director')) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

require_once ROOT . '/app/views/layouts/dashHeader.php';
?>
<link rel="stylesheet" href="<?= url . app . rq ?>css/dashboard.css">
<div class="dashboard-container">
    <aside class="sidebar">
        <?php require_once __DIR__ . '/directorSidebar.php'; ?>
    </aside>
    <main class="mainContent" id="mainContent">
        <?php require_once __DIR__ . '/dashboardHome.php'; ?>
    </main>
</div>

<!-- Modal para buscar director -->
<div class="modal fade" id="searchDirectorModal" tabindex="-1" aria-labelledby="searchDirectorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="searchDirectorModalLabel">Buscar Director</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="searchDirectorForm" class="mb-3" autocomplete="off" novalidate>
          <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>
          <div class="input-group">
            <input type="text" class="form-control w-100" id="search_director_query" placeholder="Número de documento" title="Completa este campo" autocomplete="off">
            <button type="submit" class="btn btn-primary">Buscar</button>
          </div>
        </form>
        <div id="searchDirectorResults">
          <div class="alert alert-info">Ingresa un número de documento para buscar directores.</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal para buscar coordinador -->
<div class="modal fade" id="searchCoordinatorModal" tabindex="-1" aria-labelledby="searchCoordinatorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="searchCoordinatorModalLabel">Buscar Coordinador</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="searchCoordinatorForm" class="mb-3" autocomplete="off">
          <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>
          <div class="input-group">
            <input type="text" class="form-control w-100" id="search_coordinator_query" placeholder="Número de documento" onkeyup="onlyNumbers('search_coordinator_query',value);" autocomplete="off">
            <button type="submit" class="btn btn-primary">Buscar</button>
          </div>
        </form>
        <div id="searchCoordinatorResults">
          <div class="alert alert-info">Ingresa un número de documento para buscar coordinadores.</div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
#searchDirectorResults .list-group, #searchCoordinatorResults .list-group {
    max-height: 300px;
    overflow-y: auto;
}
</style>

<script>
// Funciones de selección
function selectDirector(userId, name) {
    // Aquí puedes manejar la selección del director
    console.log('Director seleccionado:', userId, name);
    var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('searchDirectorModal'));
    modal.hide();
    
    // Mostrar mensaje de éxito
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Director Seleccionado',
            text: name,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } else {
        alert('Director seleccionado: ' + name);
    }
}

function selectCoordinator(userId, name) {
    // Aquí puedes manejar la selección del coordinador
    console.log('Coordinador seleccionado:', userId, name);
    var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('searchCoordinatorModal'));
    modal.hide();
    
    // Mostrar mensaje de éxito
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Coordinador Seleccionado',
            text: name,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } else {
        alert('Coordinador seleccionado: ' + name);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DEBUG: Inicializando formulario de búsqueda en dashboard director ===');
    
    // Búsqueda AJAX de director
    const directorForm = document.getElementById('searchDirectorForm');
    if (directorForm) {
        console.log('DEBUG: Formulario de director encontrado en dashboard');
        
        directorForm.addEventListener('submit', function(e) {
            console.log('DEBUG: Evento submit de director disparado en dashboard');
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            const queryInput = document.getElementById('search_director_query');
            const query = queryInput.value.trim();
            
            console.log('DEBUG: Query de director en dashboard:', query);
            
            // Validación adicional
            if (!query || query.length === 0) {
                console.log('DEBUG: Input vacío detectado en dashboard');
                const resultsDiv = document.getElementById('searchDirectorResults');
                resultsDiv.innerHTML = '<div class="alert alert-warning">Por favor, ingrese un número de documento para buscar.</div>';
                queryInput.focus();
                return false;
            }
            
            // Validar que sea solo números
            if (!/^\d+$/.test(query)) {
                console.log('DEBUG: Caracteres no numéricos detectados en dashboard');
                const resultsDiv = document.getElementById('searchDirectorResults');
                resultsDiv.innerHTML = '<div class="alert alert-warning">Por favor, ingrese solo números para el documento.</div>';
                queryInput.focus();
                return false;
            }
            
            console.log('DEBUG: Iniciando búsqueda de director en dashboard');
            const resultsDiv = document.getElementById('searchDirectorResults');
            resultsDiv.innerHTML = '<div class="alert alert-info">Buscando...</div>';
            
            fetch('app/processes/assignProcess.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'subject=search_users_by_role&role_type=director&search_type=document&query=' + encodeURIComponent(query)
            })
            .then(response => {
                console.log('DEBUG: Respuesta HTTP en dashboard:', response.status);
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta de búsqueda de director en dashboard:', data);
                
                if (data.status === 'ok' && data.data && data.data.length > 0) {
                    resultsDiv.innerHTML = data.data.map(director =>
                        `<button type="button" class="list-group-item list-group-item-action" 
                            onclick="selectDirector('${director.user_id}', '${director.first_name} ${director.last_name}')">
                            ${director.first_name} ${director.last_name} - ${director.email}
                        </button>`
                    ).join('');
                } else if (data.status === 'error') {
                    resultsDiv.innerHTML = `<div class="alert alert-danger">${data.msg || 'Error al buscar directores.'}</div>`;
                } else {
                    resultsDiv.innerHTML = '<div class="alert alert-warning">No se encontraron directores con ese documento.</div>';
                }
            })
            .catch(error => {
                console.error('Error en búsqueda de director en dashboard:', error);
                resultsDiv.innerHTML = '<div class="alert alert-danger">Error de conexión al buscar directores. Intente nuevamente.</div>';
            });
            
            return false;
        });
        
        // Prevenir envío del formulario al presionar Enter en el input
        const directorInput = document.getElementById('search_director_query');
        if (directorInput) {
            directorInput.addEventListener('keypress', function(e) {
                console.log('DEBUG: Keypress en input de director en dashboard:', e.key);
                if (e.key === 'Enter') {
                    console.log('DEBUG: Enter presionado en input de director en dashboard');
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    directorForm.dispatchEvent(new Event('submit'));
                }
            });
        }
    }
    
    console.log('DEBUG: Formulario de búsqueda en dashboard director inicializado');
});

// Debug y verificación
console.log('Dashboard del director cargado correctamente');
</script>


