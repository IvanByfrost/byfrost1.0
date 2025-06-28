<?php
// Verificar si hay mensajes o errores
$error = $error ?? '';
$formData = $formData ?? [];
$directors = $directors ?? [];
$coordinators = $coordinators ?? [];
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Crear Nueva Escuela</h2>
            <p>Complete la información para crear una nueva escuela.</p>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" id="createSchool" class="dash-form">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Crear Nueva Escuela</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_quota">Cupo Total</label>
                                    <input type="number" class="form-control" id="total_quota" name="total_quota" 
                                           value="<?php echo isset($formData['total_quota']) ? htmlspecialchars($formData['total_quota']) : ''; ?>" 
                                           min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="director_user_id">Director</label>
                                    <select class="form-control" id="director_user_id" name="director_user_id">
                                        <option value="">Seleccionar Director</option>
                                        <?php if (isset($directors)): ?>
                                            <?php foreach ($directors as $director): ?>
                                                <option value="<?php echo $director['user_id']; ?>" 
                                                        <?php echo (isset($formData['director_user_id']) && $formData['director_user_id'] == $director['user_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($director['name'] . ' ' . $director['lastname']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="coordinator_user_id">Coordinador</label>
                                    <select class="form-control" id="coordinator_user_id" name="coordinator_user_id">
                                        <option value="">Seleccionar Coordinador</option>
                                        <?php if (isset($coordinators)): ?>
                                            <?php foreach ($coordinators as $coordinator): ?>
                                                <option value="<?php echo $coordinator['user_id']; ?>" 
                                                        <?php echo (isset($formData['coordinator_user_id']) && $formData['coordinator_user_id'] == $coordinator['user_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($coordinator['name'] . ' ' . $coordinator['lastname']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Dirección</label>
                                    <input type="text" class="form-control" id="address" name="address" 
                                           value="<?php echo isset($formData['address']) ? htmlspecialchars($formData['address']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Teléfono</label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="<?php echo isset($formData['phone']) ? htmlspecialchars($formData['phone']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>">
                                    <div class="invalid-feedback">
                                        Ingrese un email válido.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
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
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Incluir el archivo JavaScript -->
<script src="<?php echo url; ?>app/resources/js/createSchool.js"></script>