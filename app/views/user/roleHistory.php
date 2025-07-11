<?php
// Verificar que se hayan pasado los datos necesarios
if (!isset($user) || !isset($roleHistory)) {
    echo '<div class="alert alert-danger">Error: Datos de usuario no disponibles.</div>';
    return;
}

$searchError = $searchError ?? '';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Historial de Roles</h2>
                    <p>Consulta el historial completo de roles asignados a un usuario.</p>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-info" onclick="loadView('user/view?id=<?= $user['user_id'] ?>')">
                        <i class="fas fa-eye"></i> Ver Usuario
                    </button>
                    <button type="button" class="btn btn-warning" onclick="loadView('user/edit?id=<?= $user['user_id'] ?>')">
                        <i class="fas fa-edit"></i> Editar Usuario
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="loadView('user/consultUser')">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                </div>
            </div>

            <!-- Formulario de Búsqueda -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-search"></i> Buscar Usuario
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" id="roleHistoryForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="credential_type" class="form-label">Tipo de Documento</label>
                                    <select class="form-control" id="credential_type" name="credential_type" required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="CC" <?= $user['credential_type'] === 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                                        <option value="TI" <?= $user['credential_type'] === 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                                        <option value="CE" <?= $user['credential_type'] === 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                                        <option value="PP" <?= $user['credential_type'] === 'PP' ? 'selected' : '' ?>>Pasaporte</option>
                                        <option value="RC" <?= $user['credential_type'] === 'RC' ? 'selected' : '' ?>>Registro Civil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="credential_number" class="form-label">Número de Documento</label>
                                    <input type="text" class="form-control" id="credential_number" name="credential_number"
                                           value="<?= htmlspecialchars($user['credential_number']) ?>"
                                           placeholder="Ingrese el número de documento" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mensajes de error -->
            <?php if (!empty($searchError)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($searchError) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Información del Usuario -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> Información del Usuario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombre Completo:</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email:</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($user['email']) ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Documento:</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info"><?= htmlspecialchars($user['credential_type'] . ': ' . $user['credential_number']) ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Roles -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history"></i> Historial de Roles
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($roleHistory)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Fecha de Asignación</th>
                                        <th>Fecha de Desactivación</th>
                                        <th>Asignado Por</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($roleHistory as $role): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary"><?= htmlspecialchars(ucfirst($role['role_type'])) ?></span>
                                            </td>
                                            <td>
                                                <?php if ($role['is_active']): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($role['created_at']))) ?></td>
                                            <td>
                                                <?php if (!$role['is_active']): ?>
                                                    <?= htmlspecialchars(date('d/m/Y H:i', strtotime($role['updated_at'] ?? $role['created_at']))) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="text-muted">Sistema</span></td>
                                            <td>
                                                <?php if ($role['is_active']): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="confirmDeactivateRole(<?= $user['user_id'] ?>, '<?= $role['role_type'] ?>')">
                                                        <i class="fas fa-user-slash"></i> Desactivar
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-sm btn-outline-success"
                                                            onclick="confirmReactivateRole(<?= $user['user_id'] ?>, '<?= $role['role_type'] ?>')">
                                                        <i class="fas fa-user-check"></i> Reactivar
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No se encontró historial de roles para este usuario.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total de Roles</h5>
                            <h3><?= count($roleHistory) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white text-center">
                        <div class="card-body">
                            <h5 class="card-title">Roles Activos</h5>
                            <h3><?= count(array_filter($roleHistory, fn($r) => $r['is_active'])) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white text-center">
                        <div class="card-body">
                            <h5 class="card-title">Roles Inactivos</h5>
                            <h3><?= count(array_filter($roleHistory, fn($r) => !$r['is_active'])) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white text-center">
                        <div class="card-body">
                            <h5 class="card-title">Primer Rol</h5>
                            <h6><?= !empty($roleHistory) ? ucfirst($roleHistory[count($roleHistory)-1]['role_type']) : 'N/A' ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
