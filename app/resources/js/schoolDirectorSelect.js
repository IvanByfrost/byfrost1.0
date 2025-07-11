// schoolDirectorSelect.js

document.addEventListener('DOMContentLoaded', function() {
    // Delegación para botones de selección de director
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-director-btn')) {
            const userId = e.target.getAttribute('data-user-id');
            const name = e.target.getAttribute('data-name');
            document.getElementById('director_user_id').value = userId;
            document.getElementById('selectedDirectorName').textContent = name;
            // Cierra el modal (si usas Bootstrap 5)
            const modal = bootstrap.Modal.getInstance(document.getElementById('searchDirectorModal'));
            if (modal) modal.hide();
        }
    });
}); 