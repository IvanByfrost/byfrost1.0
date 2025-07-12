<?php

/**
 * Vista para mostrar el historial de roles de un usuario
 * 
 * Variables disponibles:
 * - $userId: ID del usuario
 * - $user: Información del usuario
 * - $roleHistory: Array con el historial de roles
 */

// Validar que el usuario existe
if (!$user) {
    echo '<div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Error:</strong> Usuario no encontrado.
          </div>';
    return;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-history"></i>
                        Historial de Roles - <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                    </h4>
                    <p class="card-subtitle text-muted">
                        Registro completo de roles asignados a este usuario
                        <br>
                        <small>
                            <strong>Documento:</strong> <?= htmlspecialchars($user['credential_type'] . ' ' . $user['credential_number']) ?> |
                            <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?> |
                            <strong>ID:</strong> <?= htmlspecialchars($userId) ?>
                        </small>
                    </p>
                </div>
                <div class="card-body">
                    <?php if (empty($roleHistory)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Este usuario no tiene historial de roles registrado.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Fecha de Asignación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($roleHistory as $index => $role): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <span class="badge bg-<?= getRoleBadgeColor($role['role_type']) ?>">
                                                    <?= ucfirst(htmlspecialchars($role['role_type'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($role['is_active']): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Activo
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-times"></i> Inactivo
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <i class="fas fa-calendar-alt"></i>
                                                <?= date('d/m/Y H:i', strtotime($role['created_at'])) ?>
                                            </td>
                                            <td>
                                                <?php if ($role['is_active']): ?>
                                                    <button class="btn btn-sm btn-warning"
                                                        onclick="deactivateRole(<?= $userId ?>, '<?= $role['role_type'] ?>')"
                                                        title="Desactivar rol">
                                                        <i class="fas fa-pause"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-success"
                                                        onclick="activateRole(<?= $userId ?>, '<?= $role['role_type'] ?>')"
                                                        title="Activar rol">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <h5>Resumen del Historial</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h6>Total de Roles</h6>
                                            <h3><?= count($roleHistory) ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h6>Roles Activos</h6>
                                            <h3><?= count(array_filter($roleHistory, function ($r) {
                                                    return $r['is_active'];
                                                })) ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-secondary text-white">
                                        <div class="card-body text-center">
                                            <h6>Roles Inactivos</h6>
                                            <h3><?= count(array_filter($roleHistory, function ($r) {
                                                    return !$r['is_active'];
                                                })) ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h6>Tipos Únicos</h6>
                                            <h3><?= count(array_unique(array_column($roleHistory, 'role_type'))) ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button class="btn btn-secondary" onclick="goBack()">
                                <i class="fas fa-arrow-left"></i> Volver
                            </button>
                            <button class="btn btn-info" onclick="viewUser(<?= $userId ?>)">
                                <i class="fas fa-user"></i> Ver Usuario
                            </button>
                        </div>
                        <div>
                            <button class="btn btn-primary" onclick="assignNewRole(<?= $userId ?>)">
                                <i class="fas fa-plus"></i> Asignar Nuevo Rol
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript se carga desde archivo externo: roleHistory.js -->

<?php
/**
 * Función auxiliar para obtener el color del badge
 */
function getRoleBadgeColor($roleType)
{
    $colors = [
        'root' => 'danger',
        'director' => 'primary',
        'coordinator' => 'info',
        'professor' => 'warning',
        'treasurer' => 'success',
        'student' => 'secondary',
        'parent' => 'light'
    ];
    return $colors[$roleType] ?? 'secondary';
}
?>