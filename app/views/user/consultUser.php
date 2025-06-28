<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Búsqueda de Usuarios</h2>
            <p>Busca un usuario por documento o por rol</p>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Buscar Usuario</h5>
                </div>
                <div class="card-body">
                    <form id="searchUserForm" method="POST" action="#" onsubmit="return false;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search_type">Tipo de Búsqueda</label>
                                    <select class="form-control" id="search_type" name="search_type" required onchange="toggleSearchFields()">
                                        <option value="">Seleccionar tipo</option>
                                        <option value="document">Por Documento</option>
                                        <option value="role">Por Rol</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" id="document_type_field" style="display: none;">
                                <div class="form-group">
                                    <label for="credential_type">Tipo de Documento</label>
                                    <select class="form-control" id="credential_type" name="credential_type">
                                        <option value="">Seleccionar tipo</option>
                                        <option value="CC">Cédula de Ciudadanía</option>
                                        <option value="TI">Tarjeta de Identidad</option>
                                        <option value="CE">Cédula de Extranjería</option>
                                        <option value="PP">Pasaporte</option>
                                        <option value="RC">Registro Civil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" id="role_type_field" style="display: none;">
                                <div class="form-group">
                                    <label for="role_type">Rol</label>
                                    <select class="form-control" id="role_type" name="role_type">
                                        <option value="">Seleccionar rol</option>
                                        <option value="student">Estudiante</option>
                                        <option value="parent">Padre/Acudiente</option>
                                        <option value="professor">Profesor</option>
                                        <option value="coordinator">Coordinador</option>
                                        <option value="director">Director/Rector</option>
                                        <option value="treasurer">Tesorero</option>
                                        <option value="root">Administrador</option>
                                        <option value="no_role">Sin Rol</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" id="document_number_field" style="display: none;">
                                <div class="form-group">
                                    <label for="credential_number">Número de Documento</label>
                                    <input type="text" class="form-control" id="credential_number" name="credential_number"
                                        placeholder="Ingrese el número de documento">
                                </div>
                            </div>
                            <div class="col-md-3">
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
                    <h5 class="card-title">Usuarios Encontrados</h5>
                </div>
                <div class="card-body" id="searchResultsContainer">
                    <!-- Se llenará via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>