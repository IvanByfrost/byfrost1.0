/**
 * Módulo de acciones para actividades
 */

// Ver actividad
function viewActivity(activityId) {
    // Mostrar loading en el modal
    $('#viewActivityContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Cargando...</p></div>');
    $('#viewActivityModal').modal('show');
    
    $.get(`activity/viewActivity/${activityId}`, function(data) {
        $('#viewActivityContent').html(data);
    }).fail(function() {
        $('#viewActivityContent').html('<div class="alert alert-danger">Error al cargar los detalles de la actividad</div>');
    });
}

// Editar actividad
function editActivity(activityId) {
    window.location.href = `activity/showEditForm/${activityId}`;
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
            
            // Enviar petición de eliminación
            $.ajax({
                url: `activity/deleteActivity/${activityId}`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: response.msg
                        }).then(() => {
                            location.reload();
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

// Cambiar estado de actividad
function changeActivityStatus(activityId, newStatus) {
    Swal.fire({
        title: '¿Confirmar cambio?',
        text: `¿Estás seguro de cambiar el estado a "${newStatus}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `activity/changeStatus/${activityId}`,
                type: 'POST',
                data: { status: newStatus },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Cambiado!',
                            text: response.msg
                        }).then(() => {
                            location.reload();
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
                        text: 'Ocurrió un error al cambiar el estado'
                    });
                }
            });
        }
    });
} 