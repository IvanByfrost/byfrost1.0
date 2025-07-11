<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/helpers/SessionHelper.php';

$session = getSession();
$userRole = $session->getUserRole() ?? 'unknown';
?>

<footer>
    <div class="copyright">
        <p>Byfrost &copy; 2026. Todos los derechos reservados.</p>
        <p>Diseñado por Byfrost Software.</p>
    </div>
</footer>

<!-- Variables JS -->
<script>
    const BASE_URL = '<?= url ?>';
    window.USER_MANAGEMENT_BASE_URL = BASE_URL;
    console.log('URL base configurada:', BASE_URL);
</script>

<!-- Librerías externas -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<!-- Scripts comunes -->
<script src="<?= url . app . rq ?>js/onlyNumber.js"></script>
<script src="<?= url . app . rq ?>js/toggles.js"></script>
<script src="<?= url . app . rq ?>js/sessionHandler.js"></script>
<script src="<?= url . app . rq ?>js/Uploadpicture.js"></script>
<script src="<?= url . app . rq ?>js/User.js"></script>
<script src="<?= url . app . rq ?>js/Principal.js"></script>
<script src="<?= url . app . rq ?>js/app.js"></script>
<script src="<?= url . app . rq ?>js/profileSettings.js"></script>
<script src="<?= url . app . rq ?>js/payrollManagement.js"></script>
<script src="<?= url . app . rq ?>js/sidebarToggle.js"></script>
<script src="<?= url . app . rq ?>js/schoolDirectorSelect.js"></script>
<script src="<?= url . app . rq ?>js/schoolDirectorSearch.js"></script>
<script src="<?= url . app . rq ?>js/schoolCoordinatorSelect.js"></script>
<script src="<?= url . app . rq ?>js/test-onlyNumbers.js"></script>

<!-- Scripts por rol -->
<?php if ($userRole === 'director'): ?>
    <script src="<?= url . app . rq ?>js/directorMetrics.js"></script>
    <script src="<?= url . app . rq ?>js/directorCharts.js"></script>
    <script src="<?= url . app . rq ?>js/directorCommunication.js"></script>
    <script src="<?= url . app . rq ?>js/directorDashboard.js"></script>
    <script src="<?= url . app . rq ?>js/dashboard.js"></script>
<?php elseif ($userRole === 'professor'): ?>
    <script src="<?= url . app . rq ?>js/activityDataTable.js"></script>
    <script src="<?= url . app . rq ?>js/activityForm.js"></script>
    <script src="<?= url . app . rq ?>js/activityActions.js"></script>
    <script src="<?= url . app . rq ?>js/activityDashboard.js"></script>
<?php elseif ($userRole === 'root'): ?>
    <script src="<?= url . app . rq ?>js/userSearch.js"></script>
    <script src="<?= url . app . rq ?>js/roleHistory.js"></script>
    <script src="<?= url . app . rq ?>js/schoolValidation.js"></script>
    <script src="<?= url . app . rq ?>js/schoolForm.js"></script>
    <script src="<?= url . app . rq ?>js/createSchool.js"></script>
    <script src="<?= url . app . rq ?>js/userManagement.js"></script>
    <script src="<?= url . app . rq ?>js/roleManagement.js"></script>
<?php endif; ?>

<!-- Lucide y navegación -->
<script>lucide.createIcons();</script>
<script type="module">
    import { initializeNavigation } from '<?= url . app . rq ?>js/navigation/index.js';
    initializeNavigation();
</script>
</body>
</html>
