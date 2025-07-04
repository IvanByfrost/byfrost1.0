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
    header("Location: " . url . "?view=login");
    exit;
}

// Verificar si el usuario tiene el rol de padre/acudiente
if (!$sessionManager->hasRole('parent')) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}

require_once ROOT . '/app/views/layouts/dashHeader.php';
?>

<script>
    console.log("BASE_URL será configurada en dashFooter.php");
</script>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <?php
            // Aquí puedes incluir un sidebar específico para padres si lo tienes
            // require_once 'parentSidebar.php';
            ?>
            <div class="sidebar-content">
                <h3>Menú de Acudiente</h3>
                <ul>
                    <li><a href="#" onclick="loadView('parent/dashboard')">Dashboard</a></li>
                    <li><a href="#" onclick="loadView('parent/students')">Mis Estudiantes</a></li>
                    <li><a href="#" onclick="loadView('parent/grades')">Calificaciones</a></li>
                    <li><a href="#" onclick="loadView('parent/activities')">Actividades</a></li>
                    <li><a href="#" onclick="loadView('parent/schedule')">Horarios</a></li>
                    <li><a href="#" onclick="loadView('parent/communications')">Comunicaciones</a></li>
                </ul>
            </div>
        </aside>
        
        <div id="mainContent" class="mainContent">
            <div class="welcome-message">
                <h1>Dashboard de Acudiente</h1>
                <p>Bienvenido, <?php echo htmlspecialchars($sessionManager->getUserFullName()); ?></p>
                <p>Desde aquí puedes gestionar la información académica de tus estudiantes.</p>
                
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <h3>Estudiantes</h3>
                        <p class="stat-number">2</p>
                        <p class="stat-label">Estudiantes a cargo</p>
                    </div>
                    <div class="stat-card">
                        <h3>Actividades</h3>
                        <p class="stat-number">5</p>
                        <p class="stat-label">Actividades pendientes</p>
                    </div>
                    <div class="stat-card">
                        <h3>Comunicaciones</h3>
                        <p class="stat-number">3</p>
                        <p class="stat-label">Mensajes nuevos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php require_once __DIR__ . '/../layouts/dashFooter.php'; ?>
