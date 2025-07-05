<?php
/**
 * Layout para formularios que se cargan dinámicamente
 * Este archivo se incluye al inicio de cada formulario
 */

// Verificar si es una carga AJAX
$isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

// Si es una carga AJAX, solo mostrar el contenido del formulario
if ($isAjaxRequest) {
    // No incluir header/footer, solo el contenido del formulario
    return;
}

// Si no es AJAX, incluir el layout completo
include 'dashHead.php';
include 'dashHeader.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Contenido principal sin sidebar -->
        <main class="col-12 px-md-4">
            <!-- El contenido del formulario se insertará aquí -->
        </main>
    </div>
</div>

<?php
// Solo incluir footer si no es AJAX
if (!$isAjaxRequest) {
    include 'dashFooter.php';
}
?> 