<div class="error-container text-center">
    <div class="error-content">
        <h1 class="error-title">403</h1>
        <h2 class="error-subtitle">Acceso No Autorizado</h2>
        <p class="error-message">
            No tienes permisos para acceder a esta página.
        </p>
        
        <?php if (isset($user) && $user): ?>
            <div class="user-info">
                <p><strong>Usuario actual:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
                <p><strong>Rol:</strong> <?= htmlspecialchars(ucfirst($user['role'])) ?></p>
            </div>
        <?php endif; ?>
        
        <div class="error-actions">
            <a href="/" class="btn btn-primary">Ir al Inicio</a>
            <a href="/login" class="btn btn-secondary">Iniciar Sesión</a>
        </div>
    </div>
</div> 