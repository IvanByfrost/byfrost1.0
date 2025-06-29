<h2>Historial de Roles</h2>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Formulario de búsqueda -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Buscar Usuario</h5>
                </div>
                <div class="card-body">
                    <form id="roleHistoryForm" method="POST" action="#" onsubmit="return false;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="credential_type">Tipo de Documento</label>
                                    <select class="form-control" id="credential_type" name="credential_type" required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="CC">Cédula de Ciudadanía</option>
                                        <option value="TI">Tarjeta de Identidad</option>
                                        <option value="CE">Cédula de Extranjería</option>
                                        <option value="PA">Pasaporte</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="credential_number">Número de Documento</label>
                                    <input type="text" class="form-control" id="credential_number" name="credential_number" 
                                           placeholder="Ingrese el número de documento" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resultados de búsqueda -->
            <div class="card" id="searchResultsCard" style="display: none;">
                <div class="card-header">
                    <h5 class="card-title">Historial de Roles del Usuario</h5>
                </div>
                <div class="card-body" id="searchResultsContainer">
                    <!-- Se llenará via AJAX -->
                </div>
            </div>

            <!-- Contenedor para mostrar el historial de roles si se accede directamente con userId -->
            <div id="directResultsContainer">
                <?php if (isset($searchError) && $searchError): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($searchError) ?></div>
                <?php endif; ?>
                
                <?php if (isset($roleHistory) && !empty($roleHistory)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Historial de Roles del Usuario</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Rol</th>
                                        <th>Activo</th>
                                        <th>Fecha de Asignación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($roleHistory as $rol): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($rol['role_type']) ?></td>
                                        <td><?= $rol['is_active'] ? 'Sí' : 'No' ?></td>
                                        <td><?= htmlspecialchars($rol['created_at']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php elseif (isset($roleHistory) && empty($roleHistory) && !isset($searchError)): ?>
                    <div class="alert alert-info">No hay historial de roles para este usuario.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<a href="javascript:history.back()" class="btn btn-secondary">Volver</a> 