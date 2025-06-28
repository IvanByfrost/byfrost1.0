<?php
// Verificar si hay mensajes o errores
$message = $message ?? '';
$error = $error ?? '';
$schools = $schools ?? [];
$searchData = $searchData ?? [];
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Consulta de Escuelas</h2>
            <p>Busca escuelas por NIT, nombre o código DANE.</p>
            
            <!-- Formulario de búsqueda -->
            <form method="POST" id="consultSchool" class="dash-form mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="nit" name="nit" class="form-control" 
                                   placeholder="NIT del colegio" 
                                   value="<?php echo htmlspecialchars($searchData['nit'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="school_name" name="school_name" class="form-control" 
                                   placeholder="Nombre del colegio"
                                   value="<?php echo htmlspecialchars($searchData['school_name'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="codigoDANE" name="codigoDANE" class="form-control" 
                                   placeholder="Código DANE del colegio"
                                   value="<?php echo htmlspecialchars($searchData['codigoDANE'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Consultar
                        </button>
                        <a href="?view=school&action=consultSchool" class="btn btn-secondary">
                            <i class="fas fa-list"></i> Ver Todas
                        </a>
                    </div>
                </div>
            </form>

            <!-- Mensajes -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

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
                                                    <a href="?view=school&action=view&id=<?php echo $school['school_id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="?view=school&action=edit&id=<?php echo $school['school_id']; ?>" 
                                                       class="btn btn-sm btn-outline-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
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
            <?php elseif (!empty($searchData)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-search"></i> 
                    No se encontraron escuelas con los criterios especificados.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmDelete(schoolId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta escuela? Esta acción no se puede deshacer.')) {
        window.location.href = '?view=school&action=delete&id=' + schoolId;
    }
}

// Búsqueda en tiempo real (opcional)
document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('#consultSchool input[type="text"]');
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Aquí podrías implementar búsqueda en tiempo real si lo deseas
        });
    });
});
</script>