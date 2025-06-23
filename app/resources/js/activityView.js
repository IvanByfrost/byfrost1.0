// Vista de Detalles de Actividad - Funcionalidades JavaScript

// Editar actividad
function editActivity(activityId) {
    window.location.href = `activity/showEditForm/${activityId}`;
}

// Ver calificaciones de la actividad
function viewScores(activityId) {
    // Mostrar loading
    Swal.fire({
        title: 'Cargando calificaciones...',
        text: 'Por favor espera',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // En una implementación real, esto cargaría las calificaciones
    setTimeout(() => {
        Swal.fire({
            icon: 'info',
            title: 'Funcionalidad en desarrollo',
            text: 'La vista de calificaciones estará disponible próximamente',
            confirmButtonText: 'Entendido'
        });
    }, 1000);
}

// Eliminar actividad
function deleteActivity(activityId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Eliminando...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: `activity/deleteActivity/${activityId}`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: response.msg,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'activity/showDashboard';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al eliminar la actividad'
                    });
                }
            });
        }
    });
}

// Imprimir detalles de la actividad
function printActivity() {
    const printContent = document.getElementById('activityDetails').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div class="container mt-4">
            <div class="row">
                <div class="col-12">
                    <h3 class="text-center mb-4">Detalles de la Actividad</h3>
                    ${printContent}
                </div>
            </div>
        </div>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    
    // Re-inicializar scripts después de restaurar el contenido
    location.reload();
}

// Compartir actividad (copiar enlace)
function shareActivity(activityId) {
    const url = `${window.location.origin}/activity/viewActivity/${activityId}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Actividad Académica',
            text: 'Revisa esta actividad académica',
            url: url
        });
    } else {
        // Fallback: copiar al portapapeles
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Enlace copiado',
                text: 'El enlace se ha copiado al portapapeles',
                timer: 2000,
                showConfirmButton: false
            });
        }).catch(() => {
            // Fallback manual
            const textArea = document.createElement('textarea');
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            Swal.fire({
                icon: 'success',
                title: 'Enlace copiado',
                text: 'El enlace se ha copiado al portapapeles',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
}

// Descargar actividad como PDF
function downloadPDF(activityId) {
    Swal.fire({
        icon: 'info',
        title: 'Funcionalidad en desarrollo',
        text: 'La descarga en PDF estará disponible próximamente',
        confirmButtonText: 'Entendido'
    });
}

// Enviar recordatorio a estudiantes
function sendReminder(activityId) {
    Swal.fire({
        title: '¿Enviar recordatorio?',
        text: "Se enviará un recordatorio a todos los estudiantes del grupo",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, enviar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Enviando recordatorios...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Simular envío
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Recordatorios enviados!',
                    text: 'Los recordatorios se han enviado exitosamente',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 2000);
        }
    });
}

// Marcar actividad como completada
function markAsCompleted(activityId) {
    Swal.fire({
        title: '¿Marcar como completada?',
        text: "Esta acción cambiará el estado de la actividad",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, marcar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí iría la lógica para marcar como completada
            Swal.fire({
                icon: 'success',
                title: '¡Completada!',
                text: 'La actividad ha sido marcada como completada',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

// Duplicar actividad
function duplicateActivity(activityId) {
    Swal.fire({
        title: '¿Duplicar actividad?',
        text: "Se creará una copia de esta actividad",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, duplicar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir al formulario de creación con datos pre-poblados
            window.location.href = `activity/showCreateForm?duplicate=${activityId}`;
        }
    });
}

// Configurar eventos cuando se carga la página
$(document).ready(function() {
    // Configurar tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Configurar popovers
    $('[data-toggle="popover"]').popover();
    
    // Configurar eventos de teclado
    $(document).keydown(function(e) {
        // Ctrl/Cmd + P para imprimir
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            printActivity();
        }
        
        // Ctrl/Cmd + S para compartir
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const activityId = getActivityIdFromUrl();
            if (activityId) {
                shareActivity(activityId);
            }
        }
    });
    
    // Configurar eventos de botones
    setupButtonEvents();
});

// Configurar eventos de botones
function setupButtonEvents() {
    // Botón de imprimir
    $('.btn-print').on('click', function() {
        printActivity();
    });
    
    // Botón de compartir
    $('.btn-share').on('click', function() {
        const activityId = getActivityIdFromUrl();
        if (activityId) {
            shareActivity(activityId);
        }
    });
    
    // Botón de descargar PDF
    $('.btn-download').on('click', function() {
        const activityId = getActivityIdFromUrl();
        if (activityId) {
            downloadPDF(activityId);
        }
    });
    
    // Botón de recordatorio
    $('.btn-reminder').on('click', function() {
        const activityId = getActivityIdFromUrl();
        if (activityId) {
            sendReminder(activityId);
        }
    });
    
    // Botón de completar
    $('.btn-complete').on('click', function() {
        const activityId = getActivityIdFromUrl();
        if (activityId) {
            markAsCompleted(activityId);
        }
    });
    
    // Botón de duplicar
    $('.btn-duplicate').on('click', function() {
        const activityId = getActivityIdFromUrl();
        if (activityId) {
            duplicateActivity(activityId);
        }
    });
}

// Obtener ID de actividad desde la URL
function getActivityIdFromUrl() {
    const url = window.location.pathname;
    const matches = url.match(/\/activity\/viewActivity\/(\d+)/);
    return matches ? matches[1] : null;
}

// Actualizar contador de tiempo restante
function updateTimeRemaining() {
    const dueDateElement = document.querySelector('[data-due-date]');
    if (!dueDateElement) return;
    
    const dueDate = new Date(dueDateElement.dataset.dueDate);
    const now = new Date();
    const timeDiff = dueDate - now;
    
    const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
    
    let timeText = '';
    if (timeDiff < 0) {
        timeText = 'Vencida';
    } else if (days > 0) {
        timeText = `${days} días, ${hours} horas`;
    } else if (hours > 0) {
        timeText = `${hours} horas, ${minutes} minutos`;
    } else {
        timeText = `${minutes} minutos`;
    }
    
    const timeElement = document.getElementById('timeRemaining');
    if (timeElement) {
        timeElement.textContent = timeText;
    }
}

// Actualizar contador cada minuto
setInterval(updateTimeRemaining, 60000);
updateTimeRemaining(); // Actualizar inmediatamente 