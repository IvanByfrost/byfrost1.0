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
</script>

<script type="text/javascript" src="<?php echo url . app . rq ?>js/loadView.js"></script>

<div class="dashboard-container">
    <aside class="sidebar">
        <?php require_once __DIR__ . '/rootSidebar.php'; ?>
    </aside>
    
    <div id="mainContent" class="mainContent">
        <?php require_once 'menuRoot.php'; ?>
         <!-- INICIO: Contenido principal del dashboard -->
         <h1 style="font-size:24px; margin-bottom: 20px;"> <div class="icon-book-marked"></div>Panel de Control Escolar</h1>
         <div class="summary-cards" style="display: flex; gap:20px; flex-wrap: wrap;">
             <div style="flex: 1; min-width: 200px; background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                 <strong>Estudiantes</strong><br><span style="font-size: 24px;">1,230</span>
             </div>
         </div>
    </div>
</div> 