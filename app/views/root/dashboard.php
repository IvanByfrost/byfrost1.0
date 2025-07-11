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

// Seguridad redundante
if (!isset($this->sessionManager) || !$this->sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

if (!$this->sessionManager->hasRole('root')) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}

// Verifica si es una solicitud AJAX parcial
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
$isPartial = isset($_GET['partialView']) && $_GET['partialView'] === 'true';

// Si es parcial, solo carga el contenido central (por ejemplo: menuRoot.php)
if ($isAjax && $isPartial) {
    require_once 'menuRoot.php';
    exit;
}
?>

<!-- Vista completa -->
<script src="<?php echo url . app . rq ?>js/rootDashboard.js"></script>
<script src="<?php echo url . app . rq ?>js/loadView.js"></script>

<div class="dashboard-container">
    <aside class="sidebar">
        <?php require_once __DIR__ . '/rootSidebar.php'; ?>
    </aside>

    <div id="mainContent" class="mainContent">
        <?php require_once 'menuRoot.php'; ?>
    </div>
</div>
