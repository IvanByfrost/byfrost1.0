// schoolCoordinatorSelect.js

document.addEventListener('DOMContentLoaded', function() {
    // Delegación para botones de selección de coordinador
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-coordinator-btn')) {
            const userId = e.target.getAttribute('data-user-id');
            const name = e.target.getAttribute('data-name');
            selectCoordinator(userId, name);
        }
    });
});

// Función principal de selección de coordinador
function selectCoordinator(userId, name) {
    // Actualizar campos visibles y ocultos
    document.getElementById('coordinator_user_id').value = userId;
    document.getElementById('selectedCoordinatorName').textContent = name;

    // Cerrar el modal (Bootstrap 5)
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('searchCoordinatorModal'));
    modal.hide();

    // Mostrar mensaje de éxito
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Coordinador Seleccionado',
            text: name,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } else {
        alert('Coordinador seleccionado: ' + name);
    }
}
