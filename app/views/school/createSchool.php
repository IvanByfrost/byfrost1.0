<?php
// Verificar si hay mensajes o errores
$error = $error ?? '';
$formData = $formData ?? [];
$directors = $directors ?? [];
$coordinators = $coordinators ?? [];
?>

<form method="POST" id="createSchool" class="dash-form">
<div class="card-body">
            <h2>Crear Nueva Escuela</h2>
            <p>Complete la información para crear una nueva escuela.</p>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            <div class="row g-3">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="school_name">Nombre de la Escuela *</label>
                        <input type="text" class="form-control" id="school_name" name="school_name" 
                               value="<?php echo isset($formData['school_name']) ? htmlspecialchars($formData['school_name']) : ''; ?>" 
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
                               value="<?php echo isset($formData['school_dane']) ? htmlspecialchars($formData['school_dane']) : ''; ?>" 
                               required>
                        <div class="invalid-feedback">
                            El código DANE es obligatorio.
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="school_document">NIT *</label>
                        <input type="text" class="form-control" id="school_document" name="school_document" 
                               value="<?php echo isset($formData['school_document']) ? htmlspecialchars($formData['school_document']) : ''; ?>" 
                               required>
                        <div class="invalid-feedback">
                            El NIT es obligatorio.
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="total_quota">Cupo Total</label>
                        <input type="number" class="form-control" id="total_quota" name="total_quota" title="Only Numbers" onkeyup="onlyNumbers('total_quota',value);" autocomplete="off"
                               value="<?php echo isset($formData['total_quota']) ? htmlspecialchars($formData['total_quota']) : ''; ?>" 
                               min="0">
                               <div class="invalid-feedback">
                            El cupo es obligatorio.
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="director_name" name="director_name" readonly placeholder="Seleccionar Director">
                        <input type="hidden" id="director_user_id" name="director_user_id">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchDirectorModal">
                            Buscar Director
                        </button>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="coordinator_name" name="coordinator_name" readonly placeholder="Seleccionar Coordinador">
                        <input type="hidden" id="coordinator_user_id" name="coordinator_user_id">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchCoordinatorModal">
                            Buscar Coordinador
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-actions mt-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#completeSchoolModal">
                    <i class="fas fa-save"></i> Crear Escuela
                </button>
                <button type="button" class="btn btn-secondary" onclick="cancelCreateSchool()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="clearCreateSchoolForm()">
                    <i class="fas fa-eraser"></i> Limpiar
                </button>
            </div>
        </div>
</form>

<!-- Modal para completar registro de escuela -->
<div class="modal fade" id="completeSchoolModal" tabindex="-1" aria-labelledby="completeSchoolModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="completeSchoolModalLabel">📍 Completar Ubicación de la Escuela</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="completeSchoolForm">
          <div class="row g-3">
            <div class="col-md-6 col-12">
              <div class="form-group mb-3">
                <label for="departamento">Departamento:</label>
                <input type="text" class="form-control" id="departamento" name="departamento" required>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group mb-3">
                <label for="municipio">Municipio / Ciudad:</label>
                <input type="text" class="form-control" id="municipio" name="municipio" required>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group mb-3">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group mb-3">
                <label for="telefono">Teléfono de Contacto:</label>
                <input type="tel" class="form-control" id="telefono" name="telefono" required>
              </div>
            </div>
            <div class="col-md-6 col-12">
              <div class="form-group mb-3">
                <label for="correo">Correo Electrónico Institucional:</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Registrar Colegio
            </button>
          </div>
        </form>
      </div>
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
          <div class="input-group">
            <input type="text" class="form-control" id="search_director_query" placeholder="Nombre o número de documento">
            <button type="submit" class="btn btn-primary">Buscar</button>
          </div>
        </form>
        <div id="searchDirectorResults"></div>
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
        <div class="list-group">
          <?php if (isset($coordinators) && count($coordinators) > 0): ?>
            <?php foreach ($coordinators as $coordinator): ?>
              <button type="button" class="list-group-item list-group-item-action" onclick="selectCoordinator('<?php echo $coordinator['user_id']; ?>', '<?php echo htmlspecialchars($coordinator['name'] . ' ' . $coordinator['lastname']); ?>')">
                <?php echo htmlspecialchars($coordinator['name'] . ' ' . $coordinator['lastname']); ?>
              </button>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="alert alert-info">No hay coordinadores disponibles.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Incluir el archivo JavaScript -->
<script>
    const url = '<?php echo url; ?>';
</script>
<script src="<?php echo url; ?>app/resources/js/createSchool.js"></script>
<script>
function selectCoordinator(id, name) {
    document.getElementById('coordinator_user_id').value = id;
    document.getElementById('coordinator_name').value = name;
    var modal = bootstrap.Modal.getInstance(document.getElementById('searchCoordinatorModal'));
    if (modal) { modal.hide(); }
}
</script>