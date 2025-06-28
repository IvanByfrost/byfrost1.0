<?php
http_response_code(500);
// Prevenir cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Incluir el layout head
require_once 'app/views/layouts/head.php';
?>

<body>
    <div class="error-container text-center">
        <div class="error-content">
            <h1 class="error-title">500</h1>
            <h2 class="error-subtitle">Error Interno del Servidor</h2>
            <p class="error-message">
                Algo salió mal en nuestro lado. Intenta de nuevo más tarde.
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
            
            <div class="debug-info mt-4">
                <h4>Información de depuración:</h4>
                <p><strong>URL solicitada:</strong> <?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A'); ?></p>
                <p><strong>Método:</strong> <?php echo htmlspecialchars($_SERVER['REQUEST_METHOD'] ?? 'N/A'); ?></p>
                <p><strong>Timestamp:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
