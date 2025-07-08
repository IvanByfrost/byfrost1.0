<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar SessionManager
$sessionManager = new SessionManager();

// Verificar que el usuario esté logueado y sea director
if (!$sessionManager->isLoggedIn()) {
    header("Location: " . url . "?view=index&action=login");
    exit;
}

if (!$sessionManager->hasRole('director')) {
    header("Location: " . url . "?view=unauthorized");
    exit;
}

require_once ROOT . '/app/views/layouts/dashHeader.php';
?>

<div class="dashboard-container">
    <aside class="sidebar">
        <?php require_once __DIR__ . '/directorSidebar.php'; ?>
    </aside>
    
    <div id="mainContent" class="mainContent">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#" onclick="loadView('directorDashboard')">
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="fas fa-plus"></i> Crear Evento
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-plus"></i>
                                Crear Nuevo Evento
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="createEventForm" method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="event_title" class="form-label">
                                                <i class="fas fa-tag"></i> Título del Evento *
                                            </label>
                                            <input type="text" class="form-control" id="event_title" name="event_title" 
                                                   placeholder="Ej: Reunión de Padres" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="event_type" class="form-label">
                                                <i class="fas fa-list"></i> Tipo de Evento *
                                            </label>
                                            <select class="form-select" id="event_type" name="event_type" required>
                                                <option value="">Seleccionar tipo</option>
                                                <option value="important">Importante</option>
                                                <option value="academic">Académico</option>
                                                <option value="cultural">Cultural</option>
                                                <option value="sports">Deportes</option>
                                                <option value="administrative">Administrativo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="event_date" class="form-label">
                                                <i class="fas fa-calendar"></i> Fecha del Evento *
                                            </label>
                                            <input type="date" class="form-control" id="event_date" name="event_date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="event_time" class="form-label">
                                                <i class="fas fa-clock"></i> Hora del Evento
                                            </label>
                                            <input type="time" class="form-control" id="event_time" name="event_time">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="event_location" class="form-label">
                                                <i class="fas fa-map-marker-alt"></i> Ubicación
                                            </label>
                                            <input type="text" class="form-control" id="event_location" name="event_location" 
                                                   placeholder="Ej: Auditorio principal">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="event_priority" class="form-label">
                                                <i class="fas fa-exclamation-triangle"></i> Prioridad
                                            </label>
                                            <select class="form-select" id="event_priority" name="event_priority">
                                                <option value="normal">Normal</option>
                                                <option value="high">Alta</option>
                                                <option value="urgent">Urgente</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="event_description" class="form-label">
                                        <i class="fas fa-align-left"></i> Descripción del Evento
                                    </label>
                                    <textarea class="form-control" id="event_description" name="event_description" 
                                              rows="4" placeholder="Describe los detalles del evento..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="event_participants" class="form-label">
                                        <i class="fas fa-users"></i> Participantes
                                    </label>
                                    <select class="form-select" id="event_participants" name="event_participants" multiple>
                                        <option value="students">Estudiantes</option>
                                        <option value="parents">Padres de Familia</option>
                                        <option value="teachers">Docentes</option>
                                        <option value="administrative">Personal Administrativo</option>
                                        <option value="all">Toda la Comunidad</option>
                                    </select>
                                    <small class="form-text text-muted">Mantén presionado Ctrl (Cmd en Mac) para seleccionar múltiples opciones</small>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" onclick="loadView('directorDashboard')">
                                        <i class="fas fa-arrow-left"></i> Volver al Dashboard
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Crear Evento
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Establecer fecha mínima como hoy
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('event_date').min = today;

    // Manejar envío del formulario
    document.getElementById('createEventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const eventData = {
            title: formData.get('event_title'),
            type: formData.get('event_type'),
            date: formData.get('event_date'),
            time: formData.get('event_time'),
            location: formData.get('event_location'),
            priority: formData.get('event_priority'),
            description: formData.get('event_description'),
            participants: Array.from(formData.getAll('event_participants'))
        };

        // Mostrar loading
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
        submitBtn.disabled = true;

        // Enviar datos al servidor
        fetch(`${BASE_URL}?view=directorDashboard&action=createEvent`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Evento creado correctamente',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    loadView('directorDashboard');
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Error al crear el evento',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Error de conexión al crear el evento',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        })
        .finally(() => {
            // Restaurar botón
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
});
</script>

<?php
require_once __DIR__ . '/../layouts/dashFooter.php';
?> 