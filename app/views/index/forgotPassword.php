<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__, 3));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/library/Validator.php';
require_once ROOT . '/app/views/layouts/head.php';
require_once ROOT . '/app/views/layouts/header.php';
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
                        <h2 class="mb-3">¿Olvidaste tu contraseña?</h2>
                        <p class="text-muted">Ingresa tu documento y correo electrónico para restablecer tu contraseña.</p>
                    </div>
                    
                    <form id="forgotPasswordForm">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

                        <div class="mb-3">
                            <select class="inputEstilo1" id="credType" name="credType" required>
                                <option value="">Seleccione tipo de documento</option>
                                <option value="CC">Cédula de ciudadanía</option>
                                <option value="TI">Tarjeta de identidad</option>
                                <option value="PP">Pasaporte</option>
                                <option value="CE">Cédula de extranjería</option>
                                <option value="PM">Permiso de permanencia</option>
                                <option value="RC">Registro civil</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <input type="text" class="inputEstilo1" id="userDocument" name="userDocument" 
                                   placeholder="Número de documento" pattern="[0-9]+" 
                                   onkeyup="onlyNumbers('userDocument',value);" autocomplete="off" required>
                        </div>
                        
                        <div class="mb-3">
                            <input type="email" class="inputEstilo1" id="userEmail" name="userEmail" 
                                   placeholder="Correo electrónico registrado" autocomplete="off" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Enviar enlace de restablecimiento
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