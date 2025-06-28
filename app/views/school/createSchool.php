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
                        <h5 class="mb-0"><i class="fas fa-school"></i> Información General</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Nombre de la escuela -->
                            <div class="col-md-6 mb-3">
                                <label for="school_name" class="form-label">Nombre de la Escuela *</label>
                                <input type="text" id="school_name" name="school_name" 
                                       class="form-control" 
                                       placeholder="Ej: Colegio San José - Sede Norte"
                                       value="<?php echo htmlspecialchars($formData['school_name'] ?? ''); ?>"
                                       required>
                            </div>
                            
                            <!-- Código DANE -->
                            <div class="col-md-6 mb-3">
                                <label for="school_dane" class="form-label">Código DANE *</label>
                                <input type="text" id="school_dane" name="school_dane" 
                                       class="form-control" 
                                       placeholder="Ej: 11100123456"
                                       value="<?php echo htmlspecialchars($formData['school_dane'] ?? ''); ?>"
                                       maxlength="12"
                                       required>
                            </div>
                            
                            <!-- NIT -->
                            <div class="col-md-6 mb-3">
                                <label for="school_document" class="form-label">NIT *</label>
                                <input type="text" id="school_document" name="school_document" 
                                       class="form-control" 
                                       placeholder="Ej: 900123456-7"
                                       value="<?php echo htmlspecialchars($formData['school_document'] ?? ''); ?>"
                                       maxlength="15"
                                       required>
                            </div>
                            
                            <!-- Cupo total -->
                            <div class="col-md-6 mb-3">
                                <label for="total_quota" class="form-label">Cupo Total</label>
                                <input type="number" id="total_quota" name="total_quota" 
                                       class="form-control" 
                                       placeholder="Ej: 500"
                                       value="<?php echo htmlspecialchars($formData['total_quota'] ?? ''); ?>"
                                       min="0">
                            </div>
                            
                            <!-- Dirección -->
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">Dirección</label>
                                <input type="text" id="address" name="address" 
                                       class="form-control" 
                                       placeholder="Ej: Calle 123 # 45-67, Barrio Centro"
                                       value="<?php echo htmlspecialchars($formData['address'] ?? ''); ?>">
                            </div>
                            
                            <!-- Teléfono -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input type="tel" id="phone" name="phone" 
                                       class="form-control" 
                                       placeholder="Ej: (1) 2345678"
                                       value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>">
                            </div>
                            
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" 
                                       class="form-control" 
                                       placeholder="Ej: info@colegio.edu.co"
                                       value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                            </div>
                            
                            <!-- Director -->
                            <div class="col-md-6 mb-3">
                                <label for="director_user_id" class="form-label">Director</label>
                                <select id="director_user_id" name="director_user_id" class="form-select">
                                    <option value="">Seleccione un director</option>
                                    <?php foreach ($directors as $director): ?>
                                        <option value="<?php echo $director['user_id']; ?>"
                                                <?php echo ($formData['director_user_id'] ?? '') == $director['user_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($director['first_name'] . ' ' . $director['last_name']); ?>
                                            (<?php echo htmlspecialchars($director['email']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <!-- Coordinador -->
                            <div class="col-md-6 mb-3">
                                <label for="coordinator_user_id" class="form-label">Coordinador</label>
                                <select id="coordinator_user_id" name="coordinator_user_id" class="form-select">
                                    <option value="">Seleccione un coordinador</option>
                                    <?php foreach ($coordinators as $coordinator): ?>
                                        <option value="<?php echo $coordinator['user_id']; ?>"
                                                <?php echo ($formData['coordinator_user_id'] ?? '') == $coordinator['user_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($coordinator['first_name'] . ' ' . $coordinator['last_name']); ?>
                                            (<?php echo htmlspecialchars($coordinator['email']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Niveles educativos (para futura implementación) -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Niveles Educativos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="nivel[]" id="nivelPreescolar" value="preescolar">
                                    <label class="form-check-label" for="nivelPreescolar">
                                        Preescolar
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="nivel[]" id="nivelPrimaria" value="primaria">
                                    <label class="form-check-label" for="nivelPrimaria">
                                        Básica Primaria
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="nivel[]" id="nivelSecundaria" value="secundaria">
                                    <label class="form-check-label" for="nivelSecundaria">
                                        Básica Secundaria
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="nivel[]" id="nivelMedia" value="media">
                                    <label class="form-check-label" for="nivelMedia">
                                        Media (Bachillerato)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Crear Escuela
                        </button>
                        <a href="?view=school&action=consultSchool" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Incluir el archivo JavaScript -->
<script src="<?php echo url; ?>app/resources/js/createSchool.js"></script>