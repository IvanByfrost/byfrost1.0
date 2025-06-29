<?php
// Test para diagnosticar el problema con el modal de crear escuela
require_once '../config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Modal Crear Escuela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Test Modal Crear Escuela</h2>
        
        <form method="POST" id="createSchool" class="dash-form">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Crear Nueva Escuela</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="school_name">Nombre de la Escuela *</label>
                                <input type="text" class="form-control" id="school_name" name="school_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="school_dane">Código DANE *</label>
                                <input type="text" class="form-control" id="school_dane" name="school_dane" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="school_document">NIT *</label>
                                <input type="text" class="form-control" id="school_document" name="school_document" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_quota">Cupo Total</label>
                                <input type="number" class="form-control" id="total_quota" name="total_quota" min="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="director_name" name="director_name" readonly placeholder="Seleccionar Director">
                                <input type="hidden" id="director_user_id" name="director_user_id">
                                <button type="button" class="btn btn-primary">
                                    Buscar Director
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="coordinator_user_id">Coordinador</label>
                                <select class="form-control" id="coordinator_user_id" name="coordinator_user_id">
                                    <option value="">Seleccionar Coordinador</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#completeSchoolModal">
                            <i class="fas fa-save"></i> Crear Escuela
                        </button>
                        <button type="button" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

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
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="departamento">Departamento:</label>
                    <input type="text" class="form-control" id="departamento" name="departamento" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="municipio">Municipio / Ciudad:</label>
                    <input type="text" class="form-control" id="municipio" name="municipio" required>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="direccion">Dirección:</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="telefono">Teléfono de Contacto:</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" required>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Definir BASE_URL para el test
        const BASE_URL = '<?php echo url; ?>';
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado');
            
            const form = document.getElementById('createSchool');
            const completeForm = document.getElementById('completeSchoolForm');
            const createSchoolBtn = document.querySelector('button[data-bs-target="#completeSchoolModal"]');
            
            console.log('Formulario principal:', form);
            console.log('Formulario modal:', completeForm);
            console.log('Botón crear escuela:', createSchoolBtn);
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Formulario principal submit prevenido');
                });
            }
            
            // Validar formulario principal antes de abrir el modal
            if (createSchoolBtn) {
                createSchoolBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Botón crear escuela clickeado');
                    
                    // Validar que el formulario principal esté completo
                    const mainFormErrors = validateRequiredFields();
                    console.log('Errores del formulario principal:', mainFormErrors);
                    
                    if (mainFormErrors.length > 0) {
                        alert('Por favor complete todos los campos obligatorios del formulario principal antes de continuar.');
                        return;
                    }
                    
                    console.log('Formulario principal válido, abriendo modal');
                    // Si todo está correcto, abrir el modal
                    const modal = new bootstrap.Modal(document.getElementById('completeSchoolModal'));
                    modal.show();
                });
            }
            
            // Función para validar campos requeridos del formulario principal
            function validateRequiredFields() {
                const requiredFields = ['school_name', 'school_dane', 'school_document'];
                const errors = [];
                
                // Validar campos de texto requeridos
                requiredFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    console.log('Validando campo:', fieldName, 'valor:', field ? field.value : 'campo no encontrado');
                    if (field && !field.value.trim()) {
                        errors.push(fieldName);
                        field.classList.add('is-invalid');
                    } else if (field) {
                        field.classList.remove('is-invalid');
                    }
                });
                
                // Validar que se haya seleccionado un director
                const directorUserId = document.getElementById('director_user_id');
                const directorName = document.getElementById('director_name');
                console.log('Validando director - ID:', directorUserId ? directorUserId.value : 'no encontrado', 'Nombre:', directorName ? directorName.value : 'no encontrado');
                
                if (directorUserId && (!directorUserId.value.trim() || !directorName.value.trim())) {
                    errors.push('director_user_id');
                    directorName.classList.add('is-invalid');
                } else if (directorName) {
                    directorName.classList.remove('is-invalid');
                }
                
                return errors;
            }
            
            // Manejar el envío del formulario completo desde el modal
            if (completeForm) {
                completeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Formulario modal submit');
                    
                    // Validar campos del modal
                    const modalFormErrors = validateModalFields();
                    if (modalFormErrors.length > 0) {
                        alert('Por favor complete todos los campos de ubicación.');
                        return;
                    }
                    
                    console.log('Enviando datos...');
                    // Aquí iría el envío de datos
                    alert('Datos válidos, se enviarían al servidor');
                });
            }
            
            // Función para validar campos del modal
            function validateModalFields() {
                const requiredModalFields = ['departamento', 'municipio', 'direccion', 'telefono', 'correo'];
                const errors = [];
                
                requiredModalFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (field && !field.value.trim()) {
                        errors.push(fieldName);
                        field.classList.add('is-invalid');
                    } else if (field) {
                        field.classList.remove('is-invalid');
                    }
                });
                
                return errors;
            }
        });
    </script>
</body>
</html> 