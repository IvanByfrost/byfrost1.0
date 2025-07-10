<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';
$sessionManager = new SessionManager();
if (!$sessionManager->isLoggedIn() || !$sessionManager->hasRole('director')) {
    header('Location: ' . url . '?view=index&action=login');
    exit;
}
require_once ROOT . '/app/views/layouts/dashHeader.php';
?>

<link rel="stylesheet" href="<?= url . app . rq ?>css/dashboard-modern.css">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-calendar-plus"></i> Crear Nuevo Evento</h4>
                </div>
                <div class="card-body">
                    <form id="createEventForm" method="post" action="<?= url . app ?>processes/eventProcess.php">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Título del Evento</label>
                            <input type="text" class="form-control" id="eventTitle" name="eventTitle" required maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Descripción</label>
                            <textarea class="form-control" id="eventDescription" name="eventDescription" rows="3" maxlength="500" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="eventDate" class="form-label">Fecha</label>
                                <input type="date" class="form-control" id="eventDate" name="eventDate" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="eventTime" class="form-label">Hora</label>
                                <input type="time" class="form-control" id="eventTime" name="eventTime" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="eventLocation" class="form-label">Lugar</label>
                            <input type="text" class="form-control" id="eventLocation" name="eventLocation" maxlength="100" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventType" class="form-label">Tipo de Evento</label>
                            <select class="form-select" id="eventType" name="eventType" required>
                                <option value="">Selecciona...</option>
                                <option value="Academico">Académico</option>
                                <option value="Cultural">Cultural</option>
                                <option value="Deportivo">Deportivo</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Evento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('createEventForm').addEventListener('submit', function(e) {
    // Validación básica extra
    const title = document.getElementById('eventTitle').value.trim();
    const desc = document.getElementById('eventDescription').value.trim();
    if (title.length < 3) {
        alert('El título debe tener al menos 3 caracteres.');
        e.preventDefault();
    }
    if (desc.length < 10) {
        alert('La descripción debe tener al menos 10 caracteres.');
        e.preventDefault();
    }
});
</script> 