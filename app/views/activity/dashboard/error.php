<?php require_once 'app/views/layouts/head.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Error en el Dashboard de Actividades
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="py-5">
                        <i class="fas fa-exclamation-circle text-danger" style="font-size: 4rem;"></i>
                        <h3 class="mt-3 text-danger">¡Ups! Algo salió mal</h3>
                        <p class="text-muted">
                            <?= isset($message) ? htmlspecialchars($message) : 'Ocurrió un error inesperado al cargar el dashboard de actividades.' ?>
                        </p>
                        
                        <div class="mt-4">
                            <a href="activity/showDashboard" class="btn btn-primary me-2">
                                <i class="fas fa-refresh me-2"></i>Intentar de nuevo
                            </a>
                            <a href="index" class="btn btn-secondary">
                                <i class="fas fa-home me-2"></i>Volver al inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?> 