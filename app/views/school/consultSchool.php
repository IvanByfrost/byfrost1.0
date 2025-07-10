<?php
// Verificar si hay mensajes o errores
$message = $message ?? '';
$error = $error ?? '';
$success = $success ?? false;
$schools = $schools ?? [];
$search = $search ?? '';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Consulta de Escuelas</h2>
                    <p>Busca escuelas por nombre, código DANE o NIT.</p>
                </div>
                <button type="button" class="btn btn-success" onclick="loadView('school/createSchool')">
                    <i class="fas fa-plus"></i> Crear Nueva Escuela
                </button>
            </div>
            
            <!-- Mensajes -->
            <?php if ($success && !empty($message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Formulario de búsqueda -->
            <form id="schoolSearchForm" class="mb-4" onsubmit="return searchSchoolAJAX(event);">
                <input type="hidden" name="view" value="school">
                <input type="hidden" name="action" value="consultSchool">
                <div class="row">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" name="search" id="schoolSearchInput" class="form-control" 
                                   placeholder="Buscar por nombre, código DANE o NIT..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-secondary" onclick="loadView('school/consultSchool')">
                            <i class="fas fa-list"></i> Ver Todas
                        </button>
                    </div>
                </div>
            </form>

            <!-- Resultados -->
            <?php if (!empty($schools)): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-school"></i> 
                            Escuelas Encontradas (<?php echo count($schools); ?>)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Código DANE</th>
                                        <th>NIT</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Director</th>
                                        <th>Coordinador</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($schools as $school): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($school['school_id']); ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($school['school_name']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($school['school_dane']); ?></td>
                                            <td><?php echo htmlspecialchars($school['school_document']); ?></td>
                                            <td><?php echo htmlspecialchars($school['address'] ?? 'No especificada'); ?></td>
                                            <td><?php echo htmlspecialchars($school['phone'] ?? 'No especificado'); ?></td>
                                            <td><?php echo htmlspecialchars($school['email'] ?? 'No especificado'); ?></td>
                                            <td>
                                                <?php if (!empty($school['director_name'])): ?>
                                                    <span class="badge bg-primary">
                                                        <?php echo htmlspecialchars($school['director_name']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">No asignado</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($school['coordinator_name'])): ?>
                                                    <span class="badge bg-info">
                                                        <?php echo htmlspecialchars($school['coordinator_name']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">No asignado</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            onclick="loadView('school/view?id=<?php echo $school['school_id']; ?>')" 
                                                            title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                                            onclick="loadView('school/edit?id=<?php echo $school['school_id']; ?>')" 
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmDelete(<?php echo $school['school_id']; ?>)" 
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php elseif (!empty($search)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-search"></i> 
                    No se encontraron escuelas con los criterios especificados.
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    No hay escuelas registradas. <button type="button" class="btn btn-link p-0" onclick="loadView('school/createSchool')">Crear la primera escuela</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmDelete(schoolId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta escuela? Esta acción no se puede deshacer.')) {
        loadView('school/delete?id=' + schoolId);
    }
}
</script>