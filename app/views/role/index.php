<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles y Permisos</title>
    <link rel="stylesheet" href="/app/resources/css/bootstrap.css">
    <style>
        .permission-badge {
            font-size: 0.8em;
            margin: 2px;
        }
        .role-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: box-shadow 0.3s ease;
        }
        .role-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .role-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            border-radius: 8px 8px 0 0;
        }
        .role-body {
            padding: 15px;
        }
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Gestión de Roles y Permisos</h1>
                    <a href="/?controller=root&action=dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Dashboard
                    </a>
                </div>

                <?php if (isset($roles) && !empty($roles)): ?>
                    <div class="row">
                        <?php foreach ($roles as $role): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="role-card">
                                    <div class="role-header">
                                        <h5 class="mb-0">
                                            <?php
                                            $roleNames = [
                                                'student' => 'Estudiante',
                                                'parent' => 'Padre/Acudiente',
                                                'professor' => 'Profesor',
                                                'coordinator' => 'Coordinador',
                                                'director' => 'Director/Rector',
                                                'treasurer' => 'Tesorero',
                                                'root' => 'Administrador'
                                            ];
                                            echo $roleNames[$role] ?? ucfirst($role);
                                            ?>
                                        </h5>
                                        <small class="text-muted"><?= $role ?></small>
                                    </div>
                                    <div class="role-body">
                                        <?php
                                        // Obtener permisos actuales para este rol
                                        $permissions = $this->model->getPermissionsByRole($role);
                                        ?>
                                        <div class="permissions-grid">
                                            <span class="badge <?= $permissions['can_create'] ? 'bg-success' : 'bg-secondary' ?> permission-badge">
                                                Crear <?= $permissions['can_create'] ? '✓' : '✗' ?>
                                            </span>
                                            <span class="badge <?= $permissions['can_read'] ? 'bg-success' : 'bg-secondary' ?> permission-badge">
                                                Leer <?= $permissions['can_read'] ? '✓' : '✗' ?>
                                            </span>
                                            <span class="badge <?= $permissions['can_update'] ? 'bg-success' : 'bg-secondary' ?> permission-badge">
                                                Actualizar <?= $permissions['can_update'] ? '✓' : '✗' ?>
                                            </span>
                                            <span class="badge <?= $permissions['can_delete'] ? 'bg-success' : 'bg-secondary' ?> permission-badge">
                                                Eliminar <?= $permissions['can_delete'] ? '✓' : '✗' ?>
                                            </span>
                                        </div>
                                        <div class="mt-3">
                                            <a href="/?controller=role&action=edit&role_type=<?= urlencode($role) ?>" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Editar Permisos
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <h4>No hay roles configurados</h4>
                        <p>No se encontraron roles en el sistema. Esto puede indicar que:</p>
                        <ul>
                            <li>No hay usuarios registrados con roles</li>
                            <li>La tabla de roles no está configurada correctamente</li>
                            <li>Hay un problema con la base de datos</li>
                        </ul>
                        <a href="/?controller=user&action=assignRole" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Asignar Roles a Usuarios
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="/app/resources/js/bootstrap.bundle.js"></script>
</body>
</html> 