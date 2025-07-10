<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__, 3));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/views/layouts/head.php';
require_once ROOT . '/app/views/layouts/header.php';

// Obtener token de la URL
$token = $_GET['token'] ?? '';
if (empty($token)) {
    header("Location: " . url . "app/views/index/login.php");
    exit;
}
?>
<script>
    const ROOT = "<?php echo url ?>"; 
</script>
<body>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-box">
                    <div class="text-center mb-4">
                        <h2 class="mb-3">Restablecer Contraseña</h2>
                        <p class="text-muted">Ingresa tu nueva contraseña.</p>
                    </div>
                    
                    <form id="resetPasswordForm">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        
                        <div class="mb-3">
                            <input type="password" class="inputEstilo1" id="newPassword" name="newPassword" 
                                   placeholder="Nueva contraseña" required>
                        </div>
                        
                        <div class="mb-3">
                            <input type="password" class="inputEstilo1" id="confirmPassword" name="confirmPassword" 
                                   placeholder="Confirmar nueva contraseña" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Cambiar contraseña
                            </button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="login.php" class="text-primary" style="text-decoration: none;">
                                ← Volver al inicio de sesión
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>

    <?php
    require_once __DIR__ . '/../layouts/footer.php';
    ?> 