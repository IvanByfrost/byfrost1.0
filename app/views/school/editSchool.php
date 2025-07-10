<?php
// Verificar si hay mensajes o errores
$error = $error ?? '';
$formData = $formData ?? [];
$directors = $directors ?? [];
$coordinators = $coordinators ?? [];
$school = $school ?? [];

// Si no hay datos del formulario, usar los datos de la escuela
if (empty($formData) && !empty($school)) {
    $formData = $school;
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Editar Escuela</h2>
                    <p>Modifique la información de la escuela.</p>
                </div>
                <button type="button" class="btn btn-secondary" onclick="loadView('school/consultSchool')">
                    <i class="fas fa-arrow-left"></i> Volver a la Lista
                </button>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="editSchoolForm" class="dash-form">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-edit"></i> Información de la Escuela
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="school_name">Nombre de la Escuela *</label>
                                    <input type="text" class="form-control" id="school_name" name="school_name" 
                                           value="<?php echo htmlspecialchars($formData['school_name'] ?? ''); ?>" 
                                           required>
                                    <div class="invalid-feedback">
                                        El nombre de la escuela es obligatorio.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="school_dane">Código DANE *</label>
                                    <input type="text" class="form-control" id="school_dane" name="school_dane" 
                                           value="<?php echo htmlspecialchars($formData['school_dane'] ?? ''); ?>" 
                                           required maxlength="10">
                                    <div class="invalid-feedback">
                                        El código DANE es obligatorio.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="school_document">NIT *</label>
                                    <input type="text" class="form-control" id="school_document" name="school_document" 
                                           value="<?php echo htmlspecialchars($formData['school_document'] ?? ''); ?>" 
                                           required maxlength="10">
                                    <div class="invalid-feedback">
                                        El NIT es obligatorio.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="total_quota">Cupo Total</label>
                                    <input type="number" class="form-control" id="total_quota" name="total_quota" 
                                           title="Completa este campo" onkeyup="onlyNumbers('total_quota',value);" autocomplete="off"
                                           value="<?php echo htmlspecialchars($formData['total_quota'] ?? ''); ?>" 
                                           min="0">
                                    <div class="invalid-feedback">
                                        El cupo debe ser un número válido.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="address">Dirección</label>
                                    <input type="text" class="form-control" id="address" name="address" 
                                           value="<?php echo htmlspecialchars($formData['address'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="phone">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                                    <div class="invalid-feedback">
                                        El formato del email no es válido.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label><strong>Director *</strong></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchDirectorModal">
                                            Buscar Director
                                        </button>
                                        <span id="selectedDirectorName" class="ms-2 text-success">
                                            <?php if (!empty($formData['director_name'])) echo htmlspecialchars($formData['director_name']); ?>
                                        </span>
                                    </div>
                                    <input type="hidden" id="director_user_id" name="director_user_id" value="<?php echo $formData['director_user_id'] ?? ''; ?>" required>
                                    <div class="invalid-feedback">
                                        Debe seleccionar un director.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>Coordinador</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchCoordinatorModal">
                                            Buscar Coordinador
                                        </button>
                                        <span id="selectedCoordinatorName" class="ms-2 text-success">
                                            <?php if (!empty($formData['coordinator_name'])) echo htmlspecialchars($formData['coordinator_name']); ?>
                                        </span>
                                    </div>
                                    <input type="hidden" id="coordinator_user_id" name="coordinator_user_id" value="<?php echo $formData['coordinator_user_id'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="loadView('school/consultSchool')">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
          <div class="list-group">
            <?php if (isset($directors) && count($directors) > 0): ?>
              <?php foreach ($directors as $director): ?>
                <button type="button" class="list-group-item list-group-item-action" 
                        onclick="selectDirector('<?php echo $director['user_id']; ?>', '<?php echo htmlspecialchars($director['first_name'] . ' ' . $director['last_name']); ?>')">
                  <?php echo htmlspecialchars($director['first_name'] . ' ' . $director['last_name']); ?> 
                  - <?php echo htmlspecialchars($director['email']); ?>
                </button>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="alert alert-info">No hay directores disponibles. Use la búsqueda para encontrar directores.</div>
            <?php endif; ?>
          </div>
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
          <div class="list-group">
            <?php if (isset($coordinators) && count($coordinators) > 0): ?>
              <?php foreach ($coordinators as $coordinator): ?>
                <button type="button" class="list-group-item list-group-item-action" 
                        onclick="selectCoordinator('<?php echo $coordinator['user_id']; ?>', '<?php echo htmlspecialchars($coordinator['first_name'] . ' ' . $coordinator['last_name']); ?>')">
                  <?php echo htmlspecialchars($coordinator['first_name'] . ' ' . $coordinator['last_name']); ?> 
                  - <?php echo htmlspecialchars($coordinator['email']); ?>
                </button>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="alert alert-info">No hay coordinadores disponibles. Use la búsqueda para encontrar coordinadores.</div>
            <?php endif; ?>
          </div>
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
// Selección de director
function selectDirector(userId, name) {
    document.getElementById('director_user_id').value = userId;
    document.getElementById('selectedDirectorName').textContent = name;
    var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('searchDirectorModal'));
    modal.hide();
}
// Selección de coordinador
function selectCoordinator(userId, name) {
    document.getElementById('coordinator_user_id').value = userId;
    document.getElementById('selectedCoordinatorName').textContent = name;
    var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('searchCoordinatorModal'));
    modal.hide();
}
// Búsqueda AJAX para director
const searchDirectorForm = document.getElementById('searchDirectorForm');
if (searchDirectorForm) {
    searchDirectorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = document.getElementById('search_director_query').value.trim();
        if (!query) return;
        fetch('ruta_a_tu_endpoint_de_busqueda_director.php?query=' + encodeURIComponent(query))
            .then(res => res.text())
            .then(html => {
                document.getElementById('searchDirectorResults').innerHTML = html;
            });
    });
}
// Búsqueda AJAX para coordinador
const searchCoordinatorForm = document.getElementById('searchCoordinatorForm');
if (searchCoordinatorForm) {
    searchCoordinatorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = document.getElementById('search_coordinator_query').value.trim();
        if (!query) return;
        fetch('ruta_a_tu_endpoint_de_busqueda_coordinador.php?query=' + encodeURIComponent(query))
            .then(res => res.text())
            .then(html => {
                document.getElementById('searchCoordinatorResults').innerHTML = html;
            });
    });
}
</script>
