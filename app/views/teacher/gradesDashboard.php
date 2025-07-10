<?php
// Vista principal del dashboard de calificaciones
require_once ROOT . '/app/views/layouts/dashHead.php';
require_once ROOT . '/app/views/layouts/dashHeader.php';
require_once ROOT . '/app/views/teacher/teacherSidebar.php';
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-graduation-cap"></i> Sistema de Calificaciones</h2>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <button class="btn btn-primary" onclick="loadView('teacher/listGrades')">
                                    <i class="fas fa-list"></i> Ver Calificaciones
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success" onclick="loadView('teacher/addGradeForm')">
                                    <i class="fas fa-plus"></i> Agregar Calificación
                                </button>
                            </div>
                        </div>
                        
                        <div id="content-area">
                            <!-- Aquí se cargará el contenido dinámicamente -->
                            <div class="text-center">
                                <h4>Bienvenido al Sistema de Calificaciones</h4>
                                <p>Selecciona una opción para comenzar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Función para cargar vistas dinámicamente
function loadView(view) {
    fetch(`?view=teacher&action=${view}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('content-area').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('content-area').innerHTML = '<div class="alert alert-danger">Error al cargar la vista</div>';
        });
}
</script>

<?php require_once ROOT . '/app/views/layouts/dashFooter.php'; ?> 