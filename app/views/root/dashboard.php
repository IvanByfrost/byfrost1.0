<?php
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

require_once ROOT . '/app/views/layouts/dashHeader.php';
?>

<script>
const BASE_URL = "<?php echo url ?>";
console.log("Valor de BASE_URL: ", BASE_URL);
</script>

<script type="text/javascript" src="<?php echo url . app . rq ?>js/loadView.js"></script>

<div class="dashboard-container">
    <aside class="sidebar">
        <?php require_once __DIR__ . '/rootSidebar.php'; ?>
    </aside>
    
    <div id="mainContent" class="mainContent">
        <?php require_once 'menuRoot.php'; ?>
    </div>
</div> 