<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__, 3));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar si el usuario está logueado
if (!$sessionManager->isLoggedIn()) {
    // Si no está logueado, redirigir al login
    header('Location: ' . url . '?view=login');
    exit;
}

// Obtener datos del usuario actual
$currentUser = $sessionManager->getCurrentUser();
$redirectView = $sessionManager->getSessionData('ByFrost_redirect', null);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Byfrost - Cargando...</title>
    <link rel="stylesheet" href="<?php echo url . app . rq ?>css/loading.css">
    <link rel="icon" type="image/x-icon" href="<?php echo url . app . rq ?>img/favicon.png">
</head>

<body>
    <div class="loading-cont">
        <img class="logo" src="<?php echo url . app . rq ?>img/Byfrost-logo.svg" alt="loading-logo" />
        <h1>BYFROST</h1>
        <p>¡Hola de vuelta, <span id="userName"><?php echo htmlspecialchars($currentUser['full_name'] ?: 'Usuario'); ?></span>! ¡Que tengas un buen día 😊!</p>
        <?php if (empty($redirectView)): ?>
            <div style="color: #b94a48; background: #f2dede; border: 1px solid #eed3d7; padding: 1em; border-radius: 5px; margin-top: 2em;">
                <strong>Tu cuenta aún no tiene un rol asignado.</strong><br>
                Por favor, contacta al administrador del sistema para que te asigne uno.<br>
                <a href="<?php echo url; ?>?view=index&action=login">Cerrar sesión</a>
            </div>
        <?php else: ?>
            <script>
                setTimeout(() => {
                    window.location.href = "<?php echo url . '?view=' . htmlspecialchars($redirectView); ?>";
                }, 5000);
            </script>
        <?php endif; ?>
    </div>
</body>

</html>

