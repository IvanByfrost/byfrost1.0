<?php
error_log('DEBUG: Cargando app/views/root/dashboard.php');
echo '<!-- DEBUG: Cargando app/views/root/dashboard.php -->';
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y sea root
// Esta verificación ya se hace en el controlador, pero por seguridad la mantenemos aquí también
if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

if (!$this->sessionManager->hasRole('root')) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}

// require_once ROOT . '/app/views/layouts/dashHeader.php'; // ELIMINADO: El controlador ya lo incluye
?>

<script>
console.log("BASE_URL será configurada en dashFooter.php");

// Función de respaldo para loadView
window.safeLoadView = function(viewName) {
    console.log('safeLoadView llamado desde dashboard con:', viewName);
    
    if (typeof loadView === 'function') {
        console.log('loadView disponible, ejecutando...');
        loadView(viewName);
    } else {
        console.error('loadView no está disponible, redirigiendo...');
        // Fallback: redirigir a la página
        const url = `${BASE_URL}?view=${viewName.replace('/', '&action=')}`;
        window.location.href = url;
    }
};
</script>

<div class="dashboard-container">
    <aside class="sidebar">
        <?php require_once __DIR__ . '/rootSidebar.php'; ?>
    </aside>
    
    <div id="mainContent" class="mainContent">
        <?php require_once 'menuRoot.php'; ?>
    </div>
</div> 