

<div class="dashboard-container">
    <aside class="sidebar">
        <?php require_once __DIR__ . '/studentSidebar.php'; ?>
    </aside>
    
    <div id="mainContent" class="mainContent">
        <?php require_once 'menuStudent.php'; ?>
    </div>
</div>

<script src="<?php echo url . app . rq ?>js/studentDashboard.js"></script>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?> 