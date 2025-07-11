<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar si el usuario está logueado
if (!$sessionManager->isLoggedIn()) {
    // Usar JavaScript para redirigir en lugar de header() ya que el contenido ya se envió
    echo "<script>window.location.href = '" . url . "?view=login';</script>";
    exit;
}

// Verificar si el usuario tiene el rol de coordinador (corregido)
if (!$sessionManager->hasRole('coordinator')) {
    // Usar JavaScript para redirigir en lugar de header() ya que el contenido ya se envió
    echo "<script>window.location.href = '" . url . "?view=unauthorized';</script>";
    exit;
}

require_once ROOT . '/app/views/layouts/dashHeader.php';
?>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <?php
            // Cargar sidebar de coordinador si existe, sino usar uno genérico
            $coordinatorSidebar = ROOT . '/app/views/coordinator/coordinatorSidebar.php';
            if (file_exists($coordinatorSidebar)) {
                require_once $coordinatorSidebar;
            } else {
                // Sidebar genérico para coordinador
                ?>
                <div class="sidebar-content">
                    <h3>Panel de Coordinador</h3>
                    <ul class="nav-list">
                        <li><a href="#" onclick="loadView('coordinator/dashboard'); return false;">Dashboard</a></li>
                        <li><a href="#" onclick="loadView('coordinator/studentManagement'); return false;">Gestión de Estudiantes</a></li>
                        <li><a href="#" onclick="loadView('coordinator/teacherManagement'); return false;">Gestión de Profesores</a></li>
                        <li><a href="#" onclick="loadView('coordinator/subjectManagement'); return false;">Gestión de Materias</a></li>
                    </ul>
                </div>
                <?php
            }
            ?>
        </aside>
        <div id="mainContent">
            <div class="dashboard-welcome">
                <h1>Bienvenido, Coordinador</h1>
                <p>Panel de administración académica</p>
            </div>
        </div>
    </div>
</body>

<?php
require_once ROOT . '/app/views/layouts/dashFooter.php';
?>