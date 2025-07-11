// schoolDirectorSelect.js

document.addEventListener('DOMContentLoaded', function() {
    // Delegación para botones de selección de director
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-director-btn')) {
            const userId = e.target.getAttribute('data-user-id');
            const name = e.target.getAttribute('data-name');
            selectDirector(userId, name);
        }
    });
});

// Función principal de selección de director
function selectDirector(userId, name) {
    // Actualizar campos visibles y ocultos
    document.getElementById('director_user_id').value = userId;
    document.getElementById('selectedDirectorName').textContent = name;

    // Cerrar el modal (Bootstrap 5)
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('searchDirectorModal'));
    modal.hide();

    // Mostrar mensaje de éxito
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Director Seleccionado',
            text: name,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } else {
        alert('Director seleccionado: ' + name);
    }
}
