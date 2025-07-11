<?php
if (!$isAjax || !$isPartial) {
    echo '</div>'; // mainContent
    echo '</div>'; // dashboard-container

    // Footer general (scripts y cierre de body/html)
    require_once ROOT . '/app/views/layouts/footers/dashFooter.php';
}
?>
