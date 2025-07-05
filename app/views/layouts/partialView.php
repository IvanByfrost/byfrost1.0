<?php
/**
 * Layout para vistas parciales que se cargan dinámicamente
 * Este archivo se incluye al inicio de cada vista parcial
 */

// Verificar si es una carga AJAX
$isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

// Si es una carga AJAX, solo mostrar el contenido
if ($isAjaxRequest) {
    // No incluir header/footer, solo el contenido
    return;
}

// Si no es AJAX, incluir el layout completo
include 'dashHead.php';
include 'dashHeader.php';
?>

<!-- El contenido de la vista se insertará aquí -->

<?php
// Solo incluir footer si no es AJAX
if (!$isAjaxRequest) {
    include 'dashFooter.php';
}
?> 