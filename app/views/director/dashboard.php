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
        <form id="searchDirectorForm" class="mb-3" autocomplete="off">
          <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>
          <div class="input-group">
            <input type="text" class="form-control w-100" id="search_director_query" placeholder="Número de documento">
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
            <input type="text" class="form-control w-100" id="search_coordinator_query" placeholder="Número de documento">
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
    // Búsqueda AJAX de director
    const directorForm = document.getElementById('searchDirectorForm');
    if (directorForm) {
        directorForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = document.getElementById('search_director_query').value.trim();
            if (!query) return;
            
            const resultsDiv = document.getElementById('searchDirectorResults');
            resultsDiv.innerHTML = '<div class="alert alert-info">Buscando...</div>';
            
            fetch('app/processes/assignProcess.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'subject=search_users_by_role&role_type=director&search_type=document&query=' + encodeURIComponent(query)
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'ok' && data.data && data.data.length > 0) {
                    resultsDiv.innerHTML = data.data.map(director =>
                        `<button type="button" class="list-group-item list-group-item-action" 
                            onclick="selectDirector('${director.user_id}', '${director.first_name} ${director.last_name}')">
                            ${director.first_name} ${director.last_name} - ${director.email}
                        </button>`
                    ).join('');
                } else {
                    resultsDiv.innerHTML = '<div class="alert alert-warning">No se encontraron directores con ese documento.</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultsDiv.innerHTML = '<div class="alert alert-danger">Error al buscar directores.</div>';
            });
        });
    }
    
    // Búsqueda AJAX de coordinador
    const coordinatorForm = document.getElementById('searchCoordinatorForm');
    if (coordinatorForm) {
        coordinatorForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = document.getElementById('search_coordinator_query').value.trim();
            if (!query) return;
            
            const resultsDiv = document.getElementById('searchCoordinatorResults');
            resultsDiv.innerHTML = '<div class="alert alert-info">Buscando...</div>';
            
            fetch('app/processes/assignProcess.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'subject=search_users_by_role&role_type=coordinator&search_type=document&query=' + encodeURIComponent(query)
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'ok' && data.data && data.data.length > 0) {
                    resultsDiv.innerHTML = data.data.map(coordinator =>
                        `<button type="button" class="list-group-item list-group-item-action" 
                            onclick="selectCoordinator('${coordinator.user_id}', '${coordinator.first_name} ${coordinator.last_name}')">
                            ${coordinator.first_name} ${coordinator.last_name} - ${coordinator.email}
                        </button>`
                    ).join('');
                } else {
                    resultsDiv.innerHTML = '<div class="alert alert-warning">No se encontraron coordinadores con ese documento.</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultsDiv.innerHTML = '<div class="alert alert-danger">Error al buscar coordinadores.</div>';
            });
        });
    }
});

// Debug y verificación
console.log('Dashboard del director cargado correctamente');
</script>


