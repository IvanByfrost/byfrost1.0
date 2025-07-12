<footer>
    <div class="copyright">
        <p>Byfrost &copy; 2026. Todos los derechos reservados.</p>
        <p>Diseñado por Byfrost Software.</p>
    </div>
</footer>

<script>
    window.BASE_URL = '<?= htmlspecialchars(url) ?>';
    console.log('BASE_URL configurada:', window.BASE_URL);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    if (window.lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    } else {
        console.warn('Lucide no se cargó correctamente.');
    }
</script>

<script src="<?php echo url . app . rq ?>js/loadView.js"></script>
<script src="<?php echo url . app . rq ?>js/navigation/formHandler.js"></script>

<script src="<?php echo url . app . rq ?>js/onlyNumber.js"></script>
<script src="<?php echo url . app . rq ?>js/utils/toggles.js"></script>
<script src="<?php echo url . app . rq ?>js/user-management/Uploadpicture.js"></script>
<script src="<?php echo url . app . rq ?>js/utils/sidebarToggle.js"></script>
<script src="<?php echo url . app . rq ?>js/dashboard/rootDashboard.js"></script>
<script src="<?php echo url . app . rq ?>js/dashboard/payrollDashboard.js"></script>
<script src="<?php echo url . app . rq ?>js/dashboard/treasurerDashboard.js"></script>
<script src="<?php echo url . app . rq ?>js/dashboard/studentDashboard.js"></script>
<script src="<?php echo url . app . rq ?>js/dashboard/teacherDashboard.js"></script>
<script src="<?php echo url . app . rq ?>js/user-management/userEdit.js"></script>
<script src="<?php echo url . app . rq ?>js/user-management/roleHistory.js"></script>
<script src="<?php echo url . app . rq ?>js/user-management/userManagement.js"></script>
<script src="<?php echo url . app . rq ?>js/school-management/schoolActions.js"></script>
<script src="<?php echo url . app . rq ?>js/school-management/schoolForm.js"></script>
<script src="<?php echo url . app . rq ?>js/school-management/schoolManagement.js"></script>
<script src="<?php echo url . app . rq ?>js/school-management/schoolSearch.js"></script>
<script src="<?php echo url . app . rq ?>js/school-management/schoolValidation.js"></script>

</body>
</html>
